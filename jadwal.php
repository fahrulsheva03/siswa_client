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
$id_siswa = $_SESSION['id_siswa_232410'];


// ===============================
// 2. Ambil nama kelas siswa
// ===============================
$qKelas = mysqli_prepare($koneksi, "
    SELECT k.nama_kelas_232410 
    FROM siswa_232410 AS s
    JOIN kelas_232410 AS k 
      ON s.kelas_232410 = k.id_kelas_232410
    WHERE id_siswa_232410 = ?
");
mysqli_stmt_bind_param($qKelas, "i", $id_siswa);
mysqli_stmt_execute($qKelas);
mysqli_stmt_bind_result($qKelas, $nama_kelas);
mysqli_stmt_fetch($qKelas);
mysqli_stmt_close($qKelas);


// ===============================
// 3. Ambil daftar jadwal berdasarkan nama kelas siswa
// ===============================
$qJadwal = mysqli_prepare($koneksi, "
    SELECT 
        hari_232410,
        jam_mulai_232410,
        jam_selesai_232410,
        mata_pelajaran_232410
    FROM jadwal_232410
    WHERE id_kelas_232410 = (SELECT id_kelas_232410 FROM kelas_232410 WHERE nama_kelas_232410 = ?)
    ORDER BY FIELD(hari_232410, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
             jam_mulai_232410 ASC
");
mysqli_stmt_bind_param($qJadwal, "s", $nama_kelas);
mysqli_stmt_execute($qJadwal);
$hasil = mysqli_stmt_get_result($qJadwal);
?>

<div class="container mt-5">
  <h3 class="fw-bold mb-4">Jadwal Pelajaran Kelas <?= htmlspecialchars($nama_kelas) ?></h3>

  <table class="table table-bordered bg-white">
    <thead class="table-dark">
      <tr>
        <th>Hari</th>
        <th>Jam</th>
        <th>Mata Pelajaran</th>
        <th>Guru</th>
      </tr>
    </thead>
    <tbody>

      <?php
      if (mysqli_num_rows($hasil) == 0) {
        echo "<tr><td colspan='4' class='text-center'>Belum ada jadwal untuk kelas ini.</td></tr>";
      }

      while ($row = mysqli_fetch_assoc($hasil)) {
        $hari   = $row['hari_232410'];
        $jam    = $row['jam_mulai_232410'] . " - " . $row['jam_selesai_232410'];
        $mapel  = $row['mata_pelajaran_232410'];

        // Jika guru tidak ada di database (karena tabelmu tidak punya guru), kita buat placeholder:
        $qGuru = mysqli_prepare($koneksi, "SELECT wali_kelas_232410 FROM kelas_232410 WHERE nama_kelas_232410 = ?");
        mysqli_stmt_bind_param($qGuru, "s", $nama_kelas);
        mysqli_stmt_execute($qGuru);
        mysqli_stmt_bind_result($qGuru, $nama_guru);
        mysqli_stmt_fetch($qGuru);
        mysqli_stmt_close($qGuru);
        $guru = $nama_guru ? $nama_guru : "-";

        echo "
            <tr>
              <td>{$hari}</td>
              <td>{$jam}</td>
              <td>{$mapel}</td>
              <td>{$guru}</td>
            </tr>
          ";
      }
      ?>

    </tbody>
  </table>
</div>

<?php include 'includes/footer.php'; ?>