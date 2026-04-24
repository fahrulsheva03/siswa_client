<?php
require 'koneksi.php';
// session_start();

date_default_timezone_set('Asia/Makassar');

// Fungsi: Memproses data QR yang discan siswa untuk mencatat absensi pada jadwal yang sesuai.
// Parameter input:
// - $_SESSION['id_siswa']: identitas siswa yang sedang login dan melakukan scan.
// - $_POST['qr']: nilai QR yang diterima dari client, berisi nama file atau kode QR siswa.
// Return value:
// - Mengirimkan string status ke client (Hadir, Terlambat, ALREADY, QR_INVALID, NO_CLASS, NOT_STARTED, dll.) dan tidak mengembalikan nilai PHP.
// Contoh penggunaan:
// - Dipanggil oleh JavaScript pada halaman scan.php melalui permintaan POST fetch("proses_scan.php", ...).
// Catatan penting:
// - Menggunakan prepared statement untuk akses database dan menentukan status kehadiran berdasarkan jadwal dan waktu scan saat ini.
// =====================================================
// VALIDASI LOGIN
// =====================================================
if (!isset($_SESSION['id_siswa'])) {
    echo "NOT_LOGIN";
    exit;
}

$id_siswa = $_SESSION['id_siswa'];

// =====================================================
// VALIDASI QR
// =====================================================
if (!isset($_POST['qr'])) {
    echo "QR_EMPTY";
    exit;
}

$qr = trim($_POST['qr']);
if ($qr === "") {
    echo "QR_EMPTY";
    exit;
}

// Normalisasi input QR dari berbagai sumber:
// - hasil decode kamera (isi QR: ABSEN-...)
// - nama file (qr_ABSEN-....png/.svg)
// - URL gambar penuh (http.../qr_....png?x=1)
function normalizeQrCandidate(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    $value = rawurldecode($value);

    // Jika input berupa URL/path, ambil nama file saja.
    $path = parse_url($value, PHP_URL_PATH);
    if (!empty($path)) {
        $value = basename($path);
    }

    // Hapus query/fragment sisa yang mungkin terbawa.
    $value = preg_replace('/[?#].*$/', '', $value);
    return trim($value);
}

// Turunkan berbagai bentuk token yang valid dari nilai qr_code di database.
function buildValidQrTokens(string $storedQr): array
{
    $tokens = [];
    $storedQr = trim($storedQr);
    if ($storedQr === '') {
        return $tokens;
    }

    $tokens[] = $storedQr; // contoh: qr_ABSEN-1-12345.png

    $withoutExt = preg_replace('/\.[a-z0-9]+$/i', '', $storedQr);
    if (!empty($withoutExt)) {
        $tokens[] = $withoutExt; // contoh: qr_ABSEN-1-12345
    }

    $payload = preg_replace('/^qr_/i', '', $withoutExt ?? $storedQr);
    if (!empty($payload)) {
        $tokens[] = $payload; // contoh: ABSEN-1-12345 (isi QR kamera)
    }

    return array_values(array_unique(array_filter($tokens)));
}

// =====================================================
// AMBIL QR & KELAS SISWA
// =====================================================
$q = mysqli_prepare($koneksi, "
    SELECT qr_code, kelas 
    FROM siswa 
    WHERE id_siswa = ?
");
mysqli_stmt_bind_param($q, "i", $id_siswa);
mysqli_stmt_execute($q);
mysqli_stmt_bind_result($q, $qr_siswa, $kelas_siswa);
mysqli_stmt_fetch($q);
mysqli_stmt_close($q);

// Validasi QR sesuai akun siswa
$qrInput = normalizeQrCandidate($qr);
$validTokens = buildValidQrTokens((string) $qr_siswa);
if (empty($validTokens) || !in_array($qrInput, $validTokens, true)) {
    echo "QR_INVALID";
    exit;
}

// =====================================================
// CEK ABSEN HARI INI
// =====================================================
$tgl = date("Y-m-d");
$waktu_scan = date("Y-m-d H:i:s");

$q2 = mysqli_prepare($koneksi, "
    SELECT id_absensi 
    FROM absensi 
    WHERE id_siswa = ? AND tanggal = ?
");
mysqli_stmt_bind_param($q2, "is", $id_siswa, $tgl);
mysqli_stmt_execute($q2);
mysqli_stmt_store_result($q2);
$already = mysqli_stmt_num_rows($q2) > 0;
mysqli_stmt_close($q2);

if ($already) {
    echo "ALREADY"; // Sudah absen hari ini
    exit;
}

// =====================================================
// AMBIL JADWAL SESUAI HARI & KELAS SISWA
// =====================================================
$hariInggris = date("l");
$hariMap = [
    "Monday" => "Senin",
    "Tuesday" => "Selasa",
    "Wednesday" => "Rabu",
    "Thursday" => "Kamis",
    "Friday" => "Jumat",
    "Saturday" => "Sabtu"
];

$hariIndo = $hariMap[$hariInggris] ?? null;
if ($hariIndo === null) {
    echo "NO_CLASS";
    exit;
}

// Ambil semua jadwal hari ini
$qJadwal = mysqli_prepare($koneksi, "
    SELECT mata_pelajaran, jam_mulai, jam_selesai
    FROM jadwal
    WHERE id_kelas = ?
    AND hari = ?
    ORDER BY jam_mulai ASC
");
mysqli_stmt_bind_param($qJadwal, "is", $kelas_siswa, $hariIndo);
mysqli_stmt_execute($qJadwal);
$result = mysqli_stmt_get_result($qJadwal);

$foundSchedule = false;
$now = date("H:i:s");

// =====================================================
// LOGIKA MEMILIH MAPEL YANG SEDANG/HARUSNYA BERLANGSUNG
// =====================================================
while ($row = mysqli_fetch_assoc($result)) {

    $mapel = $row['mata_pelajaran'];
    $jam_mulai = $row['jam_mulai'];
    $jam_selesai = $row['jam_selesai'];

    // 1. Scan sebelum pelajaran dimulai → TOLAK
    if ($now < $jam_mulai) {
        echo "NOT_STARTED|$mapel|$jam_mulai";
        exit;
    }

    // 2. Scan dalam jam pelajaran
    if ($now >= $jam_mulai && $now <= $jam_selesai) {

        // Grace time = 15 menit setelah jam mulai
        $grace = date("H:i:s", strtotime($jam_mulai . " +15 minutes"));

        if ($now <= $grace) {
            $status = "Hadir";
        } else {
            $status = "Terlambat";
        }

        $foundSchedule = true;
        break;
    }

    // 3. Kalau lewat jam selesai, cek mapel berikutnya
    if ($now > $jam_selesai) {
        continue;
    }
}

mysqli_stmt_close($qJadwal);

// Jika tidak ada mapel yang cocok → Alfa
if (!$foundSchedule) {
    echo "NO_CLASS"; 
    exit;
}

// =====================================================
// SIMPAN ABSENSI
// =====================================================
$q3 = mysqli_prepare($koneksi, "
    INSERT INTO absensi
    (id_siswa, tanggal, waktu_scan, status_kehadiran)
    VALUES (?, ?, ?, ?)
");
mysqli_stmt_bind_param($q3, "isss", $id_siswa, $tgl, $waktu_scan, $status);

if (mysqli_stmt_execute($q3)) {
    echo $status; 
} else {
    echo "ERROR";
}

mysqli_stmt_close($q3);
