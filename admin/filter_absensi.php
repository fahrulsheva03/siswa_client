<?php
require 'koneksi.php';

$tipe    = $_POST['tipe'];
$tanggal = $_POST['tanggal'];
$idKelas = isset($_POST['id_kelas']) ? $_POST['id_kelas'] : '';

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
        <td colspan='8' class='text-center'>Tidak ada data.</td>
    </tr>";
}
