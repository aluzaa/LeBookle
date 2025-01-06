<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_lebooklex";

$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses update data order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input
    if (empty($_POST['order_id']) || empty($_POST['status']) || empty($_POST['total'])) {
        die("Error: Missing required data");
    }

    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $total = $_POST['total'];

    // Siapkan query hanya untuk kolom status dan total
    $sql = $conn->prepare("UPDATE orders 
                           SET status = ?, total = ? 
                           WHERE order_id = ?");
    if (!$sql) {
        die("Error preparing statement: " . $conn->error);
    }

    $sql->bind_param("sii", $status, $total, $order_id);

    if ($sql->execute()) {
        header("Location: page-list-orders.php"); // Redirect ke halaman daftar order
    } else {
        echo "Error executing statement: " . $sql->error;
    }

    $sql->close();
}

$conn->close();
?>
