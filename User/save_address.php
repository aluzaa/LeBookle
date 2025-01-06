<?php
// Mulai sesi
session_start();

// Include koneksi database
require_once 'db.php'; // Pastikan path sesuai dengan lokasi file db_connection.php

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: /LeBookle/Auth/index.php"); // Arahkan ke halaman login jika belum login
    exit;
}

// Ambil data yang dikirimkan melalui form
$user_id = $_SESSION['user_id']; // ID user yang login
$fullname = trim($_POST['fullname']);
$address = trim($_POST['address']);
$city = trim($_POST['city']);
$state = $_POST['state']; // Provinsi
$postal_code = trim($_POST['postal_code']);
$country = trim($_POST['country']);
$phone = trim($_POST['phone']);

// Validasi data (pastikan tidak kosong)
if (empty($fullname) || empty($address) || empty($city) || empty($state) || empty($postal_code) || empty($country) || empty($phone)) {
    $_SESSION['error'] = "Semua data harus diisi.";
    header("Location: /LeBookle/User/my-account.php"); // Arahkan kembali ke halaman akun
    exit;
}

// Jika data sudah valid, simpan ke database
try {
    // Cek apakah user sudah punya data alamat
    $stmt = $pdo->prepare("SELECT * FROM customer WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    $customer = $stmt->fetch();

    if ($customer) {
        // Jika sudah ada, update data alamat
        $stmt = $pdo->prepare("UPDATE customer SET fullname = :fullname, address = :address, city = :city, state = :state, postal_code = :postal_code, country = :country, phone = :phone WHERE user_id = :user_id");
        $stmt->execute([
            'fullname' => $fullname,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'postal_code' => $postal_code,
            'country' => $country,
            'phone' => $phone,
            'user_id' => $user_id
        ]);
    } else {
        // Jika belum ada, insert data baru
        $stmt = $pdo->prepare("INSERT INTO customer (user_id, fullname, address, city, state, postal_code, country, phone) VALUES (:user_id, :fullname, :address, :city, :state, :postal_code, :country, :phone)");
        $stmt->execute([
            'user_id' => $user_id,
            'fullname' => $fullname,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'postal_code' => $postal_code,
            'country' => $country,
            'phone' => $phone
        ]);
    }

    $_SESSION['success'] = "Alamat berhasil disimpan.";
    header("Location: /LeBookle/User/my-account.php"); // Arahkan kembali ke halaman akun
    exit;
} catch (PDOException $e) {
    // Jika terjadi kesalahan, tampilkan pesan error
    $_SESSION['error'] = "Terjadi kesalahan saat menyimpan data: " . $e->getMessage();
    header("Location: /LeBookle/User/my-account.php"); // Arahkan kembali ke halaman akun
    exit;
}
?>
