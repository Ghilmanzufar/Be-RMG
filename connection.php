<?php
// Konfigurasi database
$host = 'mysql://root:DYyDJzcmwCIhULUyUsMSIMRTupxGcWfQ@autorack.proxy.rlwy.net:31941/railway';
$username = 'root';
$password = '';
$database = 'db_resep';

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>