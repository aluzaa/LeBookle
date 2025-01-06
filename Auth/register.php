<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location.href = 'index.php';</script>";
        exit;
    }

    // Insert data ke database
    $query = "INSERT INTO users (username, email, password_hash, role, created_at) 
              VALUES ('$username', '$email', '$password', 'user', NOW())";

    if ($conn->query($query)) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
