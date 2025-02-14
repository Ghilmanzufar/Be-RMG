<?php
require_once 'connection.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}
if (!$conn) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Ambil data JSON yang dikirim dari frontend
$data = json_decode(file_get_contents("php://input"), true);

// Validasi apakah semua field yang diperlukan ada
if (!isset($data['email'], $data['nama'], $data['nomer'], $data['saran'])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Semua field (email, nama, nomer, saran) harus diisi!"
    ]);
    exit();
}

// Filter dan sanitasi input
$email = $conn->real_escape_string(trim($data['email']));
$nama = $conn->real_escape_string(trim($data['nama']));
$nomer = $conn->real_escape_string(trim($data['nomer']));
$saran = $conn->real_escape_string(trim($data['saran']));

// Validasi tambahan (opsional)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Format email tidak valid!"
    ]);
    exit();
}

// Masukkan data ke database
$sql = "INSERT INTO saran (email, nama, nomer, saran) VALUES ('$email', '$nama', '$nomer', '$saran')";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "success",
        "message" => "Saran berhasil disimpan!"
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyimpan saran: " . $conn->error
    ]);
}

// Tutup koneksi database
$conn->close();
?>
