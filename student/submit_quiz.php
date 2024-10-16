<?php
// student/submit_quiz.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['quiz_id']) && isset($_POST['answer'])){
        $quiz_id = intval($_POST['quiz_id']);
        $answer = $_POST['answer'];

        // Ambil jawaban yang benar dari database
        $sql_quiz = "SELECT correct_option FROM quizzes WHERE id = ?";
        $stmt_quiz = $conn->prepare($sql_quiz);
        $stmt_quiz->bind_param("i", $quiz_id);
        $stmt_quiz->execute();
        $quiz = $stmt_quiz->get_result()->fetch_assoc();
        $stmt_quiz->close();

        if(!$quiz){
            // Kuis tidak ditemukan
            header("Location: my_courses.php");
            exit();
        }

        $is_passed = ($answer == $quiz['correct_option']) ? 1 : 0;

        // Simpan hasil kuis
        $sql_insert = "INSERT INTO quiz_completions (student_id, quiz_id, is_passed) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $student_id, $quiz_id, $is_passed);
        if($stmt_insert->execute()){
            if($is_passed){
                // Redirect kembali ke course_content dengan pesan sukses
                $_SESSION['quiz_message'] = "Kuis berhasil dijawab dengan benar!";
            } else {
                $_SESSION['quiz_message'] = "Kuis dijawab salah. Anda tidak dapat melanjutkan.";
            }
        } else {
            $_SESSION['quiz_message'] = "Terjadi kesalahan saat menyimpan jawaban kuis.";
        }
        $stmt_insert->close();

        // Redirect kembali ke course_content.php
        // Anda perlu menyimpan course_id dan sub_chapter_id di session atau mengirimkannya kembali
        // Misalnya, simpan dalam session sementara
        $_SESSION['quiz_result'] = $is_passed;
        header("Location: course_content.php?course_id=" . intval($_GET['course_id']) . "&sub_chapter_id=" . intval($_GET['sub_chapter_id']));
        exit();
    }
}

// Jika tidak ada data POST, redirect ke my_courses.php
header("Location: my_courses.php");
exit();
?>
