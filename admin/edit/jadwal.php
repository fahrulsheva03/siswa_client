<?php
require '../koneksi.php';
require '../layouts/header.php';

// Fungsi: Mengambil data satu jadwal pelajaran berdasarkan ID dan menampilkannya ke dalam form edit.
// Parameter input:
// - $_GET['id']: ID jadwal yang akan dicari di tabel jadwal.
// Return value:
// - Tidak mengembalikan nilai; menampilkan form HTML yang ketika disubmit dikirim ke admin/function.php.
// Contoh penggunaan:
// - Diakses dari tombol edit pada halaman daftar jadwal admin yang menyertakan parameter ?id=... di URL.
// Catatan penting:
// - Jika ID tidak ditemukan atau tidak valid, pengguna diarahkan kembali ke halaman daftar jadwal dengan pesan.
// Ambil ID jadwal
if (!isset($_GET['id'])) {
    echo "<script>alert('ID jadwal tidak ditemukan!'); window.location.href='../admin/jadwal.php';</script>";
    exit;
}

$id_jadwal = $_GET['id'];

// Ambil data jadwal berdasarkan ID
$q = mysqli_query($koneksi, "
    SELECT * FROM jadwal WHERE id_jadwal = '$id_jadwal'
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

                    <input type="hidden" name="id_jadwal" value="<?= $data['id_jadwal']; ?>">

                    <div class="row mb-3">

                        <!-- Kelas -->
                        <div class="col-md-3">
                            <label class="form-label">Kelas</label>
                            <select name="id_kelas" class="form-select" required>
                                <?php
                                $kelas = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");

                                while ($k = mysqli_fetch_assoc($kelas)) {
                                    $selected = ($k['id_kelas'] == $data['id_kelas']) ? "selected" : "";
                                    echo "<option value='{$k['id_kelas']}' $selected>
                                        {$k['nama_kelas']}
                                      </option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="col-md-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" name="mapel" class="form-control"
                                value="<?= htmlspecialchars($data['mata_pelajaran']); ?>" required>
                        </div>

                        <!-- Nama Guru -->
                        <div class="col-md-3">
                            <label class="form-label">Nama Guru</label>
                            <input type="text" name="nama_guru" class="form-control"
                                   value="<?= htmlspecialchars($data['nama_guru']); ?>" required>
                        </div>

                        <!-- Hari -->
                        <div class="col-md-3">
                            <label class="form-label">Hari</label>
                            <select name="hari" class="form-select" required>
                                <?php
                                $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                foreach ($hariList as $h) {
                                    $selected = ($h == $data['hari']) ? "selected" : "";
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
                                value="<?= $data['jam_mulai']; ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="jam_selesai" class="form-control"
                                value="<?= $data['jam_selesai']; ?>" required>
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
