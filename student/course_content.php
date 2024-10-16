<?php
// student/course_content.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];

// Validasi parameter
if(!isset($_GET['course_id']) || !isset($_GET['sub_chapter_id'])){
    header("Location: my_courses.php");
    exit();
}

$course_id = intval($_GET['course_id']);
$sub_chapter_id = intval($_GET['sub_chapter_id']);

// Cek apakah siswa telah terdaftar di kursus ini
$sql_enroll = "SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?";
$stmt_enroll = $conn->prepare($sql_enroll);
$stmt_enroll->bind_param("ii", $student_id, $course_id);
$stmt_enroll->execute();
$enroll_result = $stmt_enroll->get_result();
if($enroll_result->num_rows == 0){
    // Siswa tidak terdaftar di kursus ini
    header("Location: my_courses.php");
    exit();
}
$stmt_enroll->close();

// Ambil informasi sub-bab
$sql_sub_chapter = "SELECT sc.*, c.title as chapter_title, c.chapter_number 
                    FROM sub_chapters sc 
                    JOIN chapters c ON sc.chapter_id = c.id 
                    WHERE sc.id = ?";
$stmt_sub = $conn->prepare($sql_sub_chapter);
$stmt_sub->bind_param("i", $sub_chapter_id);
$stmt_sub->execute();
$sub_chapter = $stmt_sub->get_result()->fetch_assoc();
$stmt_sub->close();

if(!$sub_chapter){
    // Sub-bab tidak ditemukan
    header("Location: my_courses.php");
    exit();
}

// Ambil kuis jika ada
$sql_quiz = "SELECT * FROM quizzes WHERE sub_chapter_id = ?";
$stmt_quiz = $conn->prepare($sql_quiz);
$stmt_quiz->bind_param("i", $sub_chapter_id);
$stmt_quiz->execute();
$quiz = $stmt_quiz->get_result()->fetch_assoc();
$stmt_quiz->close();

// Cek status kuis
if($quiz){
    $sql_quiz_completion = "SELECT * FROM quiz_completions WHERE student_id = ? AND quiz_id = ?";
    $stmt_qc = $conn->prepare($sql_quiz_completion);
    $stmt_qc->bind_param("ii", $student_id, $quiz['id']);
    $stmt_qc->execute();
    $qc_result = $stmt_qc->get_result();
    $has_completed = $qc_result->num_rows > 0;
    $qc = $qc_result->fetch_assoc();
    $stmt_qc->close();

    if($has_completed && $qc['is_passed']){
        $can_view_content = true;
    } else {
        $can_view_content = false;
    }
} else {
    $can_view_content = true;
}

// Tampilkan pesan jika ada
if(isset($_SESSION['quiz_message'])){
    $quiz_message = $_SESSION['quiz_message'];
    $quiz_message_type = isset($_SESSION['quiz_message_type']) ? $_SESSION['quiz_message_type'] : 'info';
    unset($_SESSION['quiz_message']);
    unset($_SESSION['quiz_message_type']);
} else {
    $quiz_message = "";
    $quiz_message_type = "";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title><?= htmlspecialchars($sub_chapter['title']) ?> - LMS</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2><?= htmlspecialchars($sub_chapter['title']) ?></h2>
        <p><strong>Bab <?= $sub_chapter['chapter_number'] ?>: <?= htmlspecialchars($sub_chapter['chapter_title']) ?></strong></p>

        <!-- Tampilkan konten sub-bab -->
        <div class="mb-4">
            <?= $sub_chapter['content'] ?>
        </div>

        <!-- Notifikasi berdasarkan status kuis -->
        <?php if(!empty($quiz_message)): ?>
            <div class="alert alert-<?= htmlspecialchars($quiz_message_type) ?>"><?= htmlspecialchars($quiz_message) ?></div>
        <?php endif; ?>

        <?php if($quiz): ?>
            <?php if(!$has_completed || !$qc['is_passed']): ?>
                <!-- Form Kuis -->
                <h4>Quiz</h4>
                <form method="POST" action="submit_quiz.php">
                    <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                    <input type="hidden" name="course_id" value="<?= $course_id ?>">
                    <input type="hidden" name="sub_chapter_id" value="<?= $sub_chapter_id ?>">
                    <div class="mb-3">
                        <label class="form-label"><strong><?= htmlspecialchars($quiz['question']) ?></strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optionA" value="a" required>
                            <label class="form-check-label" for="optionA">
                                A. <?= htmlspecialchars($quiz['option_a']) ?>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optionB" value="b" required>
                            <label class="form-check-label" for="optionB">
                                B. <?= htmlspecialchars($quiz['option_b']) ?>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optionC" value="c" required>
                            <label class="form-check-label" for="optionC">
                                C. <?= htmlspecialchars($quiz['option_c']) ?>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="answer" id="optionD" value="d" required>
                            <label class="form-check-label" for="optionD">
                                D. <?= htmlspecialchars($quiz['option_d']) ?>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
                </form>
            <?php elseif($qc['is_passed']): ?>
                <div class="alert alert-success">Anda telah menyelesaikan kuis ini dengan baik. Silakan lanjut ke chapter berikutnya.</div>
                <a href="next_chapter.php?course_id=<?= $course_id ?>&current_sub_chapter_id=<?= $sub_chapter_id ?>" class="btn btn-success">Lanjut ke Chapter Berikutnya</a>
            <?php else: ?>
                <div class="alert alert-danger">Anda belum memenuhi kriteria untuk melanjutkan.</div>
                <button class="btn btn-secondary" disabled>Lanjut ke Chapter Berikutnya</button>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
