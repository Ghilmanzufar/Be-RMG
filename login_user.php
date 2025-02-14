<?php
require_once 'connection.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Ambil data dari request POST
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi input
if (empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Email dan password harus diisi!"]);
    exit;
}

// Escape input untuk keamanan
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

// Query untuk mengambil data user berdasarkan email dan password
$sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
$result = $conn->query($sql);

// Periksa apakah user ditemukan
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $token = bin2hex(random_bytes(16)); // Generasi token
    $sql = "UPDATE user SET token = '$token' WHERE id = '$user[id]'";
    $conn->query($sql);
    echo json_encode([
        "status" => "success",
        "message" => " Login berhasil!",
        "data" => [
            "id" => $user['id'],
            "nama" => $user['nama'],
            "email" => $user['email'],
            "nomer" => $user['nomer'],
            "photo" => $user["photo"],
            "token" => $token
        ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Email atau password salah!"]);
    }
    
$conn->close();
?>    