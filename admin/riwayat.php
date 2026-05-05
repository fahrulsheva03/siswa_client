<!DOCTYPE html>
<html lang="id">

<?php
require 'koneksi.php';
require 'layouts/header.php';

if (isset($_SESSION['login']) == false) {
    echo "<script>alert('Anda belum login!'); window.location.href='../login.php';</script>";
    exit();
}

// Ambil filter dari URL
$id_kelas = $_GET['id_kelas'] ?? '';
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$tanggal = $_GET['tanggal'] ?? '';

// Query Statistik Hari Ini
$query_stats = mysqli_query($koneksi, "
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN status = 'hadir' THEN 1 END) as hadir,
        COUNT(CASE WHEN status = 'terlambat' THEN 1 END) as terlambat,
        COUNT(CASE WHEN status = 'tidak_hadir' THEN 1 END) as alfa
    FROM absensi 
    WHERE tanggal = CURDATE()
");
$stats = mysqli_fetch_assoc($query_stats);
?>

<body>

    <!-- Sidebar -->
    <?php require 'layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">

            <!-- Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold m-0">
                    <i class="bi bi-collection me-2 text-primary"></i>Laporan Absensi
                </h3>
                <div class="d-flex gap-2">
                    <a href="riwayat.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i></a>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-person-circle me-2"></i>Admin
                    </button>
                </div>
            </div>

            <!-- Ringkasan Hari Ini -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3 border-start border-primary border-4">
                        <small class="text-muted fw-bold">Absensi Hari Ini</small>
                        <h4 class="fw-bold mb-0"><?= $stats['total'] ?></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3 border-start border-success border-4">
                        <small class="text-muted fw-bold">Hadir</small>
                        <h4 class="fw-bold mb-0"><?= $stats['hadir'] ?></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3 border-start border-warning border-4">
                        <small class="text-muted fw-bold">Terlambat</small>
                        <h4 class="fw-bold mb-0"><?= $stats['terlambat'] ?></h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm p-3 border-start border-danger border-4">
                        <small class="text-muted fw-bold">Alfa</small>
                        <h4 class="fw-bold mb-0"><?= $stats['alfa'] ?></h4>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="card border-0 shadow-sm p-4 mb-4">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="<?= $tanggal ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Kelas</label>
                        <select name="id_kelas" class="form-select">
                            <option value="">Semua Kelas</option>
                            <?php
                            $kelas_q = mysqli_query($koneksi, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                            while ($k = mysqli_fetch_assoc($kelas_q)) {
                                $sel = ($id_kelas == $k['id_kelas']) ? 'selected' : '';
                                echo "<option value='{$k['id_kelas']}' $sel>{$k['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="hadir" <?= ($status_filter == 'hadir') ? 'selected' : '' ?>>Hadir</option>
                            <option value="terlambat" <?= ($status_filter == 'terlambat') ? 'selected' : '' ?>>Terlambat</option>
                            <option value="tidak_hadir" <?= ($status_filter == 'tidak_hadir') ? 'selected' : '' ?>>Alfa</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Cari Nama Siswa</label>
                        <input type="text" name="search" class="form-control" placeholder="Ketik nama siswa..." value="<?= $search ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                        <a href="riwayat.php" class="btn btn-light border w-100">Reset</a>
                    </div>
                </form>
            </div>

            <div class="card border-0 shadow-sm p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle m-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Waktu Scan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $where = "WHERE 1=1";
                            if (!empty($tanggal)) $where .= " AND a.tanggal = '$tanggal'";
                            if (!empty($id_kelas)) $where .= " AND s.id_kelas = '$id_kelas'";
                            if (!empty($status_filter)) $where .= " AND a.status = '$status_filter'";
                            if (!empty($search)) $where .= " AND s.nama_siswa LIKE '%$search%'";

                            $query = "
                                SELECT 
                                    a.tanggal,
                                    a.waktu_scan,
                                    a.status,
                                    s.nama_siswa,
                                    k.nama_kelas,
                                    j.mata_pelajaran,
                                    j.hari
                                FROM absensi a
                                JOIN siswa s ON a.id_siswa = s.id_siswa
                                JOIN kelas k ON s.id_kelas = k.id_kelas
                                LEFT JOIN jadwal j ON j.id_kelas = k.id_kelas
                                    AND TIME(a.waktu_scan) BETWEEN j.jam_mulai AND j.jam_selesai
                                    AND j.hari = (
                                        CASE DAYOFWEEK(a.tanggal)
                                            WHEN 1 THEN 'Minggu'
                                            WHEN 2 THEN 'Senin'
                                            WHEN 3 THEN 'Selasa'
                                            WHEN 4 THEN 'Rabu'
                                            WHEN 5 THEN 'Kamis'
                                            WHEN 6 THEN 'Jumat'
                                            WHEN 7 THEN 'Sabtu'
                                        END
                                    )
                                $where
                                ORDER BY a.tanggal DESC, a.waktu_scan DESC
                            ";
                            $result = mysqli_query($koneksi, $query);
                            $no = 1;

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $status = strtolower($row['status']);
                                    if ($status == "hadir") {
                                        $badge = "<span class='badge bg-success'>Hadir</span>";
                                    } elseif ($status == "terlambat") {
                                        $badge = "<span class='badge bg-warning text-dark'>Terlambat</span>";
                                    } else {
                                        $badge = "<span class='badge bg-danger'>Alfa</span>";
                                    }

                                    echo "<tr>
                                        <td class='text-center'>{$no}</td>
                                        <td>{$row['nama_siswa']}</td>
                                        <td class='text-center'>{$row['nama_kelas']}</td>
                                        <td class='text-center'>" . date("d-m-Y", strtotime($row['tanggal'])) . "</td>
                                        <td class='text-center'>" . ($row['hari'] ?: "-") . "</td>
                                        <td>" . ($row['mata_pelajaran'] ?: "-") . "</td>
                                        <td class='text-center'>{$row['waktu_scan']}</td>
                                        <td class='text-center'>{$badge}</td>
                                    </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>Belum ada riwayat absensi.</td></tr>";
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
