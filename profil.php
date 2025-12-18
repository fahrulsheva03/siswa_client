<?php 
require 'koneksi.php';

// Cek apakah siswa sudah login
if (!isset($_SESSION['login_siswa']) || !isset($_SESSION['id_siswa_232410'])) {
    echo "<script>
        alert('Anda belum login!');
        window.location.href='login_siswa.php';
    </script>";
    exit;
}

include 'includes/header.php'; 
include 'includes/navbar.php'; 

// Ambil ID siswa dari session
$id_siswa = $_SESSION['id_siswa_232410'];

// Ambil data lengkap siswa dari database
$stmt = mysqli_prepare($koneksi, "
    SELECT 
        id_siswa_232410,
        nisn_232410,
        nama_siswa_232410,
        kelas_232410.nama_kelas_232410,
        qr_code_232410,
        created_at_232410,
        status_232410
    FROM siswa_232410
    JOIN kelas_232410 ON siswa_232410.kelas_232410 = kelas_232410.id_kelas_232410
    WHERE id_siswa_232410 = ?
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
        <?= htmlspecialchars($data['nama_siswa_232410']); ?>
      </h4>
      <p class="text-muted">Siswa Kelas <?= htmlspecialchars($data['nama_kelas_232410']); ?></p>
    </div>

    <hr>

    <!-- Detail akun -->
    <p><strong>NISN:</strong> <?= htmlspecialchars($data['nisn_232410']); ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($data['nama_kelas_232410']); ?></p>
    <p><strong>Status:</strong> <?= htmlspecialchars($data['status_232410']); ?></p>
    <p><strong>Akun Dibuat:</strong> <?= htmlspecialchars($data['created_at_232410']); ?></p>

    <hr>

    <h5 class="fw-bold text-center mb-3">QR Code Absensi Anda</h5>
    <div class="text-center">
      <?php if (!empty($data['qr_code_232410'])): ?>
          <img src="admin/qr_images/<?= htmlspecialchars($data['qr_code_232410']); ?>" width="200">
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
