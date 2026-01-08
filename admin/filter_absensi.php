<?php
require 'koneksi.php';

// Fungsi: Mengambil data absensi siswa yang sudah difilter berdasarkan periode, kelas, dan mata pelajaran.
// Parameter input:
// - $_POST['tipe']: jenis filter waktu (hari, minggu, atau bulan).
// - $_POST['tanggal']: tanggal acuan untuk perhitungan hari, minggu, atau bulan.
// - $_POST['id_kelas']: ID kelas yang ingin difilter (opsional).
// - $_POST['mapel']: nama mata pelajaran yang ingin difilter (opsional).
// Return value:
// - Tidak mengembalikan nilai; mengeluarkan baris-baris HTML <tr> yang akan dimasukkan ke tabel melalui AJAX.
// Contoh penggunaan:
// - Dipanggil oleh fungsi JavaScript loadData() di admin/absen.php menggunakan XMLHttpRequest POST.
// Catatan penting:
// - Query melakukan JOIN ke tabel siswa, kelas, dan jadwal untuk menampilkan informasi lengkap absensi per siswa.
$tipe    = $_POST['tipe'];
$tanggal = $_POST['tanggal'];
$idKelas = isset($_POST['id_kelas']) ? $_POST['id_kelas'] : '';
$mapel   = isset($_POST['mapel']) ? trim($_POST['mapel']) : '';

$where = "";

if ($tipe == "hari") {
    if (!empty($tanggal)) {
        $where = "WHERE a.tanggal_232410 = '$tanggal'";
    }
} elseif ($tipe == "minggu") {
    if (!empty($tanggal)) {
        $minggu = date("W", strtotime($tanggal));
        $tahun  = date("Y", strtotime($tanggal));

        $where = "WHERE WEEK(a.tanggal_232410, 1) = '$minggu'
                  AND YEAR(a.tanggal_232410) = '$tahun'";
    }
} elseif ($tipe == "bulan") {
    if (!empty($tanggal)) {
        list($tahun, $bulan) = explode("-", $tanggal);

        $where = "WHERE MONTH(a.tanggal_232410) = '$bulan'
                  AND YEAR(a.tanggal_232410) = '$tahun'";
    }
}

if (!empty($idKelas)) {
    $idKelas = mysqli_real_escape_string($koneksi, $idKelas);
    if ($where === "") {
        $where = "WHERE s.kelas_232410 = '$idKelas'";
    } else {
        $where .= " AND s.kelas_232410 = '$idKelas'";
    }
}

if ($mapel !== '') {
    $mapel = mysqli_real_escape_string($koneksi, $mapel);
    if ($where === "") {
        $where = "WHERE j.mata_pelajaran_232410 = '$mapel'";
    } else {
        $where .= " AND j.mata_pelajaran_232410 = '$mapel'";
    }
}

$query = mysqli_query($koneksi, "
    SELECT 
        a.id_absensi_232410,
        a.tanggal_232410,
        a.waktu_scan_232410,
        a.status_kehadiran_232410,
        s.nama_siswa_232410,
        s.nisn_232410,
        s.kelas_232410
    FROM absensi_232410 AS a
    JOIN siswa_232410 AS s
      ON s.id_siswa_232410 = a.id_siswa_232410
    LEFT JOIN kelas_232410 AS k
       ON s.kelas_232410 = k.id_kelas_232410
    LEFT JOIN jadwal_232410 AS j
       ON j.id_kelas_232410 = k.id_kelas_232410
      AND a.waktu_scan_232410 BETWEEN j.jam_mulai_232410 AND j.jam_selesai_232410
    $where
    ORDER BY a.tanggal_232410 DESC, a.waktu_scan_232410 DESC
");

$no = 1;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {

        $status = $row['status_kehadiran_232410'];

        if ($status == "Hadir") {
            $badge = "<span class='badge bg-success'>Hadir</span>";
        } elseif ($status == "Terlambat") {
            $badge = "<span class='badge bg-warning text-dark'>Terlambat</span>";
        } else {
            $badge = "<span class='badge bg-danger'>Alfa</span>";
        }

        echo "
        <tr>
            <td class='text-center'>{$no}</td>
            <td>{$row['nama_siswa_232410']}</td>
            <td>{$row['nisn_232410']}</td>
            <td>{$row['kelas_232410']}</td>
            <td class='text-center'>{$row['tanggal_232410']}</td>
            <td class='text-center'>{$row['waktu_scan_232410']}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
                <a href='edit/absen.php?id={$row['id_absensi_232410']}' class='btn btn-warning btn-sm text-white'>
                    <i class='bi bi-pencil-square'></i>
                </a>
            </td>
        </tr>
        ";

        $no++;
    }
} else {
    echo "
    <tr>
        <td colspan='8' class='text-center'>Tidak ada data untuk filter ini.</td>
    </tr>";
}
