<?php
require 'koneksi.php';

// Fungsi: Menampilkan riwayat lengkap absensi siswa yang sedang login beserta jadwal terkait.
// Parameter input:
// - $_SESSION['login_siswa']: status login siswa yang menjadi syarat akses halaman.
// - $_SESSION['id_siswa']: ID siswa yang digunakan dalam query riwayat absensi.
// Return value:
// - Tidak mengembalikan nilai; menghasilkan tabel HTML berisi daftar absensi dengan tanggal, hari, mapel, waktu, status, dan keterangan.
// Contoh penggunaan:
// - Diakses melalui menu Riwayat setelah siswa login ke sistem.
// Catatan penting:
// - Menggunakan LEFT JOIN ke jadwal sehingga tetap menampilkan riwayat meskipun jadwal sudah berubah atau tidak ditemukan.
if (!isset($_SESSION['login_siswa'])) {
  echo "<script>alert('Anda belum login!');
     window.location.href='login_siswa.php';</script>";
  exit;
}

include 'includes/header.php';
include 'includes/navbar.php';

// ID siswa yang login
$id_siswa = $_SESSION['id_siswa'];

// Ambil filter dari URL
$tgl_mulai = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';
$search = $_GET['search'] ?? '';

// Query Statistik (Total Kehadiran)
$query_stats = "
    SELECT 
        COUNT(CASE WHEN status = 'hadir' THEN 1 END) as total_hadir,
        COUNT(CASE WHEN status = 'terlambat' THEN 1 END) as total_terlambat,
        COUNT(CASE WHEN status = 'tidak_hadir' THEN 1 END) as total_alfa,
        COUNT(*) as total_absen
    FROM absensi 
    WHERE id_siswa = ?
";
$stmt_stats = mysqli_prepare($koneksi, $query_stats);
mysqli_stmt_bind_param($stmt_stats, "i", $id_siswa);
mysqli_stmt_execute($stmt_stats);
$res_stats = mysqli_stmt_get_result($stmt_stats);
$stats = mysqli_fetch_assoc($res_stats);

// QUERY RIWAYAT ABSEN LENGKAP + JADWAL (MAPEL & HARI) dengan Filter
$query = "
    SELECT 
        a.tanggal,
        a.waktu_scan,
        a.status,
        j.mata_pelajaran,
        j.hari
    FROM absensi a
    JOIN siswa s 
        ON a.id_siswa = s.id_siswa
    JOIN kelas k
        ON s.id_kelas = k.id_kelas
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
    WHERE a.id_siswa = ?
";

// Tambahkan kondisi filter jika ada
if (!empty($tgl_mulai)) {
  $query .= " AND a.tanggal >= '$tgl_mulai'";
}
if (!empty($tgl_selesai)) {
  $query .= " AND a.tanggal <= '$tgl_selesai'";
}
if (!empty($search)) {
  $query .= " AND j.mata_pelajaran LIKE '%$search%'";
}

$query .= " ORDER BY a.tanggal DESC, a.waktu_scan DESC";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Riwayat Absensi Anda</h3>
    <a href="riwayat.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-clockwise me-1"></i>Refresh</a>
  </div>

  <!-- Ringkasan Statistik -->
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card border-0 shadow-sm bg-primary text-white p-3">
        <small class="opacity-75">Total Absensi</small>
        <h3 class="fw-bold mb-0"><?= $stats['total_absen'] ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm bg-success text-white p-3">
        <small class="opacity-75">Hadir</small>
        <h3 class="fw-bold mb-0"><?= $stats['total_hadir'] ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm bg-warning text-dark p-3">
        <small class="opacity-75">Terlambat</small>
        <h3 class="fw-bold mb-0"><?= $stats['total_terlambat'] ?></h3>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card border-0 shadow-sm bg-danger text-white p-3">
        <small class="opacity-75">Alfa</small>
        <h3 class="fw-bold mb-0"><?= $stats['total_alfa'] ?></h3>
      </div>
    </div>
  </div>

  <!-- Filter & Pencarian -->
  <div class="card border-0 shadow-sm p-4 mb-4">
    <form method="GET" action="" class="row g-3">
      <div class="col-md-3">
        <label class="form-label small fw-bold">Tanggal Mulai</label>
        <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label small fw-bold">Tanggal Selesai</label>
        <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label small fw-bold">Cari Mata Pelajaran</label>
        <input type="text" name="search" class="form-control" placeholder="Ketik nama mapel..." value="<?= $search ?>">
      </div>
      <div class="col-md-2 d-flex align-items-end gap-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
        <a href="riwayat.php" class="btn btn-light border w-100">Reset</a>
      </div>
    </form>
  </div>

  <div class="table-responsive shadow-sm rounded">
    <table class="table table-hover bg-white m-0">
    <thead class="table-dark">
      <tr>
        <th>Tanggal</th>
        <th>Hari</th>
        <th>Mata Pelajaran</th>
        <th>Waktu</th>
        <th>Status</th>
        <th>Keterangan</th>
      </tr>
    </thead>
    <tbody>

      <?php
      if (mysqli_num_rows($result) == 0) {
        echo "<tr><td colspan='6' class='text-center'>Belum ada riwayat absensi.</td></tr>";
      }

      while ($row = mysqli_fetch_assoc($result)) {

        $hari = $row['hari'] ?: "-";
        $mapel = $row['mata_pelajaran'] ?: "-";

        $tanggal = date("d-m-Y", strtotime($row['tanggal']));
        $waktu = $row['waktu_scan'] ?: "-";
        $status = strtolower($row['status']);

        if ($status == "hadir") {
          $badge = "<span class='badge bg-success'>Hadir</span>";
          $ket = "Tepat waktu";
        } elseif ($status == "terlambat") {
          $badge = "<span class='badge bg-warning text-dark'>Terlambat</span>";
          $ket = "Siswa datang terlambat";
        } else {
          $badge = "<span class='badge bg-danger'>Alfa</span>";
          $ket = "Tidak hadir";
        }

        echo "
            <tr>
            <td>{$tanggal}</td>
              <td>{$hari}</td>
              <td>{$mapel}</td>
              <td>{$waktu}</td>
              <td>{$badge}</td>
              <td>{$ket}</td>
            </tr>
          ";
      }
      ?>

    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>