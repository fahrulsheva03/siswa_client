<?php

// Mulai session untuk menyimpan data login
session_start();

// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "db_absensi_232410");




// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
