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
                    <i class="bi bi-calendar-week me-2 text-primary"></i>Data Jadwal Pelajaran
                </h3>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-person-circle me-2"></i>Admin
                </button>
            </div>

            <!-- Form Tambah Jadwal -->
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Form Tambah Jadwal
                </h5>

                <form action="function.php" method="post">
                    <div class="row mb-3">

                        <!-- KELAS -->
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            <select name="id_kelas" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php
                                $kelas = mysqli_query($koneksi, "SELECT * FROM kelas_232410 ORDER BY nama_kelas_232410 ASC");
                                while ($k = mysqli_fetch_assoc($kelas)) {
                                    echo "<option value='{$k['id_kelas_232410']}'>{$k['nama_kelas_232410']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- MATA PELAJARAN -->
                        <div class="col-md-4">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mapel" class="form-control" placeholder="Contoh: Matematika" required>
                        </div>

                        <!-- HARI -->
                        <div class="col-md-4">
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-select" required>
                                <option value="">-- Pilih Hari --</option>
                                <option>Senin</option>
                                <option>Selasa</option>
                                <option>Rabu</option>
                                <option>Kamis</option>
                                <option>Jumat</option>
                                <option>Sabtu</option>
                            </select>
                        </div>

                    </div>

                    <!-- Jam -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" name="tambah_jadwal" class="btn btn-custom">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Jadwal
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Jadwal -->
            <div class="card p-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-table me-2 text-primary"></i>Daftar Jadwal Pelajaran
                </h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Kelas</th>
                                <th>Mata Pelajaran</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            // Ambil semua jadwal + JOIN kelas
                            $query = mysqli_query($koneksi, "
                                SELECT 
                                    j.id_jadwal_232410,
                                    j.mata_pelajaran_232410,
                                    j.hari_232410,
                                    j.jam_mulai_232410,
                                    j.jam_selesai_232410,
                                    k.nama_kelas_232410
                                FROM jadwal_232410 AS j
                                JOIN kelas_232410 AS k 
                                  ON k.id_kelas_232410 = j.id_kelas_232410
                                ORDER BY 
                                    FIELD(hari_232410,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
                                    jam_mulai_232410 ASC
                            ");

                            $no = 1;

                            if (mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['nama_kelas_232410']); ?></td>
                                        <td><?= htmlspecialchars($row['mata_pelajaran_232410']); ?></td>
                                        <td class="text-center"><?= $row['hari_232410']; ?></td>
                                        <td class="text-center">
                                            <?= $row['jam_mulai_232410'] . " - " . $row['jam_selesai_232410']; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="edit/jadwal.php?id=<?= $row['id_jadwal_232410']; ?>"
                                                class="btn btn-warning btn-sm text-white">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="hapus/delete.php?tabel=jadwal_232410&id=<?= $row['id_jadwal_232410']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Hapus jadwal ini?')">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "
                                <tr>
                                    <td colspan='6' class='text-center'>Belum ada jadwal pelajaran.</td>
                                </tr>";
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