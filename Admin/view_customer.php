<?php
require 'db.php'; // Pastikan koneksi ke database

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID pelanggan tidak dikirim.');
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Mengambil ID yang dikirimkan dari URL
    $stmt = $conn->prepare("
    SELECT c.id, c.fullname, c.address, c.city, c.state, c.postal_code, c.country, c.phone, c.created_at, u.username 
    FROM customer c 
    INNER JOIN users u ON c.user_id = u.id
    WHERE c.user_id = ?"); // Ubah c.id menjadi c.user_id

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        echo "<p><strong>Id:</strong> {$customer['id']}</p>";
        echo "<p><strong>Fullname:</strong> {$customer['fullname']}</p>";
        echo "<p><strong>Address:</strong> {$customer['address']}</p>";
        echo "<p><strong>City:</strong> {$customer['city']}</p>";
        echo "<p><strong>State:</strong> {$customer['state']}</p>";
        echo "<p><strong>Postal Code:</strong> {$customer['postal_code']}</p>";
        echo "<p><strong>Country:</strong> {$customer['country']}</p>";
        echo "<p><strong>Phone:</strong> {$customer['phone']}</p>";
        echo "<p><strong>Username:</strong> {$customer['username']}</p>";
        echo "<p><strong>Created at:</strong> {$customer['created_at']}</p>";
    } else {
        echo "<p>Data pelanggan tidak ditemukan.</p>";
    }
    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
        echo "<pre>";
        print_r($customer); // Debug data yang diambil
        echo "</pre>";
    } else {
        echo "<p>Data pelanggan tidak ditemukan.</p>";
    }
    
}
?>
