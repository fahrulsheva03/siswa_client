<?php

// Mulai session untuk menyimpan data login
session_start();

// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "absensi_qr");




// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
