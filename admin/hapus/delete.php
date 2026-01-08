<?php 

require '../koneksi.php';

// Fungsi: Menghapus satu baris data dari tabel tertentu berdasarkan ID yang dikirim melalui URL.
// Parameter input:
// - $_GET['tabel']: nama tabel yang akan dihapus (misalnya siswa_232410, kelas_232410).
// - $_GET['id']: nilai ID baris yang akan dihapus, akan dipetakan ke kolom id_<nama_tabel>.
// Return value:
// - Tidak mengembalikan nilai; menjalankan perintah DELETE lalu menampilkan pesan berhasil atau gagal melalui JavaScript.
// Contoh penggunaan:
// - Dipanggil dari tombol hapus pada halaman admin yang mengarahkan ke hapus/delete.php?tabel=...&id=....
// Catatan penting:
// - Nilai $tabel berasal langsung dari URL tanpa whitelist sehingga perlu kewaspadaan ekstra terhadap SQL Injection.
// Ambil tabel yang akan dihapus dari URL
$tabel = $_GET['tabel'];
$id = $_GET['id'];


    $query = "DELETE FROM $tabel WHERE id_$tabel = '$id'";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Data $tabel dengan id $id berhasil dihapus!');
                window.history.back();
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
    }

