<?php
// instructor/add_chapter.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

if(isset($_POST['add_chapter'])){
    $course_id = intval($_POST['course_id']);
    $chapter_title = $conn->real_escape_string($_POST['chapter_title']);
    $chapter_content = $conn->real_escape_string($_POST['chapter_content']); // Tambahkan ini

    // Tentukan nomor bab berikutnya
    $stmt_order = $conn->prepare("SELECT COUNT(*) as total FROM chapters WHERE course_id = ?");
    $stmt_order->bind_param("i", $course_id);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result()->fetch_assoc();
    $chapter_number = $result_order['total'] + 1;
    $stmt_order->close();

    // Insert ke tabel chapters
    $stmt = $conn->prepare("INSERT INTO chapters (course_id, title, content, chapter_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $course_id, $chapter_title, $chapter_content, $chapter_number);
    if($stmt->execute()){
        $success = "Bab berhasil ditambahkan.";
    } else {
        $error = "Terjadi kesalahan saat menambahkan bab.";
    }
    $stmt->close();
    header("Location: manage_courses.php"); // Kembali ke manage_courses.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Tambah Bab - Instruktur</title>
    <!-- Sertakan CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Tambah Bab</h2>
        <!-- Form untuk menambahkan bab baru -->
        <form method="POST" action="add_chapter.php">
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <div class="mb-3">
                <label for="chapter_title" class="form-label">Judul Bab</label>
                <input type="text" class="form-control" id="chapter_title" name="chapter_title" required>
            </div>
            <div class="mb-3">
                <label for="chapter_content" class="form-label">Konten Bab</label>
                <textarea class="form-control" id="chapter_content" name="chapter_content" required></textarea>
                <script>
                    CKEDITOR.replace('chapter_content');
                </script>
            </div>
            <button type="submit" name="add_chapter" class="btn btn-primary">Tambahkan Bab</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>