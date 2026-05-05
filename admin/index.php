<!DOCTYPE html>
<html lang="id">

<?php

require 'koneksi.php';
require 'layouts/header.php';

if (isset($_SESSION['login']) == false) {
  echo "<script>alert('Anda belum login!'); window.location.href='../login.php';</script>";
  exit();
}

$queryStat = mysqli_query($koneksi, "
  SELECT
    (SELECT COUNT(*) FROM siswa) AS total_siswa,
    (SELECT COUNT(*) FROM siswa WHERE COALESCE(status, 'Aktif') = 'Aktif') AS siswa_aktif,
    (SELECT COUNT(*) FROM siswa WHERE COALESCE(status, 'Aktif') = 'Nonaktif') AS siswa_nonaktif,
    (SELECT COUNT(*) FROM siswa WHERE qr_code IS NULL OR qr_code = '') AS belum_qr,
    (SELECT COUNT(*) FROM kelas) AS total_kelas,
    (SELECT COUNT(*) FROM jadwal) AS total_jadwal,
    (SELECT COALESCE(SUM(max_siswa), 0) FROM kelas) AS kapasitas_total,
    (
      SELECT COUNT(*)
      FROM (
        SELECT k.id_kelas
        FROM kelas k
        LEFT JOIN (
          SELECT COALESCE(id_kelas, kelas) AS kelas_id, COUNT(*) AS jumlah_siswa
          FROM siswa
          GROUP BY COALESCE(id_kelas, kelas)
        ) s ON s.kelas_id = k.id_kelas
        WHERE k.max_siswa > 0 AND COALESCE(s.jumlah_siswa, 0) >= k.max_siswa
      ) x
    ) AS kelas_penuh
");
$stat = mysqli_fetch_assoc($queryStat);

$total_siswa = (int) ($stat['total_siswa'] ?? 0);
$siswa_aktif = (int) ($stat['siswa_aktif'] ?? 0);
$siswa_nonaktif = (int) ($stat['siswa_nonaktif'] ?? 0);
$belum_qr = (int) ($stat['belum_qr'] ?? 0);
$total_kelas = (int) ($stat['total_kelas'] ?? 0);
$total_jadwal = (int) ($stat['total_jadwal'] ?? 0);
$kapasitas_total = (int) ($stat['kapasitas_total'] ?? 0);
$kelas_penuh = (int) ($stat['kelas_penuh'] ?? 0);

$kapasitas_terisi = $total_siswa;
$persen_kapasitas = $kapasitas_total > 0 ? round(($kapasitas_terisi / $kapasitas_total) * 100, 1) : 0;

$queryAbsensiHariIni = mysqli_query($koneksi, "
  SELECT
    COUNT(*) AS total_absen_hari_ini,
    SUM(CASE WHEN COALESCE(status_kehadiran, '') = 'Hadir' OR COALESCE(status, '') = 'hadir' THEN 1 ELSE 0 END) AS hadir_hari_ini,
    SUM(CASE WHEN COALESCE(status_kehadiran, '') = 'Terlambat' OR COALESCE(status, '') = 'terlambat' THEN 1 ELSE 0 END) AS terlambat_hari_ini,
    SUM(CASE WHEN COALESCE(status_kehadiran, '') = 'Alfa' OR COALESCE(status, '') = 'tidak_hadir' THEN 1 ELSE 0 END) AS alfa_hari_ini
  FROM absensi
  WHERE tanggal = CURDATE()
");
$absenHariIni = mysqli_fetch_assoc($queryAbsensiHariIni);
$total_absen_hari_ini = (int) ($absenHariIni['total_absen_hari_ini'] ?? 0);
$hadir_hari_ini = (int) ($absenHariIni['hadir_hari_ini'] ?? 0);
$terlambat_hari_ini = (int) ($absenHariIni['terlambat_hari_ini'] ?? 0);
$alfa_hari_ini = (int) ($absenHariIni['alfa_hari_ini'] ?? 0);

$queryKapasitasKelas = mysqli_query($koneksi, "
  SELECT
    k.nama_kelas,
    COALESCE(k.max_siswa, 0) AS max_siswa,
    COALESCE(s.jumlah_siswa, 0) AS jumlah_siswa
  FROM kelas k
  LEFT JOIN (
    SELECT COALESCE(id_kelas, kelas) AS kelas_id, COUNT(*) AS jumlah_siswa
    FROM siswa
    GROUP BY COALESCE(id_kelas, kelas)
  ) s ON s.kelas_id = k.id_kelas
  ORDER BY COALESCE(s.jumlah_siswa, 0) DESC, k.nama_kelas ASC
  LIMIT 6
");

$querySiswaTerbaru = mysqli_query($koneksi, "
  SELECT s.nama_siswa, COALESCE(s.nisn, s.nis, '-') AS nis_display, COALESCE(k.nama_kelas, '-') AS nama_kelas
  FROM siswa s
  LEFT JOIN kelas k ON k.id_kelas = COALESCE(s.id_kelas, s.kelas)
  ORDER BY s.id_siswa DESC
  LIMIT 5
");

$queryRiwayat = mysqli_query($koneksi, "
  SELECT 
    a.tanggal,
    a.waktu_scan,
    COALESCE(a.status_kehadiran,
      CASE 
        WHEN a.status = 'hadir' THEN 'Hadir'
        WHEN a.status = 'terlambat' THEN 'Terlambat'
        WHEN a.status = 'tidak_hadir' THEN 'Alfa'
        ELSE '-'
      END
    ) AS status_kehadiran,
    s.nama_siswa,
    k.nama_kelas,
    COALESCE(j.mata_pelajaran, '-') AS mata_pelajaran,
    COALESCE(j.hari, '-') AS hari
  FROM absensi a
  JOIN siswa s
    ON a.id_siswa = s.id_siswa
  LEFT JOIN kelas k
    ON COALESCE(s.id_kelas, s.kelas) = k.id_kelas
  LEFT JOIN jadwal j
    ON j.id_kelas = k.id_kelas
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
  ORDER BY a.id_absensi DESC
  LIMIT 10
");
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

      <div class="card p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
          <div>
            <h5 class="fw-bold mb-1">Ringkasan Operasional</h5>
            <small class="text-muted">Pantau kondisi siswa, kelas, dan absensi harian dalam satu tampilan.</small>
          </div>
          <div class="d-flex gap-2">
            <a href="siswa.php" class="btn btn-primary btn-sm"><i class="bi bi-person-plus me-1"></i>Tambah Siswa</a>
            <a href="kelas.php" class="btn btn-outline-primary btn-sm"><i class="bi bi-grid-3x3-gap me-1"></i>Kelola Kelas</a>
            <!-- <a href="jadwal.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-calendar-week me-1"></i>Kelola Jadwal</a> -->
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
          <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <small class="text-muted">Total Siswa</small>
                <h4 class="fw-bold mb-0"><?= $total_siswa; ?></h4>
              </div>
              <i class="bi bi-people-fill text-primary fs-3"></i>
            </div>
            <small class="text-muted mt-2">Aktif <?= $siswa_aktif; ?> · Nonaktif <?= $siswa_nonaktif; ?></small>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <small class="text-muted">Kelas & Jadwal</small>
                <h4 class="fw-bold mb-0"><?= $total_kelas; ?> / <?= $total_jadwal; ?></h4>
              </div>
              <i class="bi bi-journals text-primary fs-3"></i>
            </div>
            <small class="text-muted mt-2">Kelas penuh: <?= $kelas_penuh; ?></small>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <small class="text-muted">Absensi Hari Ini</small>
                <h4 class="fw-bold mb-0"><?= $total_absen_hari_ini; ?></h4>
              </div>
              <i class="bi bi-clipboard-check text-primary fs-3"></i>
            </div>
            <small class="text-muted mt-2">Hadir <?= $hadir_hari_ini; ?> · Terlambat <?= $terlambat_hari_ini; ?> · Alfa <?= $alfa_hari_ini; ?></small>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <small class="text-muted">Kelengkapan QR</small>
                <h4 class="fw-bold mb-0"><?= $belum_qr; ?></h4>
              </div>
              <i class="bi bi-qr-code-scan text-primary fs-3"></i>
            </div>
            <small class="text-muted mt-2">Siswa belum memiliki QR Code</small>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-lg-8">
          <div class="card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h5 class="fw-bold mb-0"><i class="bi bi-building-check me-2 text-primary"></i>Kapasitas Kelas</h5>
              <small class="text-muted">Terisi <?= $kapasitas_terisi; ?> dari <?= $kapasitas_total; ?> kapasitas</small>
            </div>
            <div class="progress mb-3" style="height: 10px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: <?= min($persen_kapasitas, 100); ?>%;" aria-valuenow="<?= $persen_kapasitas; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Kelas</th>
                    <th class="text-center">Terisi</th>
                    <th class="text-center">Kapasitas</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($queryKapasitasKelas && mysqli_num_rows($queryKapasitasKelas) > 0): ?>
                    <?php while ($kelas = mysqli_fetch_assoc($queryKapasitasKelas)): ?>
                      <?php
                      $terisi = (int) $kelas['jumlah_siswa'];
                      $maks = (int) $kelas['max_siswa'];
                      ?>
                      <tr>
                        <td><?= htmlspecialchars($kelas['nama_kelas']); ?></td>
                        <td class="text-center"><?= $terisi; ?></td>
                        <td class="text-center"><?= $maks; ?></td>
                        <td class="text-center">
                          <?php if ($maks > 0 && $terisi >= $maks): ?>
                            <span class="badge bg-danger">Penuh</span>
                          <?php elseif ($maks > 0 && $terisi >= ($maks - 2)): ?>
                            <span class="badge bg-warning text-dark">Hampir Penuh</span>
                          <?php else: ?>
                            <span class="badge bg-success">Tersedia</span>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center">Belum ada data kelas</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-person-badge me-2 text-primary"></i>Siswa Terbaru</h5>
            <div class="list-group list-group-flush">
              <?php if ($querySiswaTerbaru && mysqli_num_rows($querySiswaTerbaru) > 0): ?>
                <?php while ($siswa = mysqli_fetch_assoc($querySiswaTerbaru)): ?>
                  <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <div>
                      <div class="fw-semibold"><?= htmlspecialchars($siswa['nama_siswa']); ?></div>
                      <small class="text-muted">NIS: <?= htmlspecialchars($siswa['nis_display']); ?></small>
                    </div>
                    <span class="badge bg-light text-dark"><?= htmlspecialchars($siswa['nama_kelas']); ?></span>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>
                <div class="text-muted">Belum ada data siswa.</div>
              <?php endif; ?>
            </div>
            <div class="mt-3">
              <a href="siswa.php" class="btn btn-sm btn-outline-primary w-100">Lihat Semua Siswa</a>
            </div>
          </div>
        </div>
      </div>

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
              <?php if ($queryRiwayat && mysqli_num_rows($queryRiwayat) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($queryRiwayat)): ?>
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

    </div>
  </div>

</body>

</html>
