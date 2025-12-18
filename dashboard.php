<?php 
require 'koneksi.php';

// Jika dashboard hanya boleh diakses setelah login
if(!isset($_SESSION['login_siswa'])){
     echo "<script>alert('Anda belum login!');
     window.location.href='login_siswa.php';</script>";
     exit;
}

// SIMPAN ID SISWA DARI SESSION
$id_siswa = $_SESSION['id_siswa_232410']; // pastikan ini sudah di-set waktu login


include 'includes/header.php'; 
include 'includes/navbar.php'; 
?>

<?php
// =============================
// Hitung total Hadir, Terlambat, Alfa per siswa
// =============================

// Total Hadir
$qHadir = mysqli_prepare($koneksi, 
    "SELECT COUNT(*) FROM absensi_232410 
     WHERE id_siswa_232410 = ? 
     AND status_kehadiran_232410 = 'Hadir'"
);
mysqli_stmt_bind_param($qHadir, "i", $id_siswa);
mysqli_stmt_execute($qHadir);
mysqli_stmt_bind_result($qHadir, $totalHadir);
mysqli_stmt_fetch($qHadir);
mysqli_stmt_close($qHadir);

// Total Telat
$qTelat = mysqli_prepare($koneksi, 
    "SELECT COUNT(*) FROM absensi_232410 
     WHERE id_siswa_232410 = ? 
     AND status_kehadiran_232410 = 'Terlambat'"
);
mysqli_stmt_bind_param($qTelat, "i", $id_siswa);
mysqli_stmt_execute($qTelat);
mysqli_stmt_bind_result($qTelat, $totalTelat);
mysqli_stmt_fetch($qTelat);
mysqli_stmt_close($qTelat);

// Total Alfa
$qAlfa = mysqli_prepare($koneksi, 
    "SELECT COUNT(*) FROM absensi_232410 
     WHERE id_siswa_232410 = ? 
     AND status_kehadiran_232410 = 'Alfa'"
);
mysqli_stmt_bind_param($qAlfa, "i", $id_siswa);
mysqli_stmt_execute($qAlfa);
mysqli_stmt_bind_result($qAlfa, $totalAlfa);
mysqli_stmt_fetch($qAlfa);
mysqli_stmt_close($qAlfa);

?>

<div class="container mt-4">
  <div class="row g-4">

    <!-- Total Hadir -->
    <div class="col-md-4">
      <div class="card text-center p-3 shadow-sm">
        <h5 class="fw-bold">Total Kehadiran</h5>
        <h2 class="text-success"><?= $totalHadir ?> Hari</h2>
      </div>
    </div>

    <!-- Total Terlambat -->
    <div class="col-md-4">
      <div class="card text-center p-3 shadow-sm">
        <h5 class="fw-bold">Terlambat</h5>
        <h2 class="text-warning"><?= $totalTelat ?> Kali</h2>
      </div>
    </div>

    <!-- Total Alfa -->
    <div class="col-md-4">
      <div class="card text-center p-3 shadow-sm">
        <h5 class="fw-bold">Tidak Hadir (Alfa)</h5>
        <h2 class="text-danger"><?= $totalAlfa ?> Hari</h2>
      </div>
    </div>

  </div>

  <div class="text-center mt-5">
    <a href="scan.php" class="btn btn-primary btn-lg shadow">📷 Scan QR untuk Absen</a>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
