<?php
session_start();

// Include koneksi database
require_once 'db.php'; // Pastikan path ini sesuai dengan file koneksi database Anda

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: /LeBookle/Auth/index.php");
    exit;
}

// Ambil data dari formulir
$user_id = $_SESSION['user_id']; // ID user yang login
$book_id = $_GET['id']; // Ambil ID buku dari URL
$rating = isset($_POST['rating']) ? (int) $_POST['rating'] : 0;
$comment = trim($_POST['comment']);

// Validasi input
if (empty($comment) || $rating < 1 || $rating > 5) {
    $_SESSION['error'] = "Komentar dan rating harus valid.";
    header("Location: /LeBookle/User/product-details.php?id=$book_id"); // Arahkan kembali ke halaman detail produk
    exit;
}

// Simpan ulasan ke database
try {
    // Menyimpan ulasan ke tabel reviews
    $stmt = $pdo->prepare("INSERT INTO reviews (user_id, book_id, rating, comment) VALUES (:user_id, :book_id, :rating, :comment)");
    $stmt->execute([
        'user_id' => $user_id,
        'book_id' => $book_id,
        'rating' => $rating,
        'comment' => $comment
    ]);

    $_SESSION['success'] = "Ulasan berhasil dikirim.";
    header("Location: /LeBookle/User/product-details.php?id=$book_id"); // Arahkan kembali ke halaman detail produk
    exit;
} catch (PDOException $e) {
    // Jika terjadi kesalahan saat menyimpan ke database
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
    header("Location: /LeBookle/User/product-details.php?id=$book_id");
    exit;
}

// Pastikan ID buku ada dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID buku tidak valid.";
    header("Location: /LeBookle/User/product-list.php"); // Arahkan ke halaman daftar buku
    exit;
}

$book_id = (int) $_GET['id']; // Ambil ID buku dari URL dan pastikan tipe data adalah integer

?>
