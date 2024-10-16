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
    if(isset($_POST['quiz_id']) && isset($_POST['answer']) && isset($_POST['course_id']) && isset($_POST['sub_chapter_id'])){
        $quiz_id = intval($_POST['quiz_id']);
        $answer = $_POST['answer'];
        $course_id = intval($_POST['course_id']);
        $sub_chapter_id = intval($_POST['sub_chapter_id']);

        // Ambil jawaban yang benar dari database
        $sql_quiz = "SELECT correct_option FROM quizzes WHERE id = ?";
        $stmt_quiz = $conn->prepare($sql_quiz);
        $stmt_quiz->bind_param("i", $quiz_id);
        $stmt_quiz->execute();
        $quiz = $stmt_quiz->get_result()->fetch_assoc();
        $stmt_quiz->close();

        if(!$quiz){
            // Kuis tidak ditemukan
            $_SESSION['quiz_message'] = "Kuis tidak ditemukan.";
            $_SESSION['quiz_message_type'] = "danger";
            header("Location: course_content.php?course_id=$course_id&sub_chapter_id=$sub_chapter_id");
            exit();
        }

        $is_passed = ($answer == $quiz['correct_option']) ? 1 : 0;

        // Cek apakah siswa sudah pernah mengerjakan kuis ini
        $sql_check = "SELECT * FROM quiz_completions WHERE student_id = ? AND quiz_id = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $student_id, $quiz_id);
        $stmt_check->execute();
        $check_result = $stmt_check->get_result();
        $stmt_check->close();

        if($check_result->num_rows > 0){
            // Siswa sudah pernah mengerjakan kuis ini, update hasilnya
            $sql_update = "UPDATE quiz_completions SET is_passed = ? WHERE student_id = ? AND quiz_id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("iii", $is_passed, $student_id, $quiz_id);
            if($stmt_update->execute()){
                if($is_passed){
                    $_SESSION['quiz_message'] = "Kuis berhasil dijawab dengan benar!";
                    $_SESSION['quiz_message_type'] = "success";
                } else {
                    $_SESSION['quiz_message'] = "Kuis dijawab salah. Anda tidak dapat melanjutkan.";
                    $_SESSION['quiz_message_type'] = "danger";
                }
            } else {
                $_SESSION['quiz_message'] = "Terjadi kesalahan saat memperbarui jawaban kuis.";
                $_SESSION['quiz_message_type'] = "danger";
            }
            $stmt_update->close();
        } else {
            // Insert hasil kuis
            $sql_insert = "INSERT INTO quiz_completions (student_id, quiz_id, is_passed) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("iii", $student_id, $quiz_id, $is_passed);
            if($stmt_insert->execute()){
                if($is_passed){
                    $_SESSION['quiz_message'] = "Kuis berhasil dijawab dengan benar!";
                    $_SESSION['quiz_message_type'] = "success";
                } else {
                    $_SESSION['quiz_message'] = "Kuis dijawab salah. Anda tidak dapat melanjutkan.";
                    $_SESSION['quiz_message_type'] = "danger";
                }
            } else {
                $_SESSION['quiz_message'] = "Terjadi kesalahan saat menyimpan jawaban kuis.";
                $_SESSION['quiz_message_type'] = "danger";
            }
            $stmt_insert->close();
        }

        // Redirect kembali ke course_content.php
        header("Location: course_content.php?course_id=$course_id&sub_chapter_id=$sub_chapter_id");
        exit();
    }
}

// Jika tidak ada data POST, redirect ke my_courses.php
header("Location: my_courses.php");
exit();
?>
