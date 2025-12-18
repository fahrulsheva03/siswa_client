<?php
require 'koneksi.php';

if (!isset($_SESSION['login_siswa'])) {
  echo "<script>alert('Anda belum login!');
     window.location.href='login_siswa.php';</script>";
  exit;
}

include 'includes/header.php';
include 'includes/navbar.php';

// ID siswa yang login
$id_siswa = $_SESSION['id_siswa_232410'];

// QUERY RIWAYAT ABSEN LENGKAP + JADWAL (MAPEL & HARI)
$query = "
    SELECT 
        a.tanggal_232410,
        a.waktu_scan_232410,
        a.status_kehadiran_232410,
        j.mata_pelajaran_232410,
        j.hari_232410
    FROM absensi_232410 a
    JOIN siswa_232410 s 
        ON a.id_siswa_232410 = s.id_siswa_232410
    JOIN kelas_232410 k
        ON s.kelas_232410 = k.id_kelas_232410
    LEFT JOIN jadwal_232410 j
        ON j.id_kelas_232410 = k.id_kelas_232410
        AND a.waktu_scan_232410 BETWEEN j.jam_mulai_232410 AND j.jam_selesai_232410
    WHERE a.id_siswa_232410 = ?
    ORDER BY a.tanggal_232410 DESC, a.waktu_scan_232410 DESC
";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-5">
  <h3 class="fw-bold mb-4">Riwayat Absensi Anda</h3>

  <table class="table table-hover bg-white rounded">
    <thead class="table-dark">
      <tr>
        <th>Tanggal</th>
        <th>Hari</th>
        <th>Mata Pelajaran</th>
        <th>Waktu</th>
        <th>Status</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>

      <?php
      if (mysqli_num_rows($result) == 0) {
        echo "<tr><td colspan='6' class='text-center'>Belum ada riwayat absensi.</td></tr>";
      }

      while ($row = mysqli_fetch_assoc($result)) {

        $hari   = $row['hari_232410'] ?: "-";
        $mapel  = $row['mata_pelajaran_232410'] ?: "-";

        $tanggal = date("d-m-Y", strtotime($row['tanggal_232410']));
        $waktu   = $row['waktu_scan_232410'] ?: "-";
        $status  = $row['status_kehadiran_232410'];

        if ($status == "Hadir") {
          $badge = "<span class='badge bg-success'>Hadir</span>";
          $ket   = "Tepat waktu";
        } elseif ($status == "Terlambat") {
          $badge = "<span class='badge bg-warning text-dark'>Terlambat</span>";
          $ket   = "Siswa datang terlambat";
        } else {
          $badge = "<span class='badge bg-danger'>Alfa</span>";
          $ket   = "Tidak hadir";
        }

        echo "
            <tr>
            <td>{$tanggal}</td>
              <td>{$hari}</td>
              <td>{$mapel}</td>
              <td>{$waktu}</td>
              <td>{$badge}</td>
              <td>{$ket}</td>
            </tr>
          ";
      }
      ?>

    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>