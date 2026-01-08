<!DOCTYPE html>
<html lang="id">

<?php 
require '../koneksi.php';
require '../layouts/header.php';

// Fungsi: Mengambil data siswa berdasarkan ID dan menampilkannya dalam form untuk proses pengeditan.
// Parameter input:
// - $_GET['id']: ID siswa yang akan diedit dan diambil datanya dari tabel siswa_232410.
// Return value:
// - Tidak mengembalikan nilai; menampilkan form HTML yang ketika disubmit akan dikirim ke admin/function.php.
// Contoh penggunaan:
// - Halaman ini dipanggil dari tombol edit pada daftar siswa yang menyertakan parameter ?id=... di URL.
// Catatan penting:
// - Jika ID tidak ada atau datanya tidak ditemukan, pengguna akan diarahkan kembali dengan pesan peringatan.
// Ambil ID dari URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = mysqli_query($koneksi, "SELECT * FROM siswa_232410 WHERE id_siswa_232410='$id'");
  $data = mysqli_fetch_assoc($query);

  // Jika ID tidak ditemukan
  if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href='index.php';
          </script>";
    exit;
  }
} else {
  echo "<script>
          alert('ID tidak ditemukan!');
          window.location.href='index.php';
        </script>";
  exit;
}
?>

<body>
  <!-- Sidebar -->
  <?php require '../layouts/sidebar.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin</h3>
        <button class="btn btn-outline-primary"><i class="bi bi-person-circle me-2"></i>Admin</button>
      </div>

      <!-- Form Edit -->
      <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Siswa</h5>
        
        <form action="../function.php" method="post">
          <!-- Hidden ID -->
          <input type="hidden" name="id" value="<?= $data['id_siswa_232410']; ?>">

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Nama Siswa</label>
              <input type="text" name="nama" class="form-control" 
                     value="<?= htmlspecialchars($data['nama_siswa_232410']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">NIS</label>
              <input type="text" name="nisn" class="form-control" 
                     value="<?= htmlspecialchars($data['nisn_232410']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input type="text" name="password" class="form-control" 
                     value="<?= htmlspecialchars($data['password_232410']); ?>" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Kelas</label>
              <select name="kelas" class="form-select" required>
                <option value="">Pilih kelas</option>
                <?php
                $kelas = mysqli_query($koneksi, "SELECT * FROM kelas_232410 ORDER BY id_kelas_232410 ASC");
                while ($k = mysqli_fetch_assoc($kelas)) {
                  $selected = ($k['id_kelas_232410'] == $data['kelas_232410']) ? "selected" : "";
                  echo "<option value='{$k['id_kelas_232410']}' $selected>{$k['nama_kelas_232410']}</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="Aktif" <?= ($data['status_232410'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                <option value="Nonaktif" <?= ($data['status_232410'] == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
              </select>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" name="edit_siswa" class="btn btn-warning text-white">
              <i class="bi bi-pencil-square me-1"></i>Update Data
            </button>
            <a href="index.php" class="btn btn-secondary ms-2">Kembali</a>
          </div>
        </form>
      </div>

    </div>
  </div>

</body>
</html>
