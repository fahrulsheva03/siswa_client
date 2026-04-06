<?php



// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "absensi_qr");


// Mulai session untuk menyimpan data login
session_start();

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
