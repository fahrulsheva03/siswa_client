<!DOCTYPE html>
<html lang="id">

<?php 

require 'koneksi.php';
require 'layouts/header.php';

if(isset($_SESSION['login']) == false){
    echo "<script>alert('Anda belum login!'); window.location.href='../login.php';</script>";
    exit();
}
?>

<body>
  <!-- Sidebar -->
  <?php 
  require 'layouts/sidebar.php';
  ?>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin</h3>
        <button class="btn btn-outline-primary"><i class="bi bi-person-circle me-2"></i>Admin</button>
      </div>

      <!-- Form Section -->
      <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-pencil-square me-2 text-primary"></i>Form Tambah Data Siswa</h5>
        <form action="function.php" method="post" >
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Nama Siswa</label>
              <input type="text" name="nama" class="form-control" placeholder="Masukkan nama siswa">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">NIS</label>
              <input type="text" class="form-control" name="nisn" placeholder="Nomor Induk Siswa">
            </div>
            <div class="col-md-12">
              <label class="form-label">Password</label>
              <input type="text" class="form-control" name="password" placeholder="Password">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Kelas</label>
              <select name="kelas" class="form-select">
                <option selected>Pilih kelas</option>
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY id_kelas ASC");
                while ($k = mysqli_fetch_assoc($query)) {
                  echo "<option value='{$k['id_kelas']}'>{$k['nama_kelas']}</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status</label>
              <select name="status" class="form-select">
                <option selected>Aktif</option>
                <option>Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="text-end">
            <button type="submit"  name="tambah_siswa" class="btn btn-custom me-2"><i class="bi bi-plus-circle me-1"></i>Tambah</button>
          </div>
        </form>
      </div>

      <!-- Data Table Section -->
      <div class="card p-4">
  <h5 class="fw-bold mb-3"><i class="bi bi-table me-2 text-primary"></i>Data Siswa</h5>
  <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
      <thead class="table-primary text-center">
        <tr>
          <th>No</th>
          <th>Nama Siswa</th>
          <th>NIS</th>
          <th>Password</th>
          <th>Kelas</th>
          <th>Status</th>
          <th>QR Code</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        

        // Ambil data dari tabel siswa + join kelas
        $query = mysqli_query($koneksi, "SELECT *, k.nama_kelas FROM siswa AS s JOIN kelas AS k ON s.kelas = k.id_kelas ORDER BY id_siswa DESC");
        $no = 1;

        // Periksa apakah ada data
        if (mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_assoc($query)) {
                // Ambil data per field
                $nama   = htmlspecialchars($data['nama_siswa']);
                $nisn   = htmlspecialchars($data['nisn']);
                $password = htmlspecialchars($data['password']);
                $kelas  = htmlspecialchars($data['nama_kelas']);
                $status = htmlspecialchars($data['status']);
        ?>
        <tr>
          <td class="text-center"><?= $no++; ?></td>
          <td><?= $nama; ?></td>
          <td><?= $nisn; ?></td>
          <td><?= $password; ?></td>
          <td><?= $kelas; ?></td>
          <td class="text-center">
            <?php if ($status == 'Aktif'): ?>
              <span class="badge bg-success">Aktif</span>
            <?php else: ?>
              <span class="badge bg-secondary">Nonaktif</span>
            <?php endif; ?>
          </td>
          <td class="text-center">
            <?php if ($data['qr_code'] != null): ?>
              <img src="qr_images/<?= $data['qr_code']; ?>" height="50" width="50"  alt="QR Code">
            <?php else: ?>
              <span class="badge bg-secondary">Tidak ada QR Code</span>
            <?php endif; ?>
          </td>
          <td class="text-center">
            <a href="edit/siswa.php?id=<?= $data['id_siswa']; ?>" class="btn btn-warning btn-sm text-white"><i class="bi bi-pencil-square"></i></a>
            <a href="hapus/delete.php?tabel=siswa&id=<?= $data['id_siswa']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
              <i class="bi bi-trash3"></i>
            </a>
          </td>
        </tr>
        <?php 
            }
        } else {
        ?>
        <tr>
          <td colspan="8" class="text-center">Belum ada data siswa</td>
        </tr>
        <?php 
        }

       
        ?>
      </tbody>
    </table>
  </div>
</div>


    </div>
  </div>

</body>
</html>
