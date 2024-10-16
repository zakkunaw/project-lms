<?php
// courses/course.php
session_start();
require_once '../includes/db_connect.php';

if(!isset($_GET['id'])){
    header("Location: ../index.php");
    exit();
}

$course_id = intval($_GET['id']);

// Cek apakah pengguna adalah tamu atau sudah login
$is_guest = false;
if(!isset($_SESSION['user_id'])){
    if(isset($_SESSION['guest_email']) && isset($_SESSION['guest_phone'])){
        $is_guest = true;
    } else {
        header("Location: ../index.php");
        exit();
    }
}

// Fetch kursus
$sql_course = "SELECT * FROM courses WHERE id=$course_id";
$course = $conn->query($sql_course)->fetch_assoc();

// Fetch bab kursus
if($is_guest){
    $limit = intval($_GET['limit'] ?? 5);
    $sql_chapters = "SELECT * FROM chapters WHERE course_id=$course_id ORDER BY chapter_number ASC LIMIT $limit";
} else {
    // Jika siswa login, bisa mengakses sesuai izin
    // Implementasikan logika sesuai kebutuhan
    $sql_chapters = "SELECT * FROM chapters WHERE course_id=$course_id ORDER BY chapter_number ASC";
}

$chapters = $conn->query($sql_chapters);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title><?= htmlspecialchars($course['title']) ?> - LMS</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2><?= htmlspecialchars($course['title']) ?></h2>
        <p><?= htmlspecialchars($course['description']) ?></p>
        <h4>Daftar Bab</h4>
        <ul class="list-group">
            <?php while($chapter = $chapters->fetch_assoc()): ?>
                <li class="list-group-item">
                    <?= htmlspecialchars($chapter['chapter_number']) ?>. <?= htmlspecialchars($chapter['title']) ?>
                </li>
            <?php endwhile; ?>
            <?php if($is_guest && $limit < $conn->query("SELECT COUNT(*) as total FROM chapters WHERE course_id=$course_id")->fetch_assoc()['total']): ?>
                <li class="list-group-item text-muted">Bab selanjutnya terkunci.</li>
            <?php endif; ?>
        </ul>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
