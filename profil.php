<?php 
require 'koneksi.php';

// Cek apakah siswa sudah login
if (!isset($_SESSION['login_siswa']) || !isset($_SESSION['id_siswa'])) {
    echo "<script>
        alert('Anda belum login!');
        window.location.href='login_siswa.php';
    </script>";
    exit;
}

include 'includes/header.php'; 
include 'includes/navbar.php'; 

// Ambil ID siswa dari session
$id_siswa = $_SESSION['id_siswa'];

// Ambil data lengkap siswa dari database
$stmt = mysqli_prepare($koneksi, "
    SELECT 
        id_siswa,
        nisn,
        nama_siswa,
        kelas.nama_kelas,
        qr_code,
        created_at,
        status
    FROM siswa
    JOIN kelas ON siswa.kelas = kelas.id_kelas
    WHERE id_siswa = ?
");
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);
?>

<div class="container mt-5">
  <div class="card mx-auto p-4 shadow-sm" style="max-width:500px;">
    <div class="text-center mb-3">

      <!-- Foto Profil Default -->
      <!-- <img src="https://via.placeholder.com/120" class="rounded-circle mb-3" alt="Foto Profil"> -->

      <!-- Nama siswa -->
      <h4 class="fw-bold">
        <?= htmlspecialchars($data['nama_siswa']); ?>
      </h4>
      <p class="text-muted">Siswa Kelas <?= htmlspecialchars($data['nama_kelas']); ?></p>
    </div>

    <hr>

    <!-- Detail akun -->
    <p><strong>NISN:</strong> <?= htmlspecialchars($data['nisn']); ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($data['nama_kelas']); ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($data['status']); ?></p>
    <p><strong>Akun Dibuat:</strong> <?= htmlspecialchars($data['created_at']); ?></p>

    <hr>

    <h5 class="fw-bold text-center mb-3">QR Code Absensi Anda</h5>
    <div class="text-center">
      <?php if (!empty($data['qr_code'])): ?>
          <img src="admin/qr_images/<?= htmlspecialchars($data['qr_code']); ?>" width="200">
      <?php else: ?>
          <p class="text-danger">QR tidak tersedia.</p>
      <?php endif; ?>
    </div>

    <div class="text-center mt-4">
      <a href="logout.php" class="btn btn-danger w-100">Logout</a>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
