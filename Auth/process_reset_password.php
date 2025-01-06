<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $query = "UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $new_password, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Kata sandi berhasil diatur ulang.";
    } else {
        echo "Tautan reset tidak valid.";
    }
}
?>
