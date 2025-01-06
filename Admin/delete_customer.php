<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_lebooklex";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil ID dari parameter POST
if (isset($_POST['id'])) {
    $userId = $_POST['id']; // Ambil user_id yang dikirimkan melalui POST

    // Pastikan ID tidak kosong
    if (!empty($userId)) {
        // Persiapkan query untuk menghapus data customer berdasarkan user_id
        $stmt = $conn->prepare("DELETE FROM customer WHERE user_id = ?");
        $stmt->bind_param("i", $userId); // "i" untuk integer (user_id)

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil
            echo json_encode(["success" => true, "message" => "Data berhasil dihapus."]);
        } else {
            // Jika gagal
            echo json_encode(["success" => false, "message" => "Gagal menghapus data."]);
        }

        // Menutup statement
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "ID tidak ditemukan."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID tidak diberikan."]);
}

// Menutup koneksi database
$conn->close();
?>
