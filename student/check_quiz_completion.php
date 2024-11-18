<?php
session_start();
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];
$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;

if ($course_id === 0) {
    echo json_encode([]);
    exit();
}

// Fetch chapters and quiz completion status for each chapter
$sql = "
    SELECT c.chapter_number, 
           CASE WHEN qc.is_passed = 1 THEN 1 ELSE 0 END as is_passed
    FROM chapters c
    LEFT JOIN quizzes q ON c.id = q.chapter_id
    LEFT JOIN quiz_completions qc ON q.id = qc.quiz_id AND qc.student_id = ?
    WHERE c.course_id = ?
    GROUP BY c.chapter_number
    ORDER BY c.chapter_number ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $course_id);
$stmt->execute();
$result = $stmt->get_result();

$completion_status = [];
$previous_passed = true;

while ($row = $result->fetch_assoc()) {
    $chapter_number = $row['chapter_number'];
    $is_passed = $row['is_passed'];

    // A chapter is accessible if the previous chapter's quiz is passed or there is no quiz
    if ($previous_passed) {
        $completion_status[$chapter_number] = true;
    } else {
        $completion_status[$chapter_number] = false;
    }

    // Update previous_passed for the next chapter
    $previous_passed = $is_passed ? true : false;
}

echo json_encode($completion_status);
