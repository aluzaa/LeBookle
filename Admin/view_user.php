<?php
require 'db.php'; // Pastikan koneksi ke database

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo "<p><strong>ID:</strong> {$user['id']}</p>";
        echo "<p><strong>Username:</strong> {$user['username']}</p>";
        echo "<p><strong>Email:</strong> {$user['email']}</p>";
        echo "<p><strong>Password Hash:</strong> {$user['password_hash']}</p>";
        echo "<p><strong>Google ID:</strong> {$user['google_id']}</p>";
        echo "<p><strong>Role:</strong> {$user['role']}</p>";
        echo "<p><strong>Dibuat pada:</strong> {$user['created_at']}</p>";
    } else {
        echo "<p>Data pengguna tidak ditemukan.</p>";
    }
}
?>
