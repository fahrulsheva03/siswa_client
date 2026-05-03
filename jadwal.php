<?php
require 'koneksi.php';

if (!isset($_SESSION['login_siswa'])) {
  echo "<script>alert('Anda belum login!');
     window.location.href='login_siswa.php';</script>";
  exit;
}

include 'includes/header.php';
include 'includes/navbar.php';

// ===============================
// 1. Ambil ID siswa yg login
// ===============================
$id_siswa = $_SESSION['id_siswa'];


// ===============================
// 2. Ambil nama kelas siswa
// ===============================
$qKelas = mysqli_prepare($koneksi, "
    SELECT k.nama_kelas 
    FROM siswa AS s
    JOIN kelas AS k 
      ON s.kelas = k.id_kelas
    WHERE id_siswa = ?
");
mysqli_stmt_bind_param($qKelas, "i", $id_siswa);
mysqli_stmt_execute($qKelas);
mysqli_stmt_bind_result($qKelas, $nama_kelas);
mysqli_stmt_fetch($qKelas);
mysqli_stmt_close($qKelas);


$qJadwal = mysqli_prepare($koneksi, "
    SELECT 
        hari,
        jam_mulai,
        jam_selesai,
        mata_pelajaran,
        nama_guru
    FROM jadwal
    WHERE id_kelas = (SELECT id_kelas FROM kelas WHERE nama_kelas = ?)
    ORDER BY FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'),
             jam_mulai ASC
");
mysqli_stmt_bind_param($qJadwal, "s", $nama_kelas);
mysqli_stmt_execute($qJadwal);
$hasil = mysqli_stmt_get_result($qJadwal);

$filterHari = isset($_GET['hari']) ? $_GET['hari'] : '';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$mapHari = [
  1 => 'Senin',
  2 => 'Selasa',
  3 => 'Rabu',
  4 => 'Kamis',
  5 => 'Jumat',
  6 => 'Sabtu',
  7 => 'Minggu'
];
$hariSekarang = $mapHari[(int) date('N')];
$waktuSekarang = date('H:i:s');
?>

<div class="container mt-5">
  <h3 class="fw-bold mb-4">Jadwal Pelajaran Kelas <?= htmlspecialchars($nama_kelas) ?></h3>

  <form method="get" class="row g-2 mb-3">
    <div class="col-md-4">
      <select name="hari" class="form-select">
        <option value="">Semua hari</option>
        <?php
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        foreach ($hariList as $h) {
          $selected = ($filterHari === $h) ? 'selected' : '';
          echo "<option value=\"{$h}\" {$selected}>{$h}</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-md-5">
      <input type="text" name="q" class="form-control" placeholder="Cari mata pelajaran atau guru"
        value="<?= htmlspecialchars($search); ?>">
    </div>
    <div class="col-md-3 d-flex justify-content-end gap-2">
      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="jadwal.php" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <table class="table table-bordered table-hover table-striped bg-white">
    <thead class="table-dark">
      <tr>
        <th>Hari</th>
        <th>Kelas</th>
        <th>Jam</th>
        <th>Mata Pelajaran</th>
        <th>Guru</th>
        <th>Ruangan</th>
        <th>Status Jadwal</th>
      </tr>
    </thead>
    <tbody>

      <?php
      if (mysqli_num_rows($hasil) == 0) {
        echo "<tr><td colspan='7' class='text-center'>Belum ada jadwal untuk kelas ini.</td></tr>";
      }

      $adaDataTampil = false;

      while ($row = mysqli_fetch_assoc($hasil)) {
        $hari = $row['hari'];
        $jamMulai = $row['jam_mulai'];
        $jamSelesai = $row['jam_selesai'];
        $jam = $jamMulai . " - " . $jamSelesai;
        $mapel = $row['mata_pelajaran'];
        $guru = $row['nama_guru'] ?: "-";
        $kelas = $nama_kelas;
        $ruangan = 'Ruang ' . $nama_kelas;

        if ($filterHari !== '' && $hari !== $filterHari) {
          continue;
        }

        if ($search !== '') {
          $cari = mb_strtolower($search, 'UTF-8');
          $gabungan = mb_strtolower($mapel . ' ' . $guru . ' ' . $ruangan, 'UTF-8');
          if (mb_strpos($gabungan, $cari, 0, 'UTF-8') === false) {
            continue;
          }
        }

        $statusBadge = '';
        $rowClass = '';

        // Konversi ke timestamp untuk perbandingan yang lebih akurat
        $timeNow = strtotime($waktuSekarang);
        $timeStart = strtotime($jamMulai);
        $timeEnd = strtotime($jamSelesai);

        if ($hari === $hariSekarang && $timeNow >= $timeStart && $timeNow <= $timeEnd) {
          $statusBadge = "<span class='badge bg-success'>Berlangsung</span>";
          $rowClass = "table-success";
        } elseif ($hari === $hariSekarang && $timeNow < $timeStart) {
          $statusBadge = "<span class='badge bg-info text-dark'>Belum dimulai</span>";
        } elseif ($hari === $hariSekarang && $timeNow > $timeEnd) {
          $statusBadge = "<span class='badge bg-secondary'>Selesai</span>";
        } else {
          $statusBadge = "<span class='badge bg-light text-dark'>Hari lain</span>";
        }

        $adaDataTampil = true;

        echo "
            <tr class='{$rowClass}'>
              <td>{$hari}</td>
              <td>{$kelas}</td>
              <td>{$jam}</td>
              <td>{$mapel}</td>
              <td>{$guru}</td>
              <td>{$ruangan}</td>
              <td>{$statusBadge}</td>
            </tr>
          ";
      }

      if (mysqli_num_rows($hasil) > 0 && !$adaDataTampil) {
        echo "<tr><td colspan='7' class='text-center'>Tidak ada jadwal yang cocok dengan filter.</td></tr>";
      }
      ?>

    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>