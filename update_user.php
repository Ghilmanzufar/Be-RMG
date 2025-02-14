<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once 'connection.php';

$id = $_POST['id'] ?? '';
$nama = $_POST['nama'] ?? '';
$nomer = $_POST['nomer'] ?? '';

// Validasi input
if (empty($id) || empty($nama) || empty($nomer)) {
    echo json_encode([
        "status" => "error",
        "message" => "Semua field harus diisi!"
    ]);
    exit;
}

$id = $conn->real_escape_string($id);
$nama = $conn->real_escape_string($nama);
$nomer = $conn->real_escape_string($nomer);

// Penanganan file foto (opsional)
$photoUrl = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo = $_FILES['photo'];
    $photoName = time() . "_" . $photo['name'];
    $uploadDir = "uploads/";
    $uploadPath = $uploadDir . $photoName;

    if (move_uploaded_file($photo['tmp_name'], $uploadPath)) {
        $photoUrl = "http://localhost/Project_resep_Masakan/be_resep/" . $uploadPath;
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "File upload failed"
        ]);
        exit;
    }
}

// Query update ke database
$sql = "UPDATE user SET nama = '$nama', nomer = '$nomer'";
if ($photoUrl) {
    $sql .= ", photo = '$photoUrl'";
}
$sql .= " WHERE id = '$id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "success",
        "message" => "Data berhasil diperbarui!",
        "photoUrl" => $photoUrl
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal memperbarui data: " . $conn->error
    ]);
}

$conn->close();
?>
