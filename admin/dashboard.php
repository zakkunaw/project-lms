<?php
// admin/dashboard.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

// Analisis jumlah akun
$sql_admin = "SELECT COUNT(*) as total_admin FROM users WHERE role='admin'";
$sql_instructor = "SELECT COUNT(*) as total_instructor FROM users WHERE role='instructor'";
$sql_student = "SELECT COUNT(*) as total_student FROM users WHERE role='student'";

$result_admin = $conn->query($sql_admin)->fetch_assoc();
$result_instructor = $conn->query($sql_instructor)->fetch_assoc();
$result_student = $conn->query($sql_student)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Admin Dashboard - LMS</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?> <!-- Buat navbar jika perlu -->
    <div class="container mt-5">
        <h1>Selamat Datang, Admin</h1>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Admin</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $result_admin['total_admin'] ?></h5>
                        <p class="card-text">Jumlah Admin</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Instruktur</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $result_instructor['total_instructor'] ?></h5>
                        <p class="card-text">Jumlah Instruktur</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Siswa</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $result_student['total_student'] ?></h5>
                        <p class="card-text">Jumlah Siswa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambahkan fitur lain seperti manage_users, blokir akun, dll -->
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
