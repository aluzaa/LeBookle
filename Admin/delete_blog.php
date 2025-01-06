<?php
header('Content-Type: application/json');

// Koneksi ke database
require 'db.php';

// Ambil data ID dari request
$id = $_POST['id'] ?? null;

if ($id) {
    // Query untuk menghapus blog berdasarkan ID
    $query = "DELETE FROM blog WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Artikel berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus artikel.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid.']);
}

$conn->close();
?>
