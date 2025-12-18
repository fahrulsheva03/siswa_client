<?php
require 'koneksi.php';

require '../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;


// Cek apakah tombol tambah diklik
if (isset($_POST['tambah_siswa'])) {

  // Ambil data dari form
  $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $nisn   = mysqli_real_escape_string($koneksi, $_POST['nisn']);
  $kelas  = mysqli_real_escape_string($koneksi, $_POST['kelas']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);
  $status = mysqli_real_escape_string($koneksi, $_POST['status']);

  // Validasi sederhana
  if (empty($nama) || empty($nisn) || $kelas == "Pilih kelas") {
    echo "<script>
                alert('Semua field harus diisi dengan benar!');
                window.history.back();
              </script>";
    exit;
  }

  // ===============================
  // 1. Tambah siswa dulu
  // ===============================
  $query = "INSERT INTO siswa_232410 
            (nama_siswa_232410, nisn_232410, kelas_232410, password_232410, status_232410) 
             VALUES ('$nama', '$nisn', '$kelas', '$password', '$status')";

  if (mysqli_query($koneksi, $query)) {

    // 2. Ambil ID siswa
    $siswa_id = mysqli_insert_id($koneksi);

    // 3. Generate kode QR
    $kode_qr = "ABSEN-" . $siswa_id . "-" . time();

    // 4. Buat folder jika belum ada
    $qrFolder = __DIR__ . "/qr_images/";
    if (!is_dir($qrFolder)) {
      mkdir($qrFolder, 0777, true);
    }

    $qrFilename = "qr_" . $kode_qr . ".png";
    $qrPath = $qrFolder . $qrFilename;

    // 5. Generate QR Code PNG
    $result = (new Builder())->build(
      writer: new PngWriter(),
      data: $kode_qr,
      size: 300,
      margin: 10
    );

    $result->saveToFile($qrPath);

    // 6. Update siswa dengan qr_code
    $updateQR = "UPDATE siswa_232410 
                     SET qr_code_232410 = '$qrFilename' 
                     WHERE id_siswa_232410 = '$siswa_id'";

    mysqli_query($koneksi, $updateQR);

    echo "<script>
                alert('Data siswa + QR berhasil dibuat!');
                window.location.href='siswa.php';
              </script>";
  } else {

    echo "<script>
                alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
  }
}

