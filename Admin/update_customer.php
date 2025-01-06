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

// Proses update data customer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $city = !empty($_POST['city']) ? $_POST['city'] : NULL;
    $state = $_POST['state'];
    $postal_code = !empty($_POST['postal_code']) ? $_POST['postal_code'] : NULL;
    $country = !empty($_POST['country']) ? $_POST['country'] : NULL;
    $phone = !empty($_POST['phone']) ? $_POST['phone'] : NULL;

    // Siapkan query
    $sql = $conn->prepare("UPDATE customer SET fullname = ?, address = ?, city = ?, state = ?, postal_code = ?, country = ?, phone = ? WHERE user_id = ?");
    $sql->bind_param("sssssssi", $fullname, $address, $city, $state, $postal_code, $country, $phone, $user_id);
    
    if ($sql->execute()) {
        header("Location: page-list-customer.php");
    } else {
        echo "Error: " . $sql->error;
    }
    
    $sql->close();
}

$conn->close();
?>
