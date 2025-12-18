<?php
require 'koneksi.php';
// session_start();

date_default_timezone_set('Asia/Makassar');

// =====================================================
// VALIDASI LOGIN
// =====================================================
if (!isset($_SESSION['id_siswa_232410'])) {
    echo "NOT_LOGIN";
    exit;
}

$id_siswa = $_SESSION['id_siswa_232410'];

// =====================================================
// VALIDASI QR
// =====================================================
if (!isset($_POST['qr'])) {
    echo "QR_EMPTY";
    exit;
}

$qr = $_POST['qr'];

// =====================================================
// AMBIL QR & KELAS SISWA
// =====================================================
$q = mysqli_prepare($koneksi, "
    SELECT qr_code_232410, kelas_232410 
    FROM siswa_232410 
    WHERE id_siswa_232410 = ?
");
mysqli_stmt_bind_param($q, "i", $id_siswa);
mysqli_stmt_execute($q);
mysqli_stmt_bind_result($q, $qr_siswa, $kelas_siswa);
mysqli_stmt_fetch($q);
mysqli_stmt_close($q);

// Validasi QR sesuai akun siswa
if ($qr_siswa == "" || $qr !== $qr_siswa) {
    echo "QR_INVALID";
    exit;
}

// =====================================================
// CEK ABSEN HARI INI
// =====================================================
$tgl = date("Y-m-d");

$q2 = mysqli_prepare($koneksi, "
    SELECT id_absensi_232410 
    FROM absensi_232410 
    WHERE id_siswa_232410 = ? AND tanggal_232410 = ?
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

$hariIndo = $hariMap[$hariInggris];

// Ambil semua jadwal hari ini
$qJadwal = mysqli_prepare($koneksi, "
    SELECT mata_pelajaran_232410, jam_mulai_232410, jam_selesai_232410
    FROM jadwal_232410
    WHERE id_kelas_232410 = ?
    AND hari_232410 = ?
    ORDER BY jam_mulai_232410 ASC
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

    $mapel = $row['mata_pelajaran_232410'];
    $jam_mulai = $row['jam_mulai_232410'];
    $jam_selesai = $row['jam_selesai_232410'];

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
    INSERT INTO absensi_232410
    (id_siswa_232410, tanggal_232410, waktu_scan_232410, status_kehadiran_232410)
    VALUES (?, ?, ?, ?)
");
mysqli_stmt_bind_param($q3, "isss", $id_siswa, $tgl, $now, $status);

if (mysqli_stmt_execute($q3)) {
    echo $status; 
} else {
    echo "ERROR";
}

mysqli_stmt_close($q3);
