<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Halaman Login | LeBookle</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
        <form action="register.php" method="POST">
            <h1>Buat Akun</h1><br>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-container">
                <input type="password" id="password-signup" name="password" placeholder="Kata Sandi" required>
                <span class="input-group-text" id="toggle-password-signup" title="Tampilkan Kata Sandi" style="cursor: pointer;">
                    <i class="fa fa-eye-slash" id="eye-icon-signup"></i>
                </span>
            </div>
            <button type="submit">Daftar</button>
        </form>
        </div>
        <div class="form-container sign-in">
        <form action="login.php" method="POST">
            <h1>Masuk</h1><br>
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-container">
                <input type="password" id="password-signin" name="password" placeholder="Kata Sandi" required>
                <span class="input-group-text" id="toggle-password-signin" title="Tampilkan Kata Sandi" style="cursor: pointer;">
                    <i class="fa fa-eye-slash" id="eye-icon-signin"></i>
                </span>
            </div>
            <a href="forgot_password.php">Lupa Kata Sandi?</a>
            <button type="submit">Masuk</button>
        </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Mulai Perjalanan Literasi Anda!</h1><br><br><br>
                    <p>Punya Akun? Login dan lihat kembali koleksi yang menanti Anda.</p>                   
                    <button class="hidden" id="login">Masuk</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Selamat Datang, Pembaca Setia!</h1><br><br><br>
                    <p>Baru di LeBookle? Daftar sekarang dan buka dunia penuh cerita menarik.</p>
                    <button class="hidden" id="register">Daftar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        // Toggle password visibility for signup form
        document.getElementById('toggle-password-signup').addEventListener('click', function () {
            const passwordField = document.getElementById('password-signup');
            const eyeIcon = document.getElementById('eye-icon-signup');
            const toggleButton = document.getElementById('toggle-password-signup');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
                toggleButton.title = "Sembunyikan Kata Sandi";
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
                toggleButton.title = "Tampilkan Kata Sandi";
            }
        });

        // Toggle password visibility for signin form
        document.getElementById('toggle-password-signin').addEventListener('click', function () {
            const passwordField = document.getElementById('password-signin');
            const eyeIcon = document.getElementById('eye-icon-signin');
            const toggleButton = document.getElementById('toggle-password-signin');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
                toggleButton.title = "Sembunyikan Kata Sandi";
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
                toggleButton.title = "Tampilkan Kata Sandi";
            }
        });
    </script>
</body>

</html>