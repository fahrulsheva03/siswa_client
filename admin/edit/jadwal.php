<?php
require '../koneksi.php';
require '../layouts/header.php';

// Ambil ID jadwal
if (!isset($_GET['id'])) {
    echo "<script>alert('ID jadwal tidak ditemukan!'); window.location.href='../admin/jadwal.php';</script>";
    exit;
}

$id_jadwal = $_GET['id'];

// Ambil data jadwal berdasarkan ID
$q = mysqli_query($koneksi, "
    SELECT * FROM jadwal_232410 WHERE id_jadwal_232410 = '$id_jadwal'
");

if (mysqli_num_rows($q) == 0) {
    echo "<script>alert('Data jadwal tidak ditemukan!'); window.location.href='../admin/jadwal.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($q);
?>

<body>

    <!-- Sidebar -->
    <?php require '../layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">

            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Jadwal Pelajaran
                </h3>
                <a href="../admin/jadwal.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <!-- Form Edit Jadwal -->
            <div class="card p-4">
                <form action="../function.php" method="post">

                    <input type="hidden" name="id_jadwal" value="<?= $data['id_jadwal_232410']; ?>">

                    <div class="row mb-3">

                        <!-- Kelas -->
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            <select name="id_kelas" class="form-select" required>
                                <?php
                                $kelas = mysqli_query($koneksi, "SELECT * FROM kelas_232410 ORDER BY nama_kelas_232410 ASC");

                                while ($k = mysqli_fetch_assoc($kelas)) {
                                    $selected = ($k['id_kelas_232410'] == $data['id_kelas_232410']) ? "selected" : "";
                                    echo "<option value='{$k['id_kelas_232410']}' $selected>
                                        {$k['nama_kelas_232410']}
                                      </option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="col-md-4">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mapel" class="form-control"
                                value="<?= htmlspecialchars($data['mata_pelajaran_232410']); ?>" required>
                        </div>

                        <!-- Hari -->
                        <div class="col-md-4">
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-select" required>
                                <?php
                                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                foreach ($hariList as $h) {
                                    $selected = ($h == $data['hari_232410']) ? "selected" : "";
                                    echo "<option $selected>$h</option>";
                                }
                                ?>
                            </select>
                        </div>

                    </div>

                    <!-- Jam -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="jam_mulai" class="form-control"
                                value="<?= $data['jam_mulai_232410']; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control"
                                value="<?= $data['jam_selesai_232410']; ?>" required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" name="edit_jadwal" class="btn btn-custom">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</body>

</html>