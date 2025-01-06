<?php
require_once 'db.php';
session_start(); // Memulai sesi

// Periksa apakah pengguna login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login untuk memberikan ulasan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user_id']; // Ambil user_id dari sesi
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $created_at = date('Y-m-d H:i:s');

    // Validasi data
    if (empty($book_id) || empty($rating) || empty($comment)) {
        echo "Semua bidang wajib diisi!";
        exit;
    }

    // Masukkan data ke database
    $stmt = $pdo->prepare("INSERT INTO reviews (book_id, user_id, rating, comment, created_at) VALUES (:book_id, :user_id, :rating, :comment, :created_at)");
    $stmt->execute([
        'book_id' => $book_id,
        'user_id' => $user_id,
        'rating' => $rating,
        'comment' => $comment,
        'created_at' => $created_at
    ]);

    // Redirect kembali ke halaman buku
    header("Location: product-details.php?id=$book_id");
    exit;
}
?>
