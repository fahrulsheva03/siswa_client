<!DOCTYPE html>
<html lang="id">

<?php 
require '../koneksi.php';
require '../layouts/header.php';

// Fungsi: Memuat data satu kelas tertentu ke dalam form sehingga admin dapat mengeditnya.
// Parameter input:
// - $_GET['id']: ID kelas yang akan dicari pada tabel kelas.
// Return value:
// - Tidak mengembalikan nilai; menampilkan form HTML yang hasil submit-nya dikirim ke admin/function.php.
// Contoh penggunaan:
// - Diakses dari tombol edit di halaman daftar kelas yang menyertakan parameter ?id=... di URL.
// Catatan penting:
// - Jika ID tidak valid atau data kelas tidak ditemukan, admin akan dikembalikan ke halaman daftar kelas dengan pesan.
// Ambil ID dari URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas='$id'");
  $data = mysqli_fetch_assoc($query);

  // Jika ID tidak ditemukan
  if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href='../kelas.php';
          </script>";
    exit;
  }
} else {
  echo "<script>
          alert('ID tidak ditemukan!');
          window.location.href='../kelas.php';
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
        <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Kelas</h5>
        
        <form action="../function.php" method="post">
          <!-- Hidden ID -->
          <input type="hidden" name="id" value="<?= $data['id_kelas']; ?>">

          <div class="row mb-3">
            <div class="col-md-4">
              <label class="form-label">Nama Kelas</label>
              <input type="text" name="nama" class="form-control" 
                     value="<?= htmlspecialchars($data['nama_kelas']); ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Latitude Sekolah</label>
              <input type="text" name="lat" class="form-control" 
                     value="<?= htmlspecialchars($data['latitude'] ?? '-5.1476'); ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Longitude Sekolah</label>
              <input type="text" name="lng" class="form-control" 
                     value="<?= htmlspecialchars($data['longitude'] ?? '119.4327'); ?>" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Maksimal Siswa</label>
              <input type="number" name="max_siswa" class="form-control" min="1"
                     value="<?= isset($data['max_siswa']) ? (int) $data['max_siswa'] : 0; ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Radius Absen (Meter)</label>
              <input type="number" name="radius" class="form-control" min="10"
                     value="<?= isset($data['radius_meter']) ? (int) $data['radius_meter'] : 100; ?>" required>
            </div>
          </div>

          <div class="text-end">
            <button type="submit" name="edit_kelas" class="btn btn-warning text-white">
              <i class="bi bi-pencil-square me-1"></i>Update Data
            </button>
            <a href="../kelas.php" class="btn btn-secondary ms-2">Kembali</a>
          </div>
        </form>
      </div>

    </div>
  </div>

</body>
</html>
