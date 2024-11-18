<?php
// check_chapter_access.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];
$chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
$previous_chapter_id = isset($_POST['previous_chapter_id']) ? intval($_POST['previous_chapter_id']) : 0;

if ($chapter_id === 0 || $previous_chapter_id === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid chapter data']);
    exit();
}

// Check if there is a quiz in the previous chapter and if it has been passed
$sql = "
    SELECT qc.is_passed 
    FROM quizzes q 
    LEFT JOIN quiz_completions qc ON q.id = qc.quiz_id AND qc.student_id = ?
    WHERE q.chapter_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $previous_chapter_id);
$stmt->execute();
$result = $stmt->get_result();
$quiz_completed = true;  // Default to true if no quiz exists in the previous chapter

if ($result->num_rows > 0) {
    $quiz_completed = false;  // If quiz exists, assume not completed until proven otherwise
    while ($row = $result->fetch_assoc()) {
        if ($row['is_passed']) {
            $quiz_completed = true;
            break; // Stop once we know the quiz is passed
        }
    }
}
$stmt->close();

if ($quiz_completed) {
    echo json_encode(['status' => 'success', 'message' => 'Chapter is unlocked']);
} else {
    echo json_encode(['status' => 'locked', 'message' => 'Complete the previous chapter quiz to unlock this chapter']);
}
?>