// Edit data siswa
if (isset($_POST['edit_siswa'])) {
  $id     = $_POST['id'];
  $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $nisn   = mysqli_real_escape_string($koneksi, $_POST['nisn']);
  $kelas  = mysqli_real_escape_string($koneksi, $_POST['kelas']);
  $status = mysqli_real_escape_string($koneksi, $_POST['status']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);

  $update = mysqli_query($koneksi, "UPDATE siswa_232410 
    SET nama_siswa_232410='$nama', 
        nisn_232410='$nisn', 
        kelas_232410='$kelas', 
        status_232410='$status',
        password_232410='$password' 
    WHERE id_siswa_232410='$id'");

  if ($update) {
    echo "<script>
                alert('Data siswa berhasil diperbarui!');
                window.location.href='siswa.php';
              </script>";
  } else {
    echo "<script>
                alert('Gagal memperbarui data!');
                window.history.back();
              </script>";
  }
}

// Tambah data kelas
if (isset($_POST['tambah_kelas'])) {
  $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $wali   = mysqli_real_escape_string($koneksi, $_POST['wali']);

  $query = "INSERT INTO kelas_232410 
            (nama_kelas_232410, wali_kelas_232410) 
             VALUES ('$nama', '$wali')";

  if (mysqli_query($koneksi, $query)) {
    echo "<script>
                alert('Data kelas berhasil ditambahkan!');
                window.location.href='kelas.php';
              </script>";
  } else {
    echo "<script>
                alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
  }
}


// Edit data kelas
if (isset($_POST['edit_kelas'])) {
  $id     = $_POST['id'];
  $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $wali   = mysqli_real_escape_string($koneksi, $_POST['wali']);

  $update = mysqli_query($koneksi, "UPDATE kelas_232410 
    SET nama_kelas_232410='$nama', 
        wali_kelas_232410='$wali' 
    WHERE id_kelas_232410='$id'");

  if ($update) {
    echo "<script>
                alert('Data kelas berhasil diperbarui!');
                window.location.href='kelas.php';
              </script>";
  } else {
    echo "<script>
                alert('Gagal memperbarui data!');
                window.history.back();
              </script>";
  }
}

// =============== TAMBAH JADWAL ==================
if (isset($_POST['tambah_jadwal'])) {
    $id_kelas    = $_POST['id_kelas'];
    $mapel       = $_POST['mapel'];
    $hari        = $_POST['hari'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // ----------------------------
    // VALIDASI JAM
    // ----------------------------
    if (strtotime($jam_mulai) >= strtotime($jam_selesai)) {
        echo "<script>
                alert('Jam mulai harus lebih kecil dari jam selesai!');
                window.location.href='jadwal.php';
              </script>";
        exit;
    }

    // ----------------------------
    // CEK DUPLIKASI / TABRAKAN JADWAL
    // ----------------------------
    /*
        Kriteria bentrok:
        --------------------------------------------
        (jam_mulai_baru < jam_selesai_lama)
        AND
        (jam_selesai_baru > jam_mulai_lama)
        AND
        (kelas sama)
        AND
        (hari sama)
    */

    $cek = mysqli_query($koneksi, "
        SELECT * 
        FROM jadwal_232410
        WHERE id_kelas_232410 = '$id_kelas'
          AND hari_232410 = '$hari'
          AND (
                ( '$jam_mulai' < jam_selesai_232410 ) 
            AND ( '$jam_selesai' > jam_mulai_232410 )
          )
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Jadwal bentrok! Kelas ini sudah memiliki jadwal di jam tersebut.');
                window.location.href='jadwal.php';
              </script>";
        exit;
    }

    // ----------------------------
    // INSERT JADWAL JIKA VALID
    // ----------------------------
    $query = "
        INSERT INTO jadwal_232410 
        (id_kelas_232410, mata_pelajaran_232410, hari_232410, jam_mulai_232410, jam_selesai_232410)
        VALUES ('$id_kelas', '$mapel', '$hari', '$jam_mulai', '$jam_selesai')
    ";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Jadwal berhasil ditambahkan!');
                window.location.href='jadwal.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan jadwal: " . mysqli_error($koneksi) . "');
                window.location.href='jadwal.php';
              </script>";
    }

    exit;
}

// =============== EDIT JADWAL ==================
if (isset($_POST['edit_jadwal'])) {

    $id_jadwal   = $_POST['id_jadwal'];
    $id_kelas    = $_POST['id_kelas'];
    $mapel       = $_POST['mapel'];
    $hari        = $_POST['hari'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // --- VALIDASI JAM ---
    if (strtotime($jam_mulai) >= strtotime($jam_selesai)) {
        echo "<script>
                alert('Jam mulai harus lebih kecil dari jam selesai!');
                window.location.href='edit/jadwal.php?id=$id_jadwal';
              </script>";
        exit;
    }

    // --- CEK DUPLIKASI / BENTROK ---
    $cek = mysqli_query($koneksi, "
        SELECT * 
        FROM jadwal_232410
        WHERE id_kelas_232410 = '$id_kelas'
          AND hari_232410 = '$hari'
          AND id_jadwal_232410 != '$id_jadwal'
          AND (
                ('$jam_mulai' < jam_selesai_232410)
            AND ('$jam_selesai' > jam_mulai_232410)
          )
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Jadwal bentrok! Silahkan cek jam kelas lain.');
                window.location.href='edit/jadwal.php?id=$id_jadwal';
              </script>";
        exit;
    }

    // --- UPDATE JADWAL ---
    mysqli_query($koneksi, "
        UPDATE jadwal_232410 SET 
            id_kelas_232410 = '$id_kelas',
            mata_pelajaran_232410 = '$mapel',
            hari_232410 = '$hari',
            jam_mulai_232410 = '$jam_mulai',
            jam_selesai_232410 = '$jam_selesai'
        WHERE id_jadwal_232410 = '$id_jadwal'
    ");

    echo "<script>
            alert('Jadwal berhasil diperbarui!');
            window.location.href='jadwal.php';
          </script>";
    exit;
}

