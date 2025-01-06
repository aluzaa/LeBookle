<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_lebooklex";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reviewId = $_POST['review_id'];
    $bookId = $_POST['book_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $sql = "UPDATE reviews SET book_id = ?, rating = ?, comment = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $bookId, $rating, $comment, $reviewId);

    if ($stmt->execute()) {
        header("Location: page-list-review.php?success=update");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
