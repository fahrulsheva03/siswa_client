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
        $where = "WHERE a.tanggal = '$tanggal'";
    }
} elseif ($tipe == "minggu") {
    if (!empty($tanggal)) {
        $minggu = date("W", strtotime($tanggal));
        $tahun  = date("Y", strtotime($tanggal));

        $where = "WHERE WEEK(a.tanggal, 1) = '$minggu'
                  AND YEAR(a.tanggal) = '$tahun'";
    }
} elseif ($tipe == "bulan") {
    if (!empty($tanggal)) {
        list($tahun, $bulan) = explode("-", $tanggal);

        $where = "WHERE MONTH(a.tanggal) = '$bulan'
                  AND YEAR(a.tanggal) = '$tahun'";
    }
}

if (!empty($idKelas)) {
    $idKelas = mysqli_real_escape_string($koneksi, $idKelas);
    if ($where === "") {
        $where = "WHERE s.id_kelas = '$idKelas'";
    } else {
        $where .= " AND s.id_kelas = '$idKelas'";
    }
}

if ($mapel !== '') {
    $mapel = mysqli_real_escape_string($koneksi, $mapel);
    if ($where === "") {
        $where = "WHERE j.mata_pelajaran = '$mapel'";
    } else {
        $where .= " AND j.mata_pelajaran = '$mapel'";
    }
}

$query = mysqli_query($koneksi, "
    SELECT 
        a.id_absensi,
        a.tanggal,
        a.waktu_scan,
        a.status,
        s.nama_siswa,
        s.nis,
        k.nama_kelas
    FROM absensi AS a
    JOIN siswa AS s
      ON s.id_siswa = a.id_siswa
    LEFT JOIN kelas AS k
       ON s.id_kelas = k.id_kelas
    LEFT JOIN jadwal AS j
       ON j.id_kelas = k.id_kelas
      AND TIME(a.waktu_scan) BETWEEN j.jam_mulai AND j.jam_selesai
      AND j.hari = (
          CASE DAYOFWEEK(a.tanggal)
              WHEN 1 THEN 'Minggu'
              WHEN 2 THEN 'Senin'
              WHEN 3 THEN 'Selasa'
              WHEN 4 THEN 'Rabu'
              WHEN 5 THEN 'Kamis'
              WHEN 6 THEN 'Jumat'
              WHEN 7 THEN 'Sabtu'
          END
      )
    $where
    ORDER BY a.tanggal DESC, a.waktu_scan DESC
");

$no = 1;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {

        $status = strtolower($row['status']);

        if ($status == "hadir") {
            $badge = "<span class='badge bg-success'>Hadir</span>";
        } elseif ($status == "terlambat") {
            $badge = "<span class='badge bg-warning text-dark'>Terlambat</span>";
        } else {
            $badge = "<span class='badge bg-danger'>Alfa</span>";
        }

        echo "
        <tr>
            <td class='text-center'>{$no}</td>
            <td>{$row['nama_siswa']}</td>
            <td>{$row['nis']}</td>
            <td>{$row['nama_kelas']}</td>
            <td class='text-center'>{$row['tanggal']}</td>
            <td class='text-center'>{$row['waktu_scan']}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
                <a href='edit/absen.php?id={$row['id_absensi']}' class='btn btn-warning btn-sm text-white'>
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
