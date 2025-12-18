<?php 

require '../koneksi.php';

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

