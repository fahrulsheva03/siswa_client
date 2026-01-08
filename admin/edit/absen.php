<!DOCTYPE html>
<html lang="id">

<?php
require '../koneksi.php';
require '../layouts/header.php';

// Fungsi: Menyajikan form edit untuk satu data absensi tertentu sebelum dikirim kembali ke handler update.
// Parameter input:
// - $_GET['id']: ID absensi yang akan diedit dan datanya diambil dari database.
// Return value:
// - Tidak mengembalikan nilai; menampilkan form HTML dengan nilai awal yang bisa diubah lalu dikirim ke admin/function.php.
// Contoh penggunaan:
// - Dihubungkan dari tombol edit pada tabel absensi di admin/absen.php, yang menambahkan parameter ?id=... di URL.
// Catatan penting:
// - Jika ID tidak ditemukan, pengguna akan diarahkan kembali ke halaman utama absensi dengan pesan peringatan.
if (!isset($_GET['id'])) {
    echo "<script>
            alert('ID absensi tidak ditemukan!');
            window.location.href='../absen.php';
          </script>";
    exit;
}

$id = $_GET['id'];

$query = mysqli_query($koneksi, "
    SELECT 
        a.id_absensi_232410,
        a.tanggal_232410,
        a.waktu_scan_232410,
        a.status_kehadiran_232410,
        s.nama_siswa_232410,
        s.nisn_232410,
        s.kelas_232410,
        k.nama_kelas_232410
    FROM absensi_232410 AS a
    JOIN siswa_232410 AS s
      ON s.id_siswa_232410 = a.id_siswa_232410
    LEFT JOIN kelas_232410 AS k
      ON s.kelas_232410 = k.id_kelas_232410
    WHERE a.id_absensi_232410 = '$id'
");

if (mysqli_num_rows($query) == 0) {
    echo "<script>
            alert('Data absensi tidak ditemukan!');
            window.location.href='../absen.php';
          </script>";
    exit;
}

$data = mysqli_fetch_assoc($query);
?>

<body>

    <?php require '../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Absensi
                </h3>
                <a href="../absen.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-clipboard-check me-2 text-primary"></i>Form Edit Absensi
                </h5>

                <form action="../function.php" method="post">
                    <input type="hidden" name="id_absensi" value="<?= $data['id_absensi_232410']; ?>">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama_siswa_232410']); ?>" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NISN</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['nisn_232410']); ?>" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama_kelas_232410'] ?: $data['kelas_232410']); ?>" disabled>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                   value="<?= htmlspecialchars($data['tanggal_232410']); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Waktu Scan</label>
                            <input type="time" name="waktu_scan" class="form-control"
                                   value="<?= htmlspecialchars($data['waktu_scan_232410']); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Kehadiran</label>
                            <select name="status" class="form-select" required>
                                <?php
                                $statusList = ['Hadir', 'Terlambat', 'Alfa'];
                                foreach ($statusList as $s) {
                                    $selected = ($s == $data['status_kehadiran_232410']) ? "selected" : "";
                                    echo "<option value='{$s}' {$selected}>{$s}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" name="edit_absensi" class="btn btn-custom">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                        <a href="../absen.php" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

</body>
</html>

