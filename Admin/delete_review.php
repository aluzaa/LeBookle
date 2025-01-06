<?php
// Koneksi ke database
$host = 'localhost'; // Ganti dengan host database Anda
$db = 'db_lebooklex'; // Ganti dengan nama database Anda
$user = 'root'; // Ganti dengan username database Anda
$pass = ''; // Ganti dengan password database Anda jika ada

try {
    // Membuat koneksi dengan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    // Set error mode untuk menangani kesalahan
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Menangani jika terjadi kesalahan koneksi
    die("Connection failed: " . $e->getMessage());
}

// Pastikan id dikirim melalui POST
if (isset($_POST['id'])) {
    $reviewId = $_POST['id']; // Ambil ID review yang dikirimkan melalui POST

    try {
        // Penghapusan data berdasarkan ID review
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?"); // Pastikan nama tabel 'reviews' sesuai
        $stmt->execute([$reviewId]);

        // Kembali dengan response JSON
        echo json_encode(["success" => true, "message" => "Review berhasil dihapus"]);
    } catch (PDOException $e) {
        // Menangani jika terjadi kesalahan saat eksekusi query
        echo json_encode(["success" => false, "message" => "Error deleting review: " . $e->getMessage()]);
    }
}
?>
