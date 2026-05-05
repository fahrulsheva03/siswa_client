<?php
require 'koneksi.php';

require '../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;


// Fungsi: Menambahkan data siswa baru sekaligus membuat file QR Code unik untuk absensi.
// Parameter input:
// - $_POST['nama']: nama lengkap siswa yang akan didaftarkan.
// - $_POST['nisn']: nomor induk siswa nasional yang menjadi identitas login.
// - $_POST['kelas']: ID kelas tempat siswa terdaftar.
// - $_POST['password']: password siswa dalam bentuk teks biasa.
// - $_POST['status']: status keaktifan siswa (misalnya Aktif atau Nonaktif).
// Return value:
// - Tidak mengembalikan nilai; menyimpan data ke siswa, membuat file QR di qr_images, lalu redirect dengan pesan.
// Contoh penggunaan:
// - Dipicu ketika form tambah siswa di halaman admin/siswa.php disubmit dengan tombol name="tambah_siswa".
// Catatan penting:
// - QR Code disimpan sebagai file PNG/SVG (tergantung dukungan GD) dan nama filenya direferensikan di kolom qr_code.
// Cek apakah tombol tambah diklik
if (isset($_POST['tambah_siswa'])) {

  // Ambil data dari form
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
  $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);
  $status = mysqli_real_escape_string($koneksi, $_POST['status']);
  $kelasId = (int) $kelas;

  // Validasi sederhana
  if (empty($nama) || empty($nisn) || $kelasId < 1) {
    echo "<script>
                alert('Semua field harus diisi dengan benar!');
                window.history.back();
              </script>";
    exit;
  }

  $cekKelas = mysqli_query($koneksi, "SELECT COALESCE(max_siswa, 0) AS max_siswa FROM kelas WHERE id_kelas = '$kelasId' LIMIT 1");
  if (!$cekKelas || mysqli_num_rows($cekKelas) === 0) {
    echo "<script>
                alert('Kelas tidak valid!');
                window.history.back();
              </script>";
    exit;
  }

  $kelasData = mysqli_fetch_assoc($cekKelas);
  $maxSiswa = (int) $kelasData['max_siswa'];
  $cekJumlahSiswa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa WHERE id_kelas = '$kelasId'");
  $jumlahSiswa = (int) mysqli_fetch_assoc($cekJumlahSiswa)['total'];

  if ($maxSiswa > 0 && $jumlahSiswa >= $maxSiswa) {
    echo "<script>
                alert('Kelas sudah penuh! Pilih kelas lain.');
                window.history.back();
              </script>";
    exit;
  }

  // ===============================
  // 1. Tambah siswa dulu
  // ===============================
  $query = "INSERT INTO siswa 
            (nama_siswa, nis, nisn, id_kelas, kelas, password, status) 
             VALUES ('$nama', '$nisn', '$nisn', '$kelasId', '$kelasId', '$password', '$status')";

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

    $isGdEnabled = extension_loaded('gd');
    $writer = $isGdEnabled ? new PngWriter() : new SvgWriter();
    $fileExtension = $isGdEnabled ? 'png' : 'svg';
    $qrFilename = "qr_" . $kode_qr . "." . $fileExtension;
    $qrPath = $qrFolder . $qrFilename;

    // 5. Generate QR Code (fallback ke SVG jika GD belum aktif)
    $result = (new Builder())->build(
      writer: $writer,
      data: $kode_qr,
      size: 300,
      margin: 10
    );

    $result->saveToFile($qrPath);

    // 6. Update siswa dengan qr_code
    $updateQR = "UPDATE siswa 
                     SET qr_code = '$qrFilename' 
                     WHERE id_siswa = '$siswa_id'";

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

