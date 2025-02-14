<?php
require_once 'connection.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Izinkan semua origin (Ganti * dengan domain spesifik jika perlu)
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Izinkan metode HTTP tertentu
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Izinkan header tertentu

// Pastikan ini ada sebelum logika pemrosesan data
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['resep_id'])) {
    $user_id = $data['user_id'];
    $resep_id = $data['resep_id'];

    $checkQuery = "SELECT * FROM save_resep WHERE user_id = ? AND resep_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $user_id, $resep_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Resep sudah tersimpan"]);
    } else {
        $insertQuery = "INSERT INTO save_resep (user_id, resep_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $user_id, $resep_id);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Resep berhasil disimpan"]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menyimpan resep"]);
        }
    }    
} else {
    echo json_encode(["error" => "Data tidak lengkap"]);
}
?>
