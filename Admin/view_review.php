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

// Mendapatkan ID review dari parameter GET
$reviewId = $_GET['id'];

// Query untuk mengambil data review beserta username pembuat review
$sql = "SELECT r.id, r.rating, r.comment, r.created_at, b.judul, u.username AS reviewer_name
        FROM reviews r
        JOIN books b ON r.book_id = b.id
        JOIN users u ON r.user_id = u.id
        WHERE r.id = ?";
$stmt = $conn->prepare($sql);

// Memeriksa apakah query berhasil dipersiapkan
if ($stmt === false) {
    die('Query preparation failed: ' . $conn->error);
}

$stmt->bind_param("i", $reviewId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Ambil data review
    $row = $result->fetch_assoc();

    // Menampilkan judul buku
    echo "<h5>" . htmlspecialchars($row['judul']) . "</h5>";
    
    // Menampilkan rating dengan bintang
    $rating = $row['rating'];
    echo "<p>Rating: ";
    for ($i = 1; $i <= 5; $i++) {
        // Menampilkan bintang yang terisi atau kosong berdasarkan rating
        if ($i <= $rating) {
            echo '<i class="ri-star-fill" style="color: gold;"></i>'; // Bintang terisi
        } else {
            echo '<i class="ri-star-line"></i>'; // Bintang kosong
        }
    }
    echo "</p>";
    
    // Menampilkan komentar
    echo "<p>Komentar: " . nl2br(htmlspecialchars($row['comment'])) . "</p>";
    
    // Menampilkan tanggal pembuatan review
    echo "<p>Dibuat pada: " . $row['created_at'] . "</p>";

    // Menampilkan nama pembuat review (username)
    echo "<p><strong>Username:</strong> " . htmlspecialchars($row['reviewer_name']) . "</p>";

} else {
    echo "Review tidak ditemukan.";
}

$stmt->close();
$conn->close();
?>
