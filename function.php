<?php
require 'koneksi.php';


// Fungsi: Memproses login admin menggunakan email dan password yang dikirim lewat form.
// Parameter input:
// - $_POST['email']: alamat email admin yang diinput di form login.
// - $_POST['password']: password admin yang diinput di form login.
// Return value:
// - Tidak mengembalikan nilai; mengatur data $_SESSION dan melakukan redirect menggunakan JavaScript.
// Contoh penggunaan:
// - Dipicu saat form login admin disubmit dengan tombol name="login_admin".
// Catatan penting:
// - Password masih dibandingkan dalam bentuk teks biasa (belum di-hash) sehingga hanya cocok untuk lingkungan terkontrol.
if (isset($_POST['login_admin'])) {

    // Pastikan request datang dari form POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Ambil data dari form login
        $email = mysqli_real_escape_string($koneksi, $_POST['email']);
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);

        // Query cek email di tabel admin
        $query = "SELECT * FROM admin_232410 WHERE email_admin_232410 = '$email' LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            $data = mysqli_fetch_assoc($result);

            // Cek apakah password sesuai
            if ($password === $data['password_admin_232410']) {

                // Simpan semua data admin ke dalam session
                foreach ($data as $key => $value) {
                    $_SESSION[$key] = $value; // simpan semua kolom ke $_SESSION
                }

                // Buat juga indikator login
                $_SESSION['login'] = true;

                echo "<script>alert('Login berhasil!'); window.location.href='admin/index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Email tidak ditemukan!'); window.location.href='login.php';</script>";
        }
    }
}

// Fungsi: Memproses login siswa menggunakan NISN dan password yang dikirim lewat form.
// Parameter input:
// - $_POST['nisn']: NISN siswa yang digunakan sebagai kredensial login.
// - $_POST['password']: password siswa yang diinput di form login.
// Return value:
// - Tidak mengembalikan nilai; menyetel data sesi siswa pada $_SESSION dan melakukan redirect ke dashboard.
// Contoh penggunaan:
// - Dipicu saat form login siswa disubmit dengan tombol name="login_siswa".
// Catatan penting:
// - Kredensial masih dicek dalam bentuk teks biasa tanpa hashing sehingga lebih cocok untuk lingkungan tertutup.
if (isset($_POST["login_siswa"])) {
     // Ambil data dari form login
        $nisn = mysqli_real_escape_string($koneksi, $_POST['nisn']);
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);

        // Query cek email di tabel admin
        $query = "SELECT * FROM siswa_232410 WHERE nisn_232410 = '$nisn' LIMIT 1";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) == 1) {
            $data = mysqli_fetch_assoc($result);

            // Cek apakah password sesuai
            if ($password === $data['password_232410']) {

                // Simpan semua data admin ke dalam session
                foreach ($data as $key => $value) {
                    $_SESSION[$key] = $value; // simpan semua kolom ke $_SESSION
                }

                // Buat juga indikator login
                $_SESSION['login_siswa'] = true;

                // Arahkan ke dashboard
                echo "<script>alert('Login berhasil!'); window.location.href='dashboard.php';</script>";
                exit();
            } else {
                echo "<script>alert('Password salah!'); window.location.href='login_siswa.php';</script>";
            }
        } else {
            echo "<script>alert('NISN tidak ditemukan!'); window.location.href='login_siswa.php';</script>";
        }
}

// Fungsi: Memproses registrasi admin baru berdasarkan data yang dikirim dari form.
// Parameter input:
// - $_POST['nama']: nama lengkap admin yang akan diregistrasi.
// - $_POST['email']: alamat email admin yang akan digunakan untuk login.
// - $_POST['password']: password admin dalam bentuk teks biasa.
// Return value:
// - Tidak mengembalikan nilai; menyimpan data admin ke database atau menampilkan pesan gagal melalui JavaScript.
// Contoh penggunaan:
// - Dipicu saat form registrasi admin disubmit dengan tombol name="registrasi".
// Catatan penting:
// - Hanya melakukan validasi sederhana duplikasi email dan belum mengenkripsi password, sehingga perlu peningkatan keamanan untuk produksi.
if (isset($_POST['registrasi'])) {

    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Validasi: cek apakah email sudah digunakan
    $cek_email = mysqli_query($koneksi, "SELECT * FROM admin_232410 WHERE email_admin_232410='$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar, silakan gunakan email lain!'); window.location.href='register.php';</script>";
        exit();
    }

    // Simpan data admin baru
    $query = "INSERT INTO admin_232410 (nama_admin_232410, email_admin_232410, password_admin_232410, created_at_232410) 
              VALUES ('$nama', '$email', '$password', NOW())";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal: " . mysqli_error($koneksi) . "'); window.location.href='register.php';</script>";
    }
}

?>
