<?php
// Koneksi ke database
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];

    try {
        // Cek apakah informasi sudah ada
        $stmt = $pdo->prepare("SELECT * FROM customer_details WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            // Update jika sudah ada
            $stmt = $pdo->prepare("UPDATE customer_details SET 
                fullname = :fullname, 
                phone = :phone, 
                address = :address, 
                city = :city, 
                postal_code = :postal_code 
                WHERE user_id = :user_id");
        } else {
            // Insert jika belum ada
            $stmt = $pdo->prepare("INSERT INTO customer_details 
                (user_id, fullname, phone, address, city, postal_code) 
                VALUES (:user_id, :fullname, :phone, :address, :city, :postal_code)");
        }

        // Bind dan eksekusi
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':city', $city, PDO::PARAM_STR);
        $stmt->bindParam(':postal_code', $postal_code, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: my_account.php?success=1");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
