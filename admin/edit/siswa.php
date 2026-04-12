<!DOCTYPE html>
<html lang="id">

<?php 
require '../koneksi.php';
require '../layouts/header.php';

// Fungsi: Mengambil data siswa berdasarkan ID dan menampilkannya dalam form untuk proses pengeditan.
// Parameter input:
// - $_GET['id']: ID siswa yang akan diedit dan diambil datanya dari tabel siswa.
// Return value:
// - Tidak mengembalikan nilai; menampilkan form HTML yang ketika disubmit akan dikirim ke admin/function.php.
// Contoh penggunaan:
// - Halaman ini dipanggil dari tombol edit pada daftar siswa yang menyertakan parameter ?id=... di URL.
// Catatan penting:
// - Jika ID tidak ada atau datanya tidak ditemukan, pengguna akan diarahkan kembali dengan pesan peringatan.
// Ambil ID dari URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa='$id'");
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
          <input type="hidden" name="id" value="<?= $data['id_siswa']; ?>">

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Nama Siswa</label>
              <input type="text" name="nama" class="form-control" 
                     value="<?= htmlspecialchars($data['nama_siswa']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">NIS</label>
              <input type="text" name="nisn" class="form-control" 
                     value="<?= htmlspecialchars($data['nisn']); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Password</label>
              <input type="text" name="password" class="form-control" 
                     value="<?= htmlspecialchars($data['password']); ?>" required>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Kelas</label>
              <select name="kelas" class="form-select" required>
                <option value="">Pilih kelas</option>
                <?php
                $kelasAktif = (int) ($data['id_kelas'] ?? $data['kelas'] ?? 0);
                $kelas = mysqli_query($koneksi, "
                  SELECT 
                    k.id_kelas,
                    k.nama_kelas,
                    COALESCE(k.max_siswa, 0) AS max_siswa,
                    COALESCE(s.jumlah_siswa, 0) AS jumlah_siswa
                  FROM kelas AS k
                  LEFT JOIN (
                    SELECT COALESCE(id_kelas, kelas) AS kelas_id, COUNT(*) AS jumlah_siswa
                    FROM siswa
                    GROUP BY COALESCE(id_kelas, kelas)
                  ) AS s ON s.kelas_id = k.id_kelas
                  ORDER BY k.id_kelas ASC
                ");
                while ($k = mysqli_fetch_assoc($kelas)) {
                  $idKelas = (int) $k['id_kelas'];
                  $maxSiswa = (int) $k['max_siswa'];
                  $jumlahSiswa = (int) $k['jumlah_siswa'];
                  $isCurrent = $idKelas === $kelasAktif;
                  $isFull = $maxSiswa > 0 && $jumlahSiswa >= $maxSiswa;
                  $selected = $isCurrent ? "selected" : "";
                  $disabled = ($isFull && !$isCurrent) ? "disabled" : "";
                  $fullAttr = $isFull ? "1" : "0";
                  $currentAttr = $isCurrent ? "1" : "0";
                  $label = $isFull && !$isCurrent
                    ? "{$k['nama_kelas']} (Penuh {$jumlahSiswa}/{$maxSiswa})"
                    : "{$k['nama_kelas']} ({$jumlahSiswa}/{$maxSiswa})";
                  echo "<option value='{$k['id_kelas']}' $selected $disabled data-full='{$fullAttr}' data-current='{$currentAttr}'>{$label}</option>";
                }
                ?>
              </select>
              <div id="kelasStatusInfo" class="mt-2"></div>
              <small class="text-muted">Kelas penuh tidak bisa dipilih, kecuali kelas siswa saat ini.</small>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option value="Aktif" <?= ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                <option value="Nonaktif" <?= ($data['status'] == 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
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

<script>
(function () {
  var kelasSelect = document.querySelector('select[name="kelas"]');
  var infoBox = document.getElementById('kelasStatusInfo');
  if (!kelasSelect || !infoBox) {
    return;
  }

  function renderInfo() {
    var selected = kelasSelect.options[kelasSelect.selectedIndex];
    if (!selected || !selected.value) {
      infoBox.innerHTML = '';
      return;
    }

    var isFull = selected.getAttribute('data-full') === '1';
    var isCurrent = selected.getAttribute('data-current') === '1';

    if (isFull && isCurrent) {
      infoBox.innerHTML = '<span class="badge bg-warning text-dark">Kelas saat ini sudah penuh, tetapi tetap bisa dipertahankan</span>';
      return;
    }

    if (isFull) {
      infoBox.innerHTML = '<span class="badge bg-danger">Kelas penuh</span>';
      return;
    }

    infoBox.innerHTML = '<span class="badge bg-success">Kelas tersedia</span>';
  }

  kelasSelect.addEventListener('change', renderInfo);
  renderInfo();
})();
</script>
</body>
</html>
