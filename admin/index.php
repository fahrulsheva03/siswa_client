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
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa");
$data_total = mysqli_fetch_assoc($query_total);
$total_siswa = $data_total['total'];

// // Menghitung jumlah siswa Aktif
// $query_aktif = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa WHERE status_233410='Aktif'");
// $data_aktif = mysqli_fetch_assoc($query_aktif);
// $siswa_aktif = $data_aktif['total'];

// // Menghitung jumlah siswa Nonaktif
// $query_nonaktif = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa WHERE status_233410='Nonaktif'");
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
            <h5 class="mt-2 mb-0">Data Siswa Tes</h5>
            <small class="text-muted"><?= $total_siswa ?></small>
          </div>
        </div>

        <!-- Menghitung total semua siswa -->
        <?php
        $query_kelas = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kelas");
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
        $query_jadwal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM jadwal");
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
      a.tanggal,
      a.waktu_scan,
      a.status_kehadiran,
      s.nama_siswa,
      k.nama_kelas,
      j.mata_pelajaran,
      j.hari
    FROM absensi a
    JOIN siswa s
      ON a.id_siswa = s.id_siswa
    JOIN kelas k
      ON s.kelas = k.id_kelas
    JOIN jadwal j
      ON j.id_kelas = k.id_kelas
      AND a.waktu_scan BETWEEN j.jam_mulai AND j.jam_selesai
    ORDER BY a.id_absensi DESC
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
                    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                    <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                    <td><?= htmlspecialchars($row['hari']); ?></td>
                    <td><?= htmlspecialchars($row['mata_pelajaran']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal']); ?></td>
                    <td><?= htmlspecialchars($row['waktu_scan']); ?></td>

                    <td>
                      <?php if ($row['status_kehadiran'] == "Hadir"): ?>
                        <span class="badge bg-success">Hadir</span>
                      <?php elseif ($row['status_kehadiran'] == "Terlambat"): ?>
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