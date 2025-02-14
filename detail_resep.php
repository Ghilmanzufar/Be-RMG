<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
include 'connection.php'; // Menggunakan MySQLi ($conn)

// Pastikan ID dikirim
if (!isset($_GET['id'])) {
    echo json_encode(["error" => "ID resep diperlukan"]);
    exit;
}

$id_resep = $_GET['id'];

// Ambil data resep
$sql = "SELECT id, judul, deskripsi, gambar, durasi_masak, kategori FROM resep WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_resep);
$stmt->execute();
$result = $stmt->get_result();
$resep = $result->fetch_assoc();

if (!$resep) {
    echo json_encode(["error" => "Resep tidak ditemukan"]);
    exit;
}

// Ambil data bahan berdasarkan id_resep
$sql = "SELECT bahan, jumlah FROM bahan WHERE id_resep = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_resep);
$stmt->execute();
$result = $stmt->get_result();
$bahan = $result->fetch_all(MYSQLI_ASSOC);

// Ambil data langkah berdasarkan id_resep
$sql = "SELECT langkah, urutan FROM langkah WHERE id_resep = ? ORDER BY urutan ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_resep);
$stmt->execute();
$result = $stmt->get_result();
$langkah = $result->fetch_all(MYSQLI_ASSOC);

// Gabungkan data dan kirim sebagai JSON
$response = [
    "resep" => $resep,
    "bahan" => $bahan,
    "langkah" => $langkah
];

echo json_encode($response);
?>
