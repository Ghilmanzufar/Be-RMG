<?php
require_once 'connection.php';

header("Access-Control-Allow-Origin: http://localhost:3000"); // Izinkan hanya dari React
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // Jika menggunakan kredensial (cookie/token)


// Cek koneksi
if (!$conn) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Ambil token dari header Authorization
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = '';
if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1]; // Ambil token setelah "Bearer"
}

if (empty($token)) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Token tidak valid']);
    exit;
}

// Query untuk mengambil data pengguna berdasarkan token
$sql = "SELECT nama, email, nomer, photo FROM user WHERE token = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Terjadi kesalahan pada server']);
    exit;
}

if ($result->num_rows === 0) {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Pengguna tidak ditemukan']);
    exit;
}

// Kirim hasil query
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode(['status' => 'success', 'data' => $data]);
error_log(json_encode(['Token diterima' => $token]));
error_log(json_encode(['Respons data' => $data]));

$stmt->close();
$conn->close();
