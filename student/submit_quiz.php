<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];
$chapter_id = isset($_GET['chapter_id']) ? intval($_GET['chapter_id']) : 0;

if ($chapter_id === 0) {
    header("Location: my_courses.php");
    exit();
}

// Fetch quizzes for the chapter
$sql_quizzes = "SELECT * FROM quizzes WHERE chapter_id = ?";
$stmt_quizzes = $conn->prepare($sql_quizzes);
$stmt_quizzes->bind_param("i", $chapter_id);
$stmt_quizzes->execute();
$quizzes = $stmt_quizzes->get_result();
$stmt_quizzes->close();

$quiz_data = [];
while ($quiz = $quizzes->fetch_assoc()) {
    $quiz_data[] = $quiz;
}

// Handle form submission
$total_score = 0;
$is_passed = null; // This will hold the passing status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_quizzes = count($quiz_data);

    foreach ($quiz_data as $quiz) {
        $selected_answer = $_POST['answer_' . $quiz['id']] ?? '';
        if ($selected_answer === $quiz['correct_option']) {
            $total_score += 100 / $total_quizzes;
        }
    }

    $is_passed = $total_score >= 80 ? 1 : 0;

    // Insert or update quiz completion
    $sql_upsert = "INSERT INTO quiz_completions (student_id, quiz_id, is_passed, completed_at)
                   VALUES (?, ?, ?, NOW())
                   ON DUPLICATE KEY UPDATE is_passed = VALUES(is_passed), completed_at = VALUES(completed_at)";
    $stmt_upsert = $conn->prepare($sql_upsert);
    foreach ($quiz_data as $quiz) {
        $stmt_upsert->bind_param("iii", $student_id, $quiz['id'], $is_passed);
        $stmt_upsert->execute();
    }
    $stmt_upsert->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Submit Quiz</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mx-auto mt-5 px-4">
        <h1 class="mb-4 text-2xl font-bold">Submit Quiz</h1>
        <form action="" method="POST">
            <?php foreach ($quiz_data as $quiz): ?>
                <div class="mb-4">
                    <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($quiz['question']); ?></h3>
                    <div>
                        <label class="block"><input type="radio" name="answer_<?php echo $quiz['id']; ?>" value="A"> <?php echo htmlspecialchars($quiz['option_a']); ?></label>
                        <label class="block"><input type="radio" name="answer_<?php echo $quiz['id']; ?>" value="B"> <?php echo htmlspecialchars($quiz['option_b']); ?></label>
                        <label class="block"><input type="radio" name="answer_<?php echo $quiz['id']; ?>" value="C"> <?php echo htmlspecialchars($quiz['option_c']); ?></label>
                        <label class="block"><input type="radio" name="answer_<?php echo $quiz['id']; ?>" value="D"> <?php echo htmlspecialchars($quiz['option_d']); ?></label>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Quiz</button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="mt-5 bg-gray-100 p-5 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-2">Your Score: <?php echo round($total_score, 2); ?>%</h2>
                <?php if ($is_passed): ?>
                    <p class="text-green-600">Congratulations! You passed the quiz.</p>
                <?php else: ?>
                    <p class="text-red-600">Unfortunately, you did not pass. Please try again.</p>
                <?php endif; ?>
                <a href="my_courses.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Back to My Courses</a>
            </div>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
