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

// QUERY RIWAYAT ABSEN LENGKAP + JADWAL (MAPEL & HARI)
$query = "
    SELECT 
        a.tanggal,
        a.waktu_scan,
        a.status_kehadiran,
        j.mata_pelajaran,
        j.hari
    FROM absensi a
    JOIN siswa s 
        ON a.id_siswa = s.id_siswa
    JOIN kelas k
        ON s.kelas = k.id_kelas
    LEFT JOIN jadwal j
        ON j.id_kelas = k.id_kelas
        AND a.waktu_scan BETWEEN j.jam_mulai AND j.jam_selesai
    WHERE a.id_siswa = ?
    ORDER BY a.tanggal DESC, a.waktu_scan DESC
";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div class="container mt-5">
  <h3 class="fw-bold mb-4">Riwayat Absensi Anda</h3>

  <table class="table table-hover bg-white rounded">
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

        $hari   = $row['hari'] ?: "-";
        $mapel  = $row['mata_pelajaran'] ?: "-";

        $tanggal = date("d-m-Y", strtotime($row['tanggal']));
        $waktu   = $row['waktu_scan'] ?: "-";
        $status  = $row['status_kehadiran'];

        if ($status == "Hadir") {
          $badge = "<span class='badge bg-success'>Hadir</span>";
          $ket   = "Tepat waktu";
        } elseif ($status == "Terlambat") {
          $badge = "<span class='badge bg-warning text-dark'>Terlambat</span>";
          $ket   = "Siswa datang terlambat";
        } else {
          $badge = "<span class='badge bg-danger'>Alfa</span>";
          $ket   = "Tidak hadir";
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
