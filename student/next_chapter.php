<?php
// student/next_chapter.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];

// Validasi parameter
if(!isset($_GET['course_id']) || !isset($_GET['current_sub_chapter_id'])){
    header("Location: my_courses.php");
    exit();
}

$course_id = intval($_GET['course_id']);
$current_sub_chapter_id = intval($_GET['current_sub_chapter_id']);

// Cari sub-bab berikutnya dalam bab yang sama
$sql_next_sub = "
    SELECT sc.id 
    FROM sub_chapters sc
    JOIN chapters c ON sc.chapter_id = c.id
    WHERE sc.chapter_id = (
        SELECT chapter_id FROM sub_chapters WHERE id = ?
    ) AND sc.id > ?
    ORDER BY sc.id ASC
    LIMIT 1
";
$stmt_next_sub = $conn->prepare($sql_next_sub);
$stmt_next_sub->bind_param("ii", $current_sub_chapter_id, $current_sub_chapter_id);
$stmt_next_sub->execute();
$next_sub = $stmt_next_sub->get_result()->fetch_assoc();
$stmt_next_sub->close();

if($next_sub){
    // Redirect ke sub-bab berikutnya
    header("Location: course_content.php?course_id=$course_id&sub_chapter_id=".$next_sub['id']);
    exit();
} else {
    // Jika tidak ada sub-bab berikutnya dalam bab yang sama, cari bab berikutnya
    $sql_next_chapter = "
        SELECT c.id 
        FROM chapters c
        WHERE c.course_id = ?
        AND c.chapter_number > (
            SELECT c2.chapter_number FROM chapters c2 
            JOIN sub_chapters sc2 ON c2.id = sc2.chapter_id 
            WHERE sc2.id = ?
        )
        ORDER BY c.chapter_number ASC
        LIMIT 1
    ";
    $stmt_next_chapter = $conn->prepare($sql_next_chapter);
    $stmt_next_chapter->bind_param("ii", $course_id, $current_sub_chapter_id);
    $stmt_next_chapter->execute();
    $next_chapter = $stmt_next_chapter->get_result()->fetch_assoc();
    $stmt_next_chapter->close();

    if($next_chapter){
        // Redirect ke bab berikutnya, sub-bab pertama
        $sql_first_sub = "SELECT id FROM sub_chapters WHERE chapter_id = ? ORDER BY id ASC LIMIT 1";
        $stmt_first_sub = $conn->prepare($sql_first_sub);
        $stmt_first_sub->bind_param("i", $next_chapter['id']);
        $stmt_first_sub->execute();
        $first_sub = $stmt_first_sub->get_result()->fetch_assoc();
        $stmt_first_sub->close();

        if($first_sub){
            header("Location: course_content.php?course_id=$course_id&sub_chapter_id=".$first_sub['id']);
            exit();
        } else {
            // Tidak ada sub-bab di bab berikutnya
            $_SESSION['quiz_message'] = "Anda telah menyelesaikan semua bab dalam kursus ini.";
            $_SESSION['quiz_message_type'] = "success";
            header("Location: my_courses.php");
            exit();
        }
    } else {
        // Tidak ada bab berikutnya
        $_SESSION['quiz_message'] = "Anda telah menyelesaikan semua bab dalam kursus ini.";
        $_SESSION['quiz_message_type'] = "success";
        header("Location: my_courses.php");
        exit();
    }

}