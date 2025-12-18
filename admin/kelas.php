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
    <?php require 'layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">

            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="bi bi-collection me-2 text-primary"></i>Data Kelas
                </h3>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-person-circle me-2"></i>Admin
                </button>
            </div>

            <!-- Form Tambah Kelas -->
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Form Tambah Kelas
                </h5>

                <form action="function.php" method="post">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Kelas</label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: X RPL">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Wali Kelas</label>
                            <input type="text" name="wali" class="form-control" placeholder="Nama wali kelas">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" name="tambah_kelas" class="btn btn-custom">
                            <i class="bi bi-plus-circle me-1"></i>Tambah
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Kelas -->
            <div class="card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-table me-2 text-primary"></i>Daftar Kelas
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            // Ambil data dari database
                            $query = mysqli_query($koneksi, "SELECT * FROM kelas_232410 ORDER BY id_kelas_232410 DESC");
                            $no = 1;
                            $nama_tabel = 'kelas_232410';
                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $id      = $data['id_kelas_232410'];
                                    $nama    = htmlspecialchars($data['nama_kelas_232410']);
                                    $wali    = htmlspecialchars($data['wali_kelas_232410']);
                                    ?>

                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $nama; ?></td>
                                        <td><?= $wali; ?></td>
                                        <td class="text-center">
                                            <a href="edit/kelas.php?id=<?= $id; ?>" 
                                               class="btn btn-warning btn-sm text-white">
                                               <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <a href="hapus/delete.php?tabel=<?= $nama_tabel ?>&id=<?= $id ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus kelas ini?')">
                                               <i class="bi bi-trash3"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <?php
                                }
                            } else {
                                echo '
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data kelas.</td>
                                </tr>';
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
