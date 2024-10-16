<?php
// instructor/add_sub_chapter.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

if(isset($_POST['add_sub_chapter'])){
    $chapter_id = intval($_POST['chapter_id']);
    $sub_chapter_title = $conn->real_escape_string($_POST['sub_chapter_title']);
    $sub_chapter_content = $conn->real_escape_string($_POST['sub_chapter_content']); // Tambahkan ini

    // Insert ke tabel sub_chapters
    $stmt = $conn->prepare("INSERT INTO sub_chapters (chapter_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $chapter_id, $sub_chapter_title, $sub_chapter_content);
    if($stmt->execute()){
        $success = "Sub-bab berhasil ditambahkan.";
    } else {
        $error = "Terjadi kesalahan saat menambahkan sub-bab.";
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
    <title>Tambah Sub-Bab - Instruktur</title>
    <!-- Sertakan CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Tambah Sub-Bab</h2>
        <!-- Form untuk menambahkan sub-bab baru -->
        <form method="POST" action="add_sub_chapter.php">
            <input type="hidden" name="chapter_id" value="<?= $chapter_id ?>">
            <div class="mb-3">
                <label for="sub_chapter_title" class="form-label">Judul Sub-Bab</label>
                <input type="text" class="form-control" id="sub_chapter_title" name="sub_chapter_title" required>
            </div>
            <div class="mb-3">
                <label for="sub_chapter_content" class="form-label">Konten Sub-Bab</label>
                <textarea class="form-control" id="sub_chapter_content" name="sub_chapter_content" required></textarea>
                <script>
                    CKEDITOR.replace('sub_chapter_content');
                </script>
            </div>
            <button type="submit" name="add_sub_chapter" class="btn btn-primary">Tambahkan Sub-Bab</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>