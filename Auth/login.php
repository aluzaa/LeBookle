<?php
session_start();

// Ganti dengan kredensial database Anda
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_lebooklex";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Gunakan prepared statement untuk menghindari SQL Injection
    $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifikasi password dengan password_hash
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            // Redirect sesuai role
            if ($user['role'] == 'admin') {
                header('Location: ../Admin/index.php');
            } else {
                header('Location: ../User/index.php');
            }
            exit();
        } else {
            echo "Password salah.";
        }
    } else {
        echo "Email tidak terdaftar.";
    }
}

// Jangan lupa menutup koneksi
$conn->close();
?>
