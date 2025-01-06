<?php
require 'db.php'; // Sambungkan ke database Anda
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Pastikan PHPMailer telah terpasang melalui Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Generate token unik
    $expiry = time() + 3600; // Token berlaku selama 1 jam

    // Simpan token di database
    $query = "UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sis', $token, $expiry, $email);
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Kirim email reset menggunakan PHPMailer
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $mail = new PHPMailer(true);

        try {
            // Konfigurasi SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gunakan server SMTP sesuai penyedia email Anda
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // Email pengirim
            $mail->Password = 'your_email_password'; // Password aplikasi email Anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Informasi pengirim dan penerima
            $mail->setFrom('your_email@gmail.com', 'LeBookle');
            $mail->addAddress($email);

            // Konten email
            $mail->isHTML(true);
            $mail->Subject = 'Reset Kata Sandi Anda';
            $mail->Body = "Klik tautan ini untuk reset kata sandi Anda: <a href=\"$resetLink\">$resetLink</a>";

            $mail->send();
            echo "Tautan reset telah dikirim ke email Anda.";
        } catch (Exception $e) {
            echo "Gagal mengirim email: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email tidak ditemukan.";
    }
}
?>
