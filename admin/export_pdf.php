<?php
require 'koneksi.php';
require 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Fungsi: Membuat laporan PDF absensi siswa berdasarkan filter waktu dan kelas yang dipilih.
// Parameter input:
// - $_GET['tipe']: tipe filter waktu (hari, minggu, atau bulan).
// - $_GET['tanggal']: tanggal acuan untuk filter yang dipilih.
// - $_GET['id_kelas']: ID kelas yang ingin difilter (opsional).
// Return value:
// - Tidak mengembalikan nilai; menghasilkan output PDF langsung ke browser menggunakan Dompdf.
// Contoh penggunaan:
// - Dipanggil dari tombol "Export PDF" pada admin/absen.php yang membuka export_pdf.php di tab baru.
// Catatan penting:
// - Menggabungkan data dari tabel absensi dan siswa, lalu merender HTML sederhana menjadi PDF ukuran A4 portrait.
$tipe     = $_GET['tipe'] ?? 'hari';
$tanggal  = $_GET['tanggal'] ?? '';
$idKelas  = $_GET['id_kelas'] ?? '';

$where = "";

if ($tipe == "hari" && !empty($tanggal)) {
    $where = "WHERE a.tanggal = '$tanggal'";
} elseif ($tipe == "minggu" && !empty($tanggal)) {
    $minggu = date("W", strtotime($tanggal));
    $tahun  = date("Y", strtotime($tanggal));

    $where = "WHERE WEEK(a.tanggal, 1) = '$minggu'
              AND YEAR(a.tanggal) = '$tahun'";
} elseif ($tipe == "bulan" && !empty($tanggal)) {
    list($tahun, $bulan) = explode("-", $tanggal);

    $where = "WHERE MONTH(a.tanggal) = '$bulan'
              AND YEAR(a.tanggal) = '$tahun'";
}

if (!empty($idKelas)) {
    $idKelas = mysqli_real_escape_string($koneksi, $idKelas);
    if ($where === "") {
        $where = "WHERE s.id_kelas = '$idKelas'";
    } else {
        $where .= " AND s.id_kelas = '$idKelas'";
    }
}

$query = mysqli_query($koneksi, "
    SELECT 
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
    $where
    ORDER BY a.tanggal DESC, a.waktu_scan DESC
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
    <th>NIS</th>
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
            <td>".$row['nama_siswa']."</td>
            <td>".$row['nis']."</td>
            <td>".$row['nama_kelas']."</td>
            <td style='text-align:center;'>".$row['tanggal']."</td>
            <td style='text-align:center;'>".$row['waktu_scan']."</td>
            <td style='text-align:center;'>".ucfirst($row['status'])."</td>
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
