<?php
// Koneksi ke database
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "db_lebooklex"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "SELECT id, judul, penulis, price, cover, sinopsis FROM books WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
        echo json_encode(['success' => true, 'penulis' => $book['penulis'], 'judul' => $book['judul'], 'price' => $book['price'], 'cover' => $book['cover'], 'sinopsis' => $book['sinopsis']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Book not found']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}

$conn->close();
?>
