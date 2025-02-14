<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

include 'connection.php';

// Handle preflight request (OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->resep_id)) {
    $query = "DELETE FROM save_resep WHERE resep_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $data->resep_id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Resep berhasil dihapus"]);
    } else {
        echo json_encode(["message" => "Gagal menghapus resep"]);
    }
} else {
    echo json_encode(["message" => "Data tidak lengkap"]);
}
?>
