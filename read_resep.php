<?php
require_once 'connection.php';
header("Access-Control-Allow-Origin: http://localhost:3000"); // Izinkan hanya dari React
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Menyiapkan query untuk mengambil data resep terbaru berdasarkan ID (terbesar ke terkecil)
$query = "SELECT id, judul, deskripsi, gambar, durasi_masak, kategori, rating FROM resep ORDER BY id DESC"; 
$result = $conn->query($query);

// Cek apakah ada data
if ($result->num_rows > 0) {
    // Mengambil data dan mengembalikannya dalam format JSON
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
} else {
    echo json_encode([]);
}


// Menutup koneksi
$conn->close();
?>
