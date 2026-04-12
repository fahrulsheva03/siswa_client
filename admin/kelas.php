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
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: X RPL" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Maksimal Siswa</label>
                            <input type="number" name="max_siswa" class="form-control" min="1" placeholder="Contoh: 36" required>
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
                                <th>Maksimal Siswa</th>
                                <th>Jumlah Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            // Ambil data dari database
                            $query = mysqli_query($koneksi, "
                                SELECT 
                                    k.id_kelas, 
                                    k.nama_kelas,
                                    COALESCE(k.max_siswa, 0) AS max_siswa,
                                    COALESCE(s.jumlah_siswa, 0) AS jumlah_siswa
                                FROM kelas AS k
                                LEFT JOIN (
                                    SELECT id_kelas, COUNT(*) AS jumlah_siswa
                                    FROM siswa
                                    WHERE id_kelas IS NOT NULL
                                    GROUP BY id_kelas
                                ) AS s ON s.id_kelas = k.id_kelas
                                ORDER BY k.id_kelas DESC
                            ");
                            $no = 1;
                            $nama_tabel = 'kelas';
                            if (mysqli_num_rows($query) > 0) {
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $id      = $data['id_kelas'];
                                    $nama    = htmlspecialchars($data['nama_kelas']);
                                    $maxSiswa = (int) $data['max_siswa'];
                                    $jumlahSiswa = (int) $data['jumlah_siswa'];
                                    ?>

                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= $nama; ?></td>
                                        <td class="text-center"><?= $maxSiswa; ?></td>
                                        <td class="text-center">
                                            <?php if ($jumlahSiswa >= $maxSiswa): ?>
                                                <span class="badge bg-danger"><?= $jumlahSiswa; ?> / <?= $maxSiswa; ?></span>
                                            <?php elseif ($jumlahSiswa >= ($maxSiswa - 2)): ?>
                                                <span class="badge bg-warning text-dark"><?= $jumlahSiswa; ?> / <?= $maxSiswa; ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><?= $jumlahSiswa; ?> / <?= $maxSiswa; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn btn-info btn-sm text-white btn-detail-kelas"
                                                    data-target-id="detailKelas<?= $id; ?>"
                                                    aria-expanded="false">
                                                <i class="bi bi-eye"></i>
                                            </button>

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
                                    <tr class="bg-light detail-kelas-row" id="detailKelas<?= $id; ?>" style="display: none;">
                                        <td colspan="5">
                                            <div class="p-2">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span><strong>Detail Kelas <?= $nama; ?></strong></span>
                                                    <span><?= $jumlahSiswa; ?> / <?= $maxSiswa; ?> siswa</span>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm align-middle mb-0">
                                                        <thead class="table-light text-center">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Siswa</th>
                                                                <th>NIS</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $queryDetail = mysqli_query($koneksi, "SELECT nama_siswa, nis, nisn FROM siswa WHERE id_kelas = '$id' ORDER BY nama_siswa ASC");
                                                            $nomorDetail = 1;
                                                            if ($queryDetail && mysqli_num_rows($queryDetail) > 0) {
                                                                while ($siswa = mysqli_fetch_assoc($queryDetail)) {
                                                            ?>
                                                                    <tr>
                                                                        <td class="text-center"><?= $nomorDetail++; ?></td>
                                                                        <td><?= htmlspecialchars($siswa['nama_siswa']); ?></td>
                                                                        <td><?= htmlspecialchars($siswa['nis'] ?? $siswa['nisn'] ?? '-'); ?></td>
                                                                    </tr>
                                                            <?php
                                                                }
                                                            } else {
                                                                echo '<tr><td colspan="3" class="text-center">Belum ada siswa di kelas ini.</td></tr>';
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php
                                }
                            } else {
                                echo '
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data kelas.</td>
                                </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

<script>
document.querySelectorAll('.btn-detail-kelas').forEach(function (button) {
    button.addEventListener('click', function () {
        var targetId = this.getAttribute('data-target-id');
        var targetRow = document.getElementById(targetId);
        if (!targetRow) {
            return;
        }

        var isOpen = targetRow.style.display !== 'none';
        document.querySelectorAll('.detail-kelas-row').forEach(function (row) {
            row.style.display = 'none';
        });
        document.querySelectorAll('.btn-detail-kelas').forEach(function (btn) {
            btn.setAttribute('aria-expanded', 'false');
        });

        if (!isOpen) {
            targetRow.style.display = 'table-row';
            this.setAttribute('aria-expanded', 'true');
        }
    });
});
</script>
</body>
</html>
