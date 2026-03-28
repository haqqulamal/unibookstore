<?php

// Define database credentials
$host = "localhost";
$user = "root";
$password = "";
$database = "unibookstore";

// Create connection
$koneksi = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($koneksi, "utf8");

?>
