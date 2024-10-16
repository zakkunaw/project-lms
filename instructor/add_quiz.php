<?php
// instructor/add_quiz.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

if(isset($_GET['sub_chapter_id'])){
    $sub_chapter_id = intval($_GET['sub_chapter_id']);
} else {
    header("Location: manage_courses.php");
    exit();
}

// Handle pengiriman kuis
if(isset($_POST['add_quiz'])){
    $question = $conn->real_escape_string($_POST['question']);
    $option_a = $conn->real_escape_string($_POST['option_a']);
    $option_b = $conn->real_escape_string($_POST['option_b']);
    $option_c = $conn->real_escape_string($_POST['option_c']);
    $option_d = $conn->real_escape_string($_POST['option_d']);
    $correct_option = $_POST['correct_option'];

    // Insert ke tabel quizzes
    $stmt = $conn->prepare("INSERT INTO quizzes (sub_chapter_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $sub_chapter_id, $question, $option_a, $option_b, $option_c, $option_d, $correct_option);
    if($stmt->execute()){
        $success = "Quiz berhasil ditambahkan.";
    } else {
        $error = "Terjadi kesalahan saat menambahkan quiz.";
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
    <title>Tambah Quiz - Instruktur</title>
    <!-- Sertakan CKEditor jika diperlukan -->
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Tambah Quiz untuk Sub-Bab</h2>
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="add_quiz.php?sub_chapter_id=<?= $sub_chapter_id ?>">
            <div class="mb-3">
                <label for="question" class="form-label">Pertanyaan</label>
                <textarea class="form-control" id="question" name="question" required></textarea>
                <script>
                    CKEDITOR.replace('question');
                </script>
            </div>
            <div class="mb-3">
                <label for="option_a" class="form-label">Opsi A</label>
                <input type="text" class="form-control" id="option_a" name="option_a" required>
            </div>
            <div class="mb-3">
                <label for="option_b" class="form-label">Opsi B</label>
                <input type="text" class="form-control" id="option_b" name="option_b" required>
            </div>
            <div class="mb-3">
                <label for="option_c" class="form-label">Opsi C</label>
                <input type="text" class="form-control" id="option_c" name="option_c" required>
            </div>
            <div class="mb-3">
                <label for="option_d" class="form-label">Opsi D</label>
                <input type="text" class="form-control" id="option_d" name="option_d" required>
            </div>
            <div class="mb-3">
                <label for="correct_option" class="form-label">Opsi yang Benar</label>
                <select class="form-select" id="correct_option" name="correct_option" required>
                    <option value="">Pilih Opsi yang Benar</option>
                    <option value="a">Opsi A</option>
                    <option value="b">Opsi B</option>
                    <option value="c">Opsi C</option>
                    <option value="d">Opsi D</option>
                </select>
            </div>
            <button type="submit" name="add_quiz" class="btn btn-primary">Tambahkan Quiz</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
