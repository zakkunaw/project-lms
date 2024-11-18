<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

require_once '../includes/db_connect.php';

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $chapter_id = intval($_POST['chapter_id']);
        $questions = $_POST['question'];
        $options_a = $_POST['option_a'];
        $options_b = $_POST['option_b'];
        $options_c = $_POST['option_c'];
        $options_d = $_POST['option_d'];
        $correct_options = $_POST['correct_option'];

        if (empty($questions) || !is_array($questions)) {
            throw new Exception("Tidak ada pertanyaan yang disubmit");
        }

        $conn->begin_transaction();

        // Prepare statement untuk memeriksa keberadaan pertanyaan
        $checkStmt = $conn->prepare("SELECT id FROM quizzes WHERE chapter_id = ? AND question = ?");
        $insertStmt = $conn->prepare("INSERT INTO quizzes (chapter_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $updateStmt = $conn->prepare("UPDATE quizzes SET option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ? WHERE id = ?");

        foreach ($questions as $i => $question) {
            if (empty($question)) continue;

            $question = strip_tags($question, '<p><br><strong><em><ul><li><ol><img><audio><source><video>');
            $option_a = htmlspecialchars($options_a[$i]);
            $option_b = htmlspecialchars($options_b[$i]);
            $option_c = htmlspecialchars($options_c[$i]);
            $option_d = htmlspecialchars($options_d[$i]);
            $correct_option = htmlspecialchars($correct_options[$i]);

            // Check if the question already exists
            $checkStmt->bind_param("is", $chapter_id, $question);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                // If exists, get the quiz ID and update the entry
                $checkStmt->bind_result($quiz_id);
                $checkStmt->fetch();
                $updateStmt->bind_param("sssssi",
                    $option_a,
                    $option_b,
                    $option_c,
                    $option_d,
                    $correct_option,
                    $quiz_id
                );
                if (!$updateStmt->execute()) {
                    throw new Exception("Error updating statement: " . $updateStmt->error);
                }
            } else {
                // If not exists, insert a new entry
                $insertStmt->bind_param("issssss",
                    $chapter_id,
                    $question,
                    $option_a,
                    $option_b,
                    $option_c,
                    $option_d,
                    $correct_option
                );
                if (!$insertStmt->execute()) {
                    throw new Exception("Error executing insert statement: " . $insertStmt->error);
                }
            }
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Quiz berhasil ditambahkan atau diperbarui!']);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
