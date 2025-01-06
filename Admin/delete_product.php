<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_lebooklex";

$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi database gagal."]));
}

// Mendapatkan ID dari request POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Menghapus buku berdasarkan ID
    $sql = $conn->prepare("DELETE FROM books WHERE id = ?");
    $sql->bind_param("i", $id);

    if ($sql->execute()) {
        echo json_encode(["success" => true, "message" => "Buku berhasil dihapus."]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus buku."]);
    }

    $sql->close();
} else {
    echo json_encode(["success" => false, "message" => "ID buku tidak valid."]);
}

$conn->close();
?>
