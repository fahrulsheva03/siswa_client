<!DOCTYPE html>
<html lang="id">

<?php

require 'koneksi.php';
require 'layouts/header.php';

if (isset($_SESSION['login']) == false) {
  echo "<script>alert('Anda belum login!'); window.location.href='../login.php';</script>";
  exit();
}

// Menghitung total semua siswa
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa_232410");
$data_total = mysqli_fetch_assoc($query_total);
$total_siswa = $data_total['total'];

// // Menghitung jumlah siswa Aktif
// $query_aktif = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa_232410 WHERE status_233410='Aktif'");
// $data_aktif = mysqli_fetch_assoc($query_aktif);
// $siswa_aktif = $data_aktif['total'];

// // Menghitung jumlah siswa Nonaktif
// $query_nonaktif = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa_232410 WHERE status_233410='Nonaktif'");
// $data_nonaktif = mysqli_fetch_assoc($query_nonaktif);
// $siswa_nonaktif = $data_nonaktif['total'];


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

      <!-- Cards -->
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card text-center p-3">
            <i class="bi bi-people-fill text-primary fs-1"></i>
            <h5 class="mt-2 mb-0">Data Siswa</h5>
            <small class="text-muted"><?= $total_siswa ?></small>
          </div>
        </div>

        <!-- Menghitung total semua siswa -->
        <?php
        $query_kelas = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelas_232410");
        $data_kelas = mysqli_fetch_assoc($query_kelas);
        $total_kelas = $data_kelas['total'];
        ?>

        <div class="col-md-4">
          <div class="card text-center p-3">
            <i class="bi bi-clipboard-check text-primary fs-1"></i>
            <h5 class="mt-2 mb-0">Data Kelas</h5>
            <small class="text-muted"><?= $total_kelas ?></small>
          </div>
        </div>


        <!-- Menghitung total semua jadwal -->
        <?php
        $query_jadwal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal_232410");
        $data_jadwal = mysqli_fetch_assoc($query_jadwal);
        $total_jadwal = $data_jadwal['total'];
        ?>

        <div class="col-md-4">
          <div class="card text-center p-3">
            <i class="bi bi-calendar-week text-primary fs-1"></i>
            <h5 class="mt-2 mb-0">Data Jadwal</h5>
            <small class="text-muted"><?= $total_jadwal ?></small>
          </div>
        </div>
      </div>

      <!-- =============================== -->
      <!-- RIWAYAT ABSENSI TERBARU -->
      <!-- =============================== -->

      <?php
      $query_riwayat = mysqli_query($koneksi, "
    SELECT 
      a.tanggal_232410,
      a.waktu_scan_232410,
      a.status_kehadiran_232410,
      s.nama_siswa_232410,
      k.nama_kelas_232410,
      j.mata_pelajaran_232410,
      j.hari_232410
    FROM absensi_232410 a
    JOIN siswa_232410 s
      ON a.id_siswa_232410 = s.id_siswa_232410
    JOIN kelas_232410 k
      ON s.kelas_232410 = k.id_kelas_232410
    JOIN jadwal_232410 j
      ON j.id_kelas_232410 = k.id_kelas_232410
      AND a.waktu_scan_232410 BETWEEN j.jam_mulai_232410 AND j.jam_selesai_232410
    ORDER BY a.id_absensi_232410 DESC
    LIMIT 10
");

      ?>

      <div class="card p-4 mt-4">
        <h5 class="fw-bold mb-3">
          <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Absensi Terbaru
        </h5>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
              <tr>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Mata Pelajaran</th>
                <th>Tanggal</th>
                <th>Jam Scan</th>
                <th>Status</th>
              </tr>
            </thead>

            <tbody>
              <?php if (mysqli_num_rows($query_riwayat) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($query_riwayat)): ?>

                  <tr>
                    <td><?= htmlspecialchars($row['nama_siswa_232410']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kelas_232410']); ?></td>
                    <td><?= htmlspecialchars($row['hari_232410']); ?></td>
                    <td><?= htmlspecialchars($row['mata_pelajaran_232410']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_232410']); ?></td>
                    <td><?= htmlspecialchars($row['waktu_scan_232410']); ?></td>

                    <td>
                      <?php if ($row['status_kehadiran_232410'] == "Hadir"): ?>
                        <span class="badge bg-success">Hadir</span>
                      <?php elseif ($row['status_kehadiran_232410'] == "Terlambat"): ?>
                        <span class="badge bg-warning text-dark">Terlambat</span>
                      <?php else: ?>
                        <span class="badge bg-danger">Alfa</span>
                      <?php endif; ?>
                    </td>
                  </tr>

                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="text-center">Belum ada data absensi</td>
                </tr>
              <?php endif; ?>
            </tbody>

          </table>
        </div>
      </div>


      <!-- Form Section -->

    </div>
  </div>

</body>

</html>