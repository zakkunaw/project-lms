<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];
$item_type = $_POST['item_type'] ?? '';
$item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
$chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;

if ($item_id === 0 || $chapter_id === 0 || $course_id === 0 || !in_array($item_type, ['sub_chapter', 'quiz'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit();
}

// Calculate new progress
// $sql_total_items = "SELECT 
//     (SELECT COUNT(*) FROM sub_chapters WHERE chapter_id IN (SELECT id FROM chapters WHERE course_id = ?)) +
//     (SELECT COUNT(*) FROM quizzes WHERE chapter_id IN (SELECT id FROM chapters WHERE course_id = ?)) as total";
// $stmt_total = $conn->prepare($sql_total_items);
// $stmt_total->bind_param("ii", $course_id, $course_id);
// $stmt_total->execute();
// $total_items = $stmt_total->get_result()->fetch_assoc()['total'];
// $stmt_total->close();

// $sql_completed_items = "SELECT COUNT(*) as completed FROM student_progress 
//                         WHERE student_id = ? AND course_id = ? AND completed = 1";
// $stmt_completed = $conn->prepare($sql_completed_items);
// $stmt_completed->bind_param("ii", $student_id, $course_id);
// $stmt_completed->execute();
// $completed_items = $stmt_completed->get_result()->fetch_assoc()['completed'];
// $stmt_completed->close();

// $progress_percentage = ($total_items > 0) ? round(($completed_items / $total_items) * 100) : 0;

// echo json_encode(['success' => true, 'progress' => $progress_percentage]);


// Check if the record already exists
$sql_check = "SELECT id FROM student_progress WHERE student_id = ? AND course_id = ? AND chapter_id = ? AND {$item_type}_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("iiii", $student_id, $course_id, $chapter_id, $item_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
$existing_record = $result->fetch_assoc();
$stmt_check->close();

if ($existing_record) {
    // Update existing record
    $sql_update = "UPDATE student_progress SET completed = 1, completed_at = NOW() WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $existing_record['id']);
} else {
    // Insert new record
    $sql_insert = "INSERT INTO student_progress (student_id, course_id, chapter_id, {$item_type}_id, completed, completed_at) 
                   VALUES (?, ?, ?, ?, 1, NOW())";
    $stmt_update = $conn->prepare($sql_insert);
    $stmt_update->bind_param("iiii", $student_id, $course_id, $chapter_id, $item_id);
}

$stmt_update->execute();
$stmt_update->close();

