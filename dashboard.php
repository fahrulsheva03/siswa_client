<?php 
require 'koneksi.php';

// Fungsi: Menampilkan ringkasan statistik kehadiran siswa yang sedang login pada halaman dashboard.
// Parameter input:
// - $_SESSION['login_siswa']: penanda bahwa siswa sudah berhasil login.
// - $_SESSION['id_siswa']: ID siswa yang digunakan untuk mengambil data absensi dari database.
// Return value:
// - Tidak mengembalikan nilai; menampilkan tiga kartu statistik (Hadir, Terlambat, Alfa) di tampilan dashboard.
// Contoh penggunaan:
// - Diakses setelah siswa login dan diarahkan ke dashboard.php dari proses login.
// Catatan penting:
// - Menggunakan tiga query COUNT terpisah untuk setiap status, sehingga bisa dipertimbangkan optimasi bila data sangat besar.
// Jika dashboard hanya boleh diakses setelah login
if(!isset($_SESSION['login_siswa'])){
     echo "<script>alert('Anda belum login!');
     window.location.href='login_siswa.php';</script>";
     exit;
}

// SIMPAN ID SISWA DARI SESSION
$id_siswa = $_SESSION['id_siswa']; // pastikan ini sudah di-set waktu login


include 'includes/header.php'; 
include 'includes/navbar.php'; 
?>

<?php
// =============================
// Hitung total Hadir, Terlambat, Alfa per siswa
// =============================

// Menggunakan query yang lebih fleksibel terhadap perbedaan nama kolom atau casing status
$query = mysqli_prepare($koneksi, "
    SELECT 
        COUNT(CASE WHEN LOWER(status) = 'hadir' OR status_kehadiran = 'Hadir' THEN 1 END) as totalHadir,
        COUNT(CASE WHEN LOWER(status) = 'terlambat' OR status_kehadiran = 'Terlambat' THEN 1 END) as totalTelat,
        COUNT(CASE WHEN LOWER(status) = 'tidak_hadir' OR status_kehadiran = 'Alfa' THEN 1 END) as totalAlfa
    FROM absensi 
    WHERE id_siswa = ?
");
mysqli_stmt_bind_param($query, "i", $id_siswa);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $totalHadir, $totalTelat, $totalAlfa);
mysqli_stmt_fetch($query);
mysqli_stmt_close($query);

// Ambil info siswa
$qSiswa = mysqli_prepare($koneksi, "
    SELECT s.nama_siswa, k.nama_kelas 
    FROM siswa s 
    LEFT JOIN kelas k ON k.id_kelas = COALESCE(s.id_kelas, s.kelas)
    WHERE s.id_siswa = ?
");
mysqli_stmt_bind_param($qSiswa, "i", $id_siswa);
mysqli_stmt_execute($qSiswa);
mysqli_stmt_bind_result($qSiswa, $nama_siswa, $nama_kelas);
mysqli_stmt_fetch($qSiswa);
mysqli_stmt_close($qSiswa);
?>

<div class="container mt-5">
  <div class="card border-0 shadow-sm p-4 mb-4 bg-primary text-white">
    <div class="d-flex align-items-center gap-3">
      <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
        <i class="bi bi-person-fill fs-3"></i>
      </div>
      <div>
        <h4 class="fw-bold mb-0">Selamat Datang, <?= htmlspecialchars($nama_siswa ?? 'Siswa') ?>!</h4>
        <p class="mb-0 opacity-75">Kelas: <?= htmlspecialchars($nama_kelas ?? '-') ?></p>
      </div>
    </div>
  </div>

  <div class="row g-4">

    <!-- Total Hadir -->
    <div class="col-md-4">
      <a href="riwayat.php?status=hadir" class="text-decoration-none">
        <div class="card border-0 shadow-sm text-center p-4 h-100 border-bottom border-success border-4">
          <div class="mb-3 text-success">
            <i class="bi bi-check-circle-fill fs-1"></i>
          </div>
          <h5 class="fw-bold text-muted">Total Kehadiran</h5>
          <h2 class="fw-bold text-success mb-0"><?= $totalHadir ?> <small class="fs-6 text-muted">Hari</small></h2>
        </div>
      </a>
    </div>

    <!-- Total Terlambat -->
    <div class="col-md-4">
      <a href="riwayat.php?status=terlambat" class="text-decoration-none">
        <div class="card border-0 shadow-sm text-center p-4 h-100 border-bottom border-warning border-4">
          <div class="mb-3 text-warning">
            <i class="bi bi-clock-history fs-1"></i>
          </div>
          <h5 class="fw-bold text-muted">Terlambat</h5>
          <h2 class="fw-bold text-warning mb-0"><?= $totalTelat ?> <small class="fs-6 text-muted">Kali</small></h2>
        </div>
      </a>
    </div>

    <!-- Total Alfa -->
    <div class="col-md-4">
      <a href="riwayat.php?status=tidak_hadir" class="text-decoration-none">
        <div class="card border-0 shadow-sm text-center p-4 h-100 border-bottom border-danger border-4">
          <div class="mb-3 text-danger">
            <i class="bi bi-x-circle-fill fs-1"></i>
          </div>
          <h5 class="fw-bold text-muted">Tidak Hadir (Alfa)</h5>
          <h2 class="fw-bold text-danger mb-0"><?= $totalAlfa ?> <small class="fs-6 text-muted">Hari</small></h2>
        </div>
      </a>
    </div>

  </div>

  <div class="row mt-4">
    <div class="col-md-12 text-center">
      <div class="card border-0 shadow-sm p-5">
        <h4 class="fw-bold mb-3">Siap untuk Belajar Hari Ini?</h4>
        <p class="text-muted mb-4">Pastikan Anda melakukan scan QR Code tepat waktu untuk mencatat kehadiran.</p>
        <div class="d-flex justify-content-center gap-3">
          <a href="scan.php" class="btn btn-primary btn-lg px-5 shadow">
            <i class="bi bi-qr-code-scan me-2"></i>Scan QR Absen
          </a>
          <a href="riwayat.php" class="btn btn-outline-primary btn-lg px-5">
            <i class="bi bi-calendar3 me-2"></i>Lihat Riwayat
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
