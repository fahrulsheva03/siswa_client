<?php



// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "db_absensi_232410");


// Mulai session untuk menyimpan data login
session_start();

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