// Fungsi: Mengubah data identitas siswa yang sudah tersimpan di database.
// Parameter input:
// - $_POST['id']: ID siswa yang datanya akan diperbarui.
// - $_POST['nama']: nama lengkap siswa terbaru.
// - $_POST['nisn']: NISN terbaru apabila terjadi perubahan.
// - $_POST['kelas']: ID kelas terbaru tempat siswa terdaftar.
// - $_POST['status']: status keaktifan siswa (Aktif atau Nonaktif).
// - $_POST['password']: password siswa yang disimpan dalam bentuk teks biasa.
// Return value:
// - Tidak mengembalikan nilai; menjalankan UPDATE pada tabel siswa lalu redirect dengan pesan sukses atau gagal.
// Contoh penggunaan:
// - Dipicu dari form edit siswa di admin/edit/siswa.php yang disubmit dengan tombol name="edit_siswa".
// Catatan penting:
// - Perubahan password langsung menimpa nilai lama tanpa proses enkripsi maupun pencatatan riwayat.
// Edit data siswa
if (isset($_POST['edit_siswa'])) {
  $id = (int) $_POST['id'];
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
  $kelas = (int) $_POST['kelas'];
  $status = mysqli_real_escape_string($koneksi, $_POST['status']);
  $password = mysqli_real_escape_string($koneksi, $_POST['password']);

  if (empty($nama) || empty($nisn) || $kelas < 1 || $id < 1) {
    echo "<script>
                alert('Semua field harus diisi dengan benar!');
                window.history.back();
              </script>";
    exit;
  }

  $cekSiswa = mysqli_query($koneksi, "SELECT id_kelas FROM siswa WHERE id_siswa = '$id' LIMIT 1");
  if (!$cekSiswa || mysqli_num_rows($cekSiswa) === 0) {
    echo "<script>
                alert('Data siswa tidak ditemukan!');
                window.history.back();
              </script>";
    exit;
  }

  $siswaLama = mysqli_fetch_assoc($cekSiswa);
  $kelasLama = (int) ($siswaLama['id_kelas'] ?? 0);

  if ($kelas !== $kelasLama) {
    $cekKelas = mysqli_query($koneksi, "SELECT COALESCE(max_siswa, 0) AS max_siswa FROM kelas WHERE id_kelas = '$kelas' LIMIT 1");
    if (!$cekKelas || mysqli_num_rows($cekKelas) === 0) {
      echo "<script>
                  alert('Kelas tidak valid!');
                  window.history.back();
                </script>";
      exit;
    }

    $kelasData = mysqli_fetch_assoc($cekKelas);
    $maxSiswa = (int) $kelasData['max_siswa'];
    $cekJumlahSiswa = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa WHERE id_kelas = '$kelas'");
    $jumlahSiswa = (int) mysqli_fetch_assoc($cekJumlahSiswa)['total'];

    if ($maxSiswa > 0 && $jumlahSiswa >= $maxSiswa) {
      echo "<script>
                  alert('Kelas tujuan sudah penuh! Pilih kelas lain.');
                  window.history.back();
                </script>";
      exit;
    }
  }

  $update = mysqli_query($koneksi, "UPDATE siswa 
    SET nama_siswa='$nama', 
        nis='$nisn',
        nisn='$nisn', 
        id_kelas='$kelas',
        kelas='$kelas', 
        status='$status',
        password='$password' 
    WHERE id_siswa='$id'");

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

// Fungsi: Menambahkan data kelas baru ke dalam tabel kelas.
// Parameter input:
// - $_POST['nama']: nama kelas yang akan dibuat (misalnya X IPA 1).
// Return value:
// - Tidak mengembalikan nilai; menyisipkan baris baru ke kelas dan redirect ke halaman daftar kelas.
// Contoh penggunaan:
// - Dipicu saat admin mengisi form tambah kelas pada admin/kelas.php dengan tombol name="tambah_kelas".
// Catatan penting:
// - Validasi hanya memastikan nama kelas tidak kosong, belum ada pengecekan duplikasi nama.
// Tambah data kelas
if (isset($_POST['tambah_kelas'])) {
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $max_siswa = isset($_POST['max_siswa']) ? (int) $_POST['max_siswa'] : 0;
  $lat = mysqli_real_escape_string($koneksi, $_POST['lat']);
  $lng = mysqli_real_escape_string($koneksi, $_POST['lng']);
  $radius = isset($_POST['radius']) ? (int) $_POST['radius'] : 100;

  if (empty($nama) || $max_siswa < 1 || empty($lat) || empty($lng)) {
    echo "<script>
                alert('Semua field wajib diisi!');
                window.history.back();
              </script>";
    exit;
  }

  $query = "INSERT INTO kelas 
            (nama_kelas, max_siswa, latitude, longitude, radius_meter) 
             VALUES ('$nama', '$max_siswa', '$lat', '$lng', '$radius')";

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


// Fungsi: Memperbarui nama kelas yang sudah ada di tabel kelas.
// Parameter input:
// - $_POST['id']: ID kelas yang akan diperbarui.
// - $_POST['nama']: nama kelas baru yang akan disimpan.
// Return value:
// - Tidak mengembalikan nilai; menjalankan UPDATE pada kelas lalu redirect dengan pesan sukses atau gagal.
// Contoh penggunaan:
// - Dipicu dari form edit kelas di admin/edit/kelas.php yang disubmit dengan tombol name="edit_kelas".
// Catatan penting:
// - Hanya kolom nama_kelas yang diubah; kolom lain seperti wali_kelas tidak disentuh di sini.
// Edit data kelas
if (isset($_POST['edit_kelas'])) {
  $id = $_POST['id'];
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $max_siswa = isset($_POST['max_siswa']) ? (int) $_POST['max_siswa'] : 0;
  $lat = mysqli_real_escape_string($koneksi, $_POST['lat']);
  $lng = mysqli_real_escape_string($koneksi, $_POST['lng']);
  $radius = isset($_POST['radius']) ? (int) $_POST['radius'] : 100;

  if (empty($nama) || $max_siswa < 1 || empty($lat) || empty($lng)) {
    echo "<script>
                alert('Semua field wajib diisi!');
                window.history.back();
              </script>";
    exit;
  }

  $update = mysqli_query($koneksi, "UPDATE kelas 
    SET nama_kelas='$nama',
        max_siswa='$max_siswa',
        latitude='$lat',
        longitude='$lng',
        radius_meter='$radius'
    WHERE id_kelas='$id'");

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

// Fungsi: Menambahkan jadwal pelajaran baru untuk suatu kelas pada hari dan jam tertentu.
// Parameter input:
// - $_POST['id_kelas']: ID kelas yang akan diberi jadwal pelajaran.
// - $_POST['mapel']: nama mata pelajaran yang diajarkan.
// - $_POST['nama_guru']: nama guru pengajar mata pelajaran tersebut.
// - $_POST['hari']: hari pelaksanaan pelajaran (misalnya Senin, Selasa).
// - $_POST['jam_mulai']: jam mulai pelajaran dalam format HH:MM.
// - $_POST['jam_selesai']: jam selesai pelajaran dalam format HH:MM.
// Return value:
// - Tidak mengembalikan nilai; menyisipkan baris baru ke jadwal atau menampilkan pesan error bila tidak valid.
// Contoh penggunaan:
// - Dipicu saat admin menambah jadwal di admin/jadwal.php dengan tombol name="tambah_jadwal".
// Catatan penting:
// - Dilakukan validasi bentrok jam antar jadwal di kelas yang sama serta pembatasan satu guru hanya mengajar satu mata pelajaran.
// =============== TAMBAH JADWAL ==================
if (isset($_POST['tambah_jadwal'])) {
  $id_kelas = $_POST['id_kelas'];
  $mapel = $_POST['mapel'];
  $nama_guru = mysqli_real_escape_string($koneksi, $_POST['nama_guru']);
  $hari = $_POST['hari'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];

  if (empty($nama_guru)) {
    echo "<script>
                alert('Nama guru wajib diisi!');
                window.location.href='jadwal.php';
              </script>";
    exit;
  }

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
        FROM jadwal
        WHERE id_kelas = '$id_kelas'
          AND hari = '$hari'
          AND (
                ( '$jam_mulai' < jam_selesai ) 
            AND ( '$jam_selesai' > jam_mulai )
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
  // CEK RELASI 1 GURU : 1 MAPEL
  // ----------------------------
  $cekGuru = mysqli_query($koneksi, "
        SELECT DISTINCT mata_pelajaran 
        FROM jadwal
        WHERE nama_guru = '$nama_guru'
        LIMIT 1
    ");

  if ($cekGuru && mysqli_num_rows($cekGuru) > 0) {
    $rowGuru = mysqli_fetch_assoc($cekGuru);
    if ($rowGuru['mata_pelajaran'] !== $mapel) {
      echo "<script>
                    alert('Guru ini sudah terdaftar mengajar mata pelajaran \"{$rowGuru['mata_pelajaran']}\". Satu guru hanya boleh mengajar satu mata pelajaran.');
                    window.location.href='jadwal.php';
                  </script>";
      exit;
    }
  }

  // ----------------------------
  // INSERT JADWAL JIKA VALID
  // ----------------------------
  $query = "
        INSERT INTO jadwal 
        (id_kelas, mata_pelajaran, nama_guru, hari, jam_mulai, jam_selesai)
        VALUES ('$id_kelas', '$mapel', '$nama_guru', '$hari', '$jam_mulai', '$jam_selesai')
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

// Fungsi: Mengubah jadwal pelajaran yang sudah ada tanpa menambah baris baru.
// Parameter input:
// - $_POST['id_jadwal']: ID jadwal yang akan diperbarui.
// - $_POST['id_kelas']: ID kelas terkait jadwal tersebut.
// - $_POST['mapel']: nama mata pelajaran terbaru.
// - $_POST['nama_guru']: nama guru pengajar terbaru.
// - $_POST['hari']: hari pelaksanaan pelajaran setelah perubahan.
// - $_POST['jam_mulai']: jam mulai pelajaran yang baru.
// - $_POST['jam_selesai']: jam selesai pelajaran yang baru.
// Return value:
// - Tidak mengembalikan nilai; menjalankan UPDATE pada jadwal lalu redirect dengan pesan sesuai hasil.
// Contoh penggunaan:
// - Dipicu dari form edit jadwal di admin/edit/jadwal.php yang disubmit dengan tombol name="edit_jadwal".
// Catatan penting:
// - Tetap dilakukan pengecekan bentrok jadwal dan relasi satu guru satu mata pelajaran sebelum perubahan disimpan.
// =============== EDIT JADWAL ==================
if (isset($_POST['edit_jadwal'])) {

  $id_jadwal = $_POST['id_jadwal'];
  $id_kelas = $_POST['id_kelas'];
  $mapel = $_POST['mapel'];
  $nama_guru = mysqli_real_escape_string($koneksi, $_POST['nama_guru']);
  $hari = $_POST['hari'];
  $jam_mulai = $_POST['jam_mulai'];
  $jam_selesai = $_POST['jam_selesai'];

  if (empty($nama_guru)) {
    echo "<script>
                alert('Nama guru wajib diisi!');
                window.location.href='edit/jadwal.php?id=$id_jadwal';
              </script>";
    exit;
  }

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
        FROM jadwal
        WHERE id_kelas = '$id_kelas'
          AND hari = '$hari'
          AND id_jadwal != '$id_jadwal'
          AND (
                ('$jam_mulai' < jam_selesai)
            AND ('$jam_selesai' > jam_mulai)
          )
    ");

  if (mysqli_num_rows($cek) > 0) {
    echo "<script>
                alert('Jadwal bentrok! Silahkan cek jam kelas lain.');
                window.location.href='edit/jadwal.php?id=$id_jadwal';
              </script>";
    exit;
  }

  // ----------------------------
  // CEK RELASI 1 GURU : 1 MAPEL (EDIT)
  // ----------------------------
  $cekGuru = mysqli_query($koneksi, "
        SELECT DISTINCT mata_pelajaran 
        FROM jadwal
        WHERE nama_guru = '$nama_guru'
          AND id_jadwal != '$id_jadwal'
        LIMIT 1
    ");

  if ($cekGuru && mysqli_num_rows($cekGuru) > 0) {
    $rowGuru = mysqli_fetch_assoc($cekGuru);
    if ($rowGuru['mata_pelajaran'] !== $mapel) {
      echo "<script>
                    alert('Guru ini sudah terdaftar mengajar mata pelajaran \"{$rowGuru['mata_pelajaran']}\". Satu guru hanya boleh mengajar satu mata pelajaran.');
                    window.location.href='edit/jadwal.php?id=$id_jadwal';
                  </script>";
      exit;
    }
  }

  // --- UPDATE JADWAL ---
  mysqli_query($koneksi, "
        UPDATE jadwal SET 
            id_kelas = '$id_kelas',
            mata_pelajaran = '$mapel',
            nama_guru = '$nama_guru',
            hari = '$hari',
            jam_mulai = '$jam_mulai',
            jam_selesai = '$jam_selesai'
        WHERE id_jadwal = '$id_jadwal'
    ");

  echo "<script>
            alert('Jadwal berhasil diperbarui!');
            window.location.href='jadwal.php';
          </script>";
  exit;
}

// Fungsi: Mengubah data absensi yang sudah tercatat, seperti tanggal, jam scan, atau status kehadiran.
// Parameter input:
// - $_POST['id_absensi']: ID baris absensi yang akan diedit.
// - $_POST['tanggal']: tanggal kehadiran baru dalam format YYYY-MM-DD.
// - $_POST['waktu_scan']: jam scan baru dalam format HH:MM.
// - $_POST['status']: status kehadiran baru (Hadir, Terlambat, Alfa).
// Return value:
// - Tidak mengembalikan nilai; menjalankan UPDATE pada absensi dan redirect dengan pesan sukses atau gagal.
// Contoh penggunaan:
// - Dipicu dari form edit absensi di admin/edit/absen.php yang disubmit dengan tombol name="edit_absensi".
// Catatan penting:
// - Status kehadiran dibatasi hanya ke nilai yang ada di array $allowedStatus untuk mencegah input tidak valid.
// =============== EDIT ABSENSI ==================
if (isset($_POST['edit_absensi'])) {
  $id_absensi = isset($_POST['id_absensi']) ? (int) $_POST['id_absensi'] : 0;
  $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
  $waktu = mysqli_real_escape_string($koneksi, $_POST['waktu_scan']);
  $status = mysqli_real_escape_string($koneksi, $_POST['status']);

  if (empty($id_absensi) || empty($tanggal) || empty($waktu) || empty($status)) {
    echo "<script>
                alert('Semua field wajib diisi!');
                window.history.back();
              </script>";
    exit;
  }

  $allowedStatus = ['Hadir', 'Terlambat', 'Alfa'];
  if (!in_array($status, $allowedStatus, true)) {
    echo "<script>
                alert('Status kehadiran tidak valid!');
                window.history.back();
              </script>";
    exit;
  }

  $update = mysqli_query($koneksi, "
        UPDATE absensi SET
            tanggal = '$tanggal',
            waktu_scan = '$waktu',
            status_kehadiran = '$status'
        WHERE id_absensi = '$id_absensi'
    ");

  if ($update) {
    echo "<script>
                alert('Data absensi berhasil diperbarui!');
                window.location.href='absen.php';
              </script>";
  } else {
    echo "<script>
                alert('Gagal memperbarui data absensi!');
                window.history.back();
              </script>";
  }
  exit;
}

