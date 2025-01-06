<?php
$servername = "localhost"; // Your database host
$username = "root";        // Your database username
$password = "";            // Your database password
$dbname = "db_lebooklex"; // Your database name

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);  // Ganti $host menjadi $servername
    // Set atribut error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Menangani kesalahan koneksi
    die("Koneksi gagal: " . $e->getMessage());
}
?>

