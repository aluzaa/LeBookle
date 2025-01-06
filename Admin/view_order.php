<?php
require 'db.php'; // Pastikan koneksi ke database

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('ID order tidak dikirim.');
}

$orderId = intval($_GET['id']); // Mengambil ID order yang dikirimkan dari URL

$query = "
    SELECT o.id AS order_id, o.order_date, o.status, o.total, o.notes, 
           o.user_id, u.username,
           oi.book_id, oi.quantity, oi.price, b.judul AS book_title,
           p.payment_date, p.amount, p.payment_method, p.status AS payment_status, p.proof_of_payment
    FROM orders o
    INNER JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN books b ON oi.book_id = b.id
    LEFT JOIN payments p ON o.id = p.order_id
    WHERE o.id = ?
";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Error preparing the query: ' . $conn->error);
}

$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo "<p><strong>Order ID:</strong> {$order['order_id']}</p>";
    echo "<p><strong>Username:</strong> {$order['username']}</p>";
    echo "<p><strong>Tanggal Pemesanan:</strong> {$order['order_date']}</p>";
    echo "<p><strong>Status:</strong> {$order['status']}</p>";
    echo "<p><strong>Total:</strong> Rp " . number_format($order['total'], 2, ',', '.') . "</p>";
    echo "<p><strong>Catatan:</strong> {$order['notes']}</p>";

    echo "<h5>Order Items:</h5>";
    echo "<table class='table'>";
    echo "<thead><tr><th>Judul Buku</th><th>Jumlah</th><th>Harga</th><th>Total</th></tr></thead><tbody>";

    do {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($order['book_title']) . "</td>";
        echo "<td>" . htmlspecialchars($order['quantity']) . "</td>";
        echo "<td>Rp " . number_format($order['price'], 2, ',', '.') . "</td>";
        echo "<td>Rp " . number_format($order['quantity'] * $order['price'], 2, ',', '.') . "</td>";
        echo "</tr>";
    } while ($order = $result->fetch_assoc());

    echo "</tbody></table>";

    // Ambil kembali data pembayaran, jika ada
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc(); // Ambil ulang data untuk pembayaran

    if (!empty($order['payment_date'])) {
        echo "<h5>Pembayaran:</h5>";
        echo "<p><strong>Tanggal Pembayaran:</strong> {$order['payment_date']}</p>";
        echo "<p><strong>Jumlah Dibayar:</strong> Rp " . number_format($order['amount'], 2, ',', '.') . "</p>";
        echo "<p><strong>Metode Pembayaran:</strong> {$order['payment_method']}</p>";
        echo "<p><strong>Status Pembayaran:</strong> {$order['payment_status']}</p>";
        if (!empty($order['proof_of_payment'])) {
            echo "<p><strong>Bukti Pembayaran:</strong></p>";
            echo "<img src='" . htmlspecialchars($order['proof_of_payment']) . "' alt='Bukti Pembayaran' style='max-width: 300px;'>";
        }
    }
} else {
    echo "<p>Data order tidak ditemukan.</p>";
}
?>
