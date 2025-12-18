<?php
require 'koneksi.php';
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$tipe     = $_GET['tipe'] ?? 'hari';
$tanggal  = $_GET['tanggal'] ?? '';
$idKelas  = $_GET['id_kelas'] ?? '';

$where = "";

if ($tipe == "hari" && !empty($tanggal)) {
    $where = "WHERE a.tanggal_232410 = '$tanggal'";
} elseif ($tipe == "minggu" && !empty($tanggal)) {
    $minggu = date("W", strtotime($tanggal));
    $tahun  = date("Y", strtotime($tanggal));

    $where = "WHERE WEEK(a.tanggal_232410, 1) = '$minggu'
              AND YEAR(a.tanggal_232410) = '$tahun'";
} elseif ($tipe == "bulan" && !empty($tanggal)) {
    list($tahun, $bulan) = explode("-", $tanggal);

    $where = "WHERE MONTH(a.tanggal_232410) = '$bulan'
              AND YEAR(a.tanggal_232410) = '$tahun'";
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


// --------------------------
// GENERATE HTML UNTUK PDF
// --------------------------
$html = "
<h2 style='text-align:center;'>Laporan Absensi Siswa</h2>
<p><b>Filter:</b> ".ucfirst($tipe)." – ".($tanggal ?: '-')."</p>

<table border='1' width='100%' cellspacing='0' cellpadding='6'>
<thead>
<tr style='background:#eef3ff; text-align:center;'>
    <th>No</th>
    <th>Nama Siswa</th>
    <th>NISN</th>
    <th>Kelas</th>
    <th>Tanggal</th>
    <th>Waktu Scan</th>
    <th>Status</th>
</tr>
</thead>
<tbody>
";

$no = 1;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {

        $html .= "
        <tr>
            <td style='text-align:center;'>".$no++."</td>
            <td>".$row['nama_siswa_232410']."</td>
            <td>".$row['nisn_232410']."</td>
            <td>".$row['kelas_232410']."</td>
            <td style='text-align:center;'>".$row['tanggal_232410']."</td>
            <td style='text-align:center;'>".$row['waktu_scan_232410']."</td>
            <td style='text-align:center;'>".$row['status_kehadiran_232410']."</td>
        </tr>
        ";
    }
} else {
    $html .= "
    <tr>
        <td colspan='7' style='text-align:center;'>Tidak ada data untuk filter ini.</td>
    </tr>
    ";
}

$html .= "</tbody></table>";


// --------------------------
// GENERATE PDF DOMPDF
// --------------------------
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Tampilkan PDF
$dompdf->stream("laporan_absensi.pdf", ["Attachment" => false]);
