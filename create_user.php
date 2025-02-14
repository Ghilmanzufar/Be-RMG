<?php
include 'connection.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input);

    file_put_contents('debug.log', "Input: " . $input . PHP_EOL, FILE_APPEND);

    if (!isset($data->nama) || !isset($data->nomer) || !isset($data->email) || !isset($data->password)) {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        exit;
    }

    $nama = $conn->real_escape_string($data->nama);
    $nomer = $conn->real_escape_string($data->nomer);
    $email = $conn->real_escape_string($data->email);
    $password = $conn->real_escape_string($data->password);

    $query = "INSERT INTO user (nama, nomer, email, password) VALUES ('$nama', '$nomer', '$email', '$password')";

    if ($conn->query($query) === TRUE) {
        echo json_encode(["success" => true, "message" => "User created successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
