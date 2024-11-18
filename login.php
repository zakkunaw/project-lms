<?php
session_start();
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND is_blocked=0";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Store role for redirection
            $_SESSION['redirect_role'] = $user['role'];
            $_SESSION['login_success'] = true; // Indicate successful login

            // Redirect to the same page to trigger JavaScript alert
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan atau akun diblokir.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - LMS</title>
       <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const passwordToggleIcon = document.getElementById('password-toggle-icon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordToggleIcon.classList.remove('fa-eye');
                passwordToggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordToggleIcon.classList.remove('fa-eye-slash');
                passwordToggleIcon.classList.add('fa-eye');
            }
        }

        function openWhatsApp() {
            const phoneNumber = "628123456789"; // Ganti dengan nomor WhatsApp yang diinginkan
            const message = "saya ingin daftar untuk mengakses materi LMS Hatese :)";
            const url = `https://api.whatsapp.com/send?phone=${phoneNumber}&text=${encodeURIComponent(message)}`;
            window.open(url, "_blank");
        }

        // Check if the login was successful
        document.addEventListener('DOMContentLoaded', function () {
            const loginSuccess = <?= isset($_SESSION['login_success']) ? 'true' : 'false'; ?>;
            const redirectRole = '<?= isset($_SESSION['redirect_role']) ? $_SESSION['redirect_role'] : ''; ?>';

            if (loginSuccess) {
                Swal.fire({
                    title: 'Login Berhasil!',
                    text: 'Selamat datang di LMS Hatese.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect based on user role after alert is closed
                    if (redirectRole === 'admin') {
                        window.location.href = 'admin/dashboard.php';
                    } else if (redirectRole === 'instructor') {
                        window.location.href = 'instructor/dashboard.php';
                    } else if (redirectRole === 'student') {
                        window.location.href = 'student/dashboard.php';
                    }
                });

                // Clear the session variables
                <?php unset($_SESSION['login_success'], $_SESSION['redirect_role']); ?>
            }
        });
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="container mx-auto flex items-center justify-center min-h-screen">
        <div class="bg-white p-8 rounded-lg w-full max-w-md mx-auto border border-gray-300">
            <div class="flex justify-center mb-4">
                <img alt="Hatese logo" class="h-22" height="100" src="hateselogo.png" width="100"/>
            </div>
            <h2 class="text-2xl font-bold text-center mb-2">Login ke Hatese</h2>
            <p class="text-center text-gray-600 mb-4">Yuk, lanjutin belajar kamu di Hatese.</p>
            <div class="flex justify-center mb-4">
                <button onclick="openWhatsApp()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg flex items-center">
                    <i class="fab fa-whatsapp text-green-500 text-2xl mr-2"></i>
                    <div class="text-left">
                        <p class="font-bold">Daftar Sekarang</p>
                        <p class="text-sm text-gray-500">Chat on Whatsapp!</p>
                    </div>
                </button>
            </div>
            <div class="flex items-center mb-4">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="mx-4 text-gray-500">atau</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-red-500 text-center mb-4"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="username">Username<span class="text-red-500">*</span></label>
                    <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="username" name="username" required type="text"/>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-gray-700 font-bold mb-2" for="password">Password<span class="text-red-500">*</span></label>
                    <input class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" name="password" required type="password"/>
                    <i class="fas fa-eye absolute right-3 top-10 cursor-pointer" id="password-toggle-icon" onclick="togglePasswordVisibility()"></i>
                </div>
                <button class="w-full bg-blue-600 text-white py-2 rounded-lg font-bold" type="submit">Masuk</button>
            </form>
            <p class="text-center text-gray-600 mt-4"><a class="text-blue-600 font-bold" href="index.php">Kmbali ke halaman utama? </a></p>
        </div>
    </div>
</body>
</html>
