<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.php");
    exit();
}

require_once '../includes/db_connect.php';

// Handle delete quiz request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $response = ['status' => 'error', 'message' => ''];
    
    $quiz_id = intval($_POST['quiz_id']);
    $stmt = $conn->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->bind_param("i", $quiz_id);
    
    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Quiz berhasil dihapus'];
    }
    $stmt->close();
    
    echo json_encode($response);
    exit;
}

// Get chapter and quizzes
if (isset($_GET['chapter_id'])) {
    $chapter_id = $_GET['chapter_id'];

    $stmt_chapter = $conn->prepare("SELECT * FROM chapters WHERE id = ?");
    $stmt_chapter->bind_param("i", $chapter_id);
    $stmt_chapter->execute();
    $chapter = $stmt_chapter->get_result()->fetch_assoc();
    $stmt_chapter->close();

    if (!$chapter) {
        echo "Chapter tidak ditemukan.";
        exit();
    }

    $stmt_quizzes = $conn->prepare("SELECT * FROM quizzes WHERE chapter_id = ?");
    $stmt_quizzes->bind_param("i", $chapter_id);
    $stmt_quizzes->execute();
    $quizzes = $stmt_quizzes->get_result();
    $stmt_quizzes->close();
} else {
    header("Location: manage_courses.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Lihat Quiz Bab <?= htmlspecialchars($chapter['chapter_number']) ?></title>
    <script src="../assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
    
    <style>
        .quiz-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .quiz-question {
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }
        .quiz-options {
            margin-left: 20px;
        }
        .option-item {
            margin: 10px 0;
            padding: 8px;
            background: white;
            border-radius: 4px;
        }
        .correct-answer {
            color: #28a745;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <?php include '../includes/instruktur/navbar.php'; ?>
        
        <div class="container-fluid mt-5">
            <h2>Quiz untuk Bab <?= htmlspecialchars($chapter['chapter_number']) ?>: <?= htmlspecialchars($chapter['title']) ?></h2>

            <?php if ($quizzes->num_rows > 0): ?>
                <div class="quiz-container mt-4">
                    <?php $questionNumber = 1; ?>
                    <?php while ($quiz = $quizzes->fetch_assoc()): ?>
                        <div class="quiz-item" id="quiz-<?= $quiz['id'] ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>Pertanyaan <?= $questionNumber ?></h5>
                                <button class="btn btn-sm btn-danger delete-quiz" data-quiz-id="<?= $quiz['id'] ?>">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                            <div class="quiz-question tinymce-content">
                                <?= $quiz['question'] ?>
                            </div>
                            <div class="quiz-options">
                                <?php foreach (['a', 'b', 'c', 'd'] as $option): ?>
                                    <div class="option-item <?= $quiz['correct_option'] == $option ? 'correct-answer' : '' ?>">
                                        <strong><?= strtoupper($option) ?>:</strong> <?= htmlspecialchars($quiz["option_$option"]) ?>
                                        <?= $quiz['correct_option'] == $option ? ' <span class="badge bg-success">Jawaban Benar</span>' : '' ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $questionNumber++; ?>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info mt-3">
                    Belum ada quiz yang ditambahkan untuk bab ini.
                </div>
            <?php endif; ?>

            <div class="mt-4 mb-5">
                <a href="manage_courses.php" class="btn btn-secondary">Kembali ke Daftar Bab</a>
                <a href="add_quiz.php?chapter_id=<?= $chapter_id ?>" class="btn btn-primary">Tambah Quiz Baru</a>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        tinymce.init({
            selector: '.tinymce-content',
            height: 'auto',
            menubar: false,
            toolbar: false,
            readonly: true
        });

        $(document).ready(function() {
            $('.delete-quiz').click(function() {
                if (confirm('Apakah Anda yakin ingin menghapus quiz ini?')) {
                    const quizId = $(this).data('quiz-id');
                    
                    $.ajax({
                        url: window.location.href,
                        type: 'POST',
                        data: {
                            action: 'delete',
                            quiz_id: quizId
                        },
                        success: function(response) {
                            const result = JSON.parse(response);
                            if (result.status === 'success') {
                                $(`#quiz-${quizId}`).fadeOut(300, function() {
                                    $(this).remove();
                                });
                            } else {
                                alert('Terjadi kesalahan saat menghapus quiz');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan sistem');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
