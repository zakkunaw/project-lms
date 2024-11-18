<?php
// student/course_overview.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($course_id === 0) {
    header("Location: my_courses.php");
    exit();
}

// Fetch course details
$sql_course = "SELECT c.*, u.username as instructor_name FROM courses c JOIN users u ON c.instructor_id = u.id WHERE c.id = ?";
$stmt_course = $conn->prepare($sql_course);
$stmt_course->bind_param("i", $course_id);
$stmt_course->execute();
$course = $stmt_course->get_result()->fetch_assoc();
$stmt_course->close();

if (!$course) {
    header("Location: my_courses.php");
    exit();
}

// Fetch chapters
$sql_chapters = "SELECT * FROM chapters WHERE course_id = ? ORDER BY chapter_number ASC";
$stmt_chapters = $conn->prepare($sql_chapters);
$stmt_chapters->bind_param("i", $course_id);
$stmt_chapters->execute();
$chapters = $stmt_chapters->get_result();
$stmt_chapters->close();    

// Fetch quizzes and their completion status
$sql_quizzes = "
    SELECT q.*, c.chapter_number, c.title as chapter_title,
           CASE WHEN qc.is_passed = 1 THEN 1 ELSE 0 END as is_passed
    FROM quizzes q 
    JOIN chapters c ON q.chapter_id = c.id 
    LEFT JOIN quiz_completions qc ON q.id = qc.quiz_id AND qc.student_id = ?
    WHERE c.course_id = ? 
    GROUP BY c.chapter_number
    ORDER BY c.chapter_number ASC, q.id ASC
";
$stmt_quizzes = $conn->prepare($sql_quizzes);
$stmt_quizzes->bind_param("ii", $student_id, $course_id);
$stmt_quizzes->execute();
$quizzes = $stmt_quizzes->get_result();
$stmt_quizzes->close();

// Create an array to store quiz completion status for each chapter
$chapter_quiz_status = array();
while ($quiz = $quizzes->fetch_assoc()) {
    $chapter_quiz_status[$quiz['chapter_number']] = $quiz['is_passed'];
}

// Check if the first chapter's quiz is completed
$first_chapter_completed = isset($chapter_quiz_status[1]) && $chapter_quiz_status[1];// Progress calculation
$sql_progress = "SELECT 
    (SELECT COUNT(*) FROM sub_chapters WHERE chapter_id IN (SELECT id FROM chapters WHERE course_id = ?)) +
    (SELECT COUNT(DISTINCT chapter_id) FROM quizzes WHERE chapter_id IN (SELECT id FROM chapters WHERE course_id = ?)) as total_items,
    (SELECT COUNT(*) FROM student_progress WHERE student_id = ? AND course_id = ? AND completed = 1) as completed_items";
$stmt_progress = $conn->prepare($sql_progress);
$stmt_progress->bind_param("iiii", $course_id, $course_id, $student_id, $course_id);
$stmt_progress->execute();
$progress_result = $stmt_progress->get_result()->fetch_assoc();
$stmt_progress->close();

$total_items = $progress_result['total_items'];
$completed_items = $progress_result['completed_items'];
$progress_percentage = ($total_items > 0) ? round(($completed_items / $total_items) * 100) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <title><?= htmlspecialchars($course['title']) ?> - Course Overview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="../assets/js/course_progress.js"></script> <!-- Include progress script -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .course-overview {
            display: flex;
            flex-wrap: wrap;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background-color: #fff;
        }
        .course-thumbnail {
            flex: 1 1 100%;
            max-width: 100%;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
        }
        .course-thumbnail img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .course-content {
            flex: 1 1 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
        }
        .list-group-item {
            transition: background-color 0.3s;
        }
        .list-group-item:hover {
            background-color: #e9ecef;
        }
        .progress-bar {
            background-color: #28a745;
            color: #28a745;
        }
        .accordion-button {
            background-color: #f8f9fa;
            color: #000;
        }
        .accordion-button:not(.collapsed) {
            background-color: #e9ecef;
        }
        .accordion-button::after {
            color: #000;
        }
        .badge-secondary {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <?php include '../includes/student/navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="mb-4"><?= htmlspecialchars($course['title']) ?></h1>
        <div class="row course-overview">
            <div class="col-md-4 course-thumbnail">
                <?php if ($course['cover_image']): ?>
                    <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" alt="Cover image for <?= htmlspecialchars($course['title']) ?>">
                <?php else: ?>
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 100%;"><i class="bi bi-book" style="font-size: 4rem;"></i></div>
                <?php endif; ?>
            </div>
            <div class="col-md-8 course-content">
                <p><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                <p><?= $course['description'] ?></p>

                <!-- Progress Bar -->
                <!-- Progress Bar -->
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: <?= $progress_percentage; ?>%; background-color: green;" 
                        aria-valuenow="<?= $progress_percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                        <?= $progress_percentage; ?>%
                    </div>
                </div>

                <h2 class="mt-5 mb-3">Course Content</h2>
                <div class="accordion" id="courseContent">
                    <?php 
                    $previous_chapter_accessible = true; // Allow access to the first chapter by default
                    while ($chapter = $chapters->fetch_assoc()): 
                    ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="chapter<?= $chapter['id'] ?>Heading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#chapter<?= $chapter['id'] ?>Content" aria-expanded="false" aria-controls="chapter<?= $chapter['id'] ?>Content">
                                    Chapter <?= htmlspecialchars($chapter['chapter_number']) ?>: <?= htmlspecialchars($chapter['title']) ?>
                                    <?php if (!$previous_chapter_accessible && $chapter['chapter_number'] > 1): ?>
                                        <i class="bi bi-lock-fill ms-2" title="Complete previous chapter's quiz to unlock"></i>
                                    <?php endif; ?>
                                </button>
                            </h2>
                            <div id="chapter<?= $chapter['id'] ?>Content" class="accordion-collapse collapse" aria-labelledby="chapter<?= $chapter['id'] ?>Heading" data-bs-parent="#courseContent">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <?php
                                        // Fetch sub-chapters directly from the database
                                        $chapter_id = $chapter['id'];
                                        $sql_sub_chapters = "SELECT * FROM sub_chapters WHERE chapter_id = ? ORDER BY id ASC";
                                        $stmt_sub_chapters = $conn->prepare($sql_sub_chapters);
                                        $stmt_sub_chapters->bind_param("i", $chapter_id);
                                        $stmt_sub_chapters->execute();
                                        $sub_chapters = $stmt_sub_chapters->get_result();
                                        $stmt_sub_chapters->close();

                                        // List each sub-chapter
                                        while ($sub = $sub_chapters->fetch_assoc()): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <?= htmlspecialchars($sub['title']) ?>
                                                <?php if ($chapter['chapter_number'] == 1 || ($previous_chapter_accessible && $first_chapter_completed)): ?>
                                                    <a href="course_content.php?course_id=<?= $course_id ?>&sub_chapter_id=<?= $sub['id'] ?>" 
                                                       class="btn btn-sm btn-primary access-content" 
                                                       data-sub-chapter-id="<?= $sub['id'] ?>" 
                                                       data-chapter-id="<?= $chapter['id'] ?>">
                                                        Access
                                                    </a>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><i class="bi bi-lock-fill"></i> Locked</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endwhile; ?>

                                        <!-- Insert Quiz for the Chapter -->
                                        <?php 
                                        $quizzes->data_seek(0); // Reset the quiz result pointer
                                       while ($quiz = $quizzes->fetch_assoc()): 
                                    if ($quiz['chapter_number'] == $chapter['chapter_number']): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Quiz: <?= htmlspecialchars($quiz['chapter_title']) ?>
                                            
                                            <?php if ($quiz['is_passed']): ?>
                                                <span class="badge bg-success">Completed</span>
                                            <?php else: ?>
                                                <?php if ($quiz['chapter_number'] > 1 && !$first_chapter_completed): ?>
                                                    <span class="badge bg-secondary"><i class="bi bi-lock-fill"></i> Locked</span>
                                                <?php else: ?>
                                                    <a href="take_quiz.php?chapter_id=<?= $quiz['chapter_id'] ?>" 
                                                       class="btn btn-sm btn-primary take-quiz access-content" 
                                                       data-quiz-id="<?= $quiz['id'] ?>" 
                                                       data-chapter-id="<?= $quiz['chapter_id'] ?>">
                                                        Take Quiz
                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; 
                                endwhile; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Determine access to the next chapter based on quiz completion
                        if (isset($chapter['quiz']) && !$chapter_quiz_status[$chapter['chapter_number']]) {
                            $previous_chapter_accessible = false; // Lock next chapter if the current one is not completed
                        }
                    endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybStSyf8g8p8BlA1zAcvVBlAKQ1U+H0wQ1C4/21b7/8VDRlYc" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-JCwWlP22D2mT1WhIVBf5/7UVgXM1f5/JbE2qB+czAWZkP3Vz+g4CvtdAhD8PsfGE" crossorigin="anonymous"></script>
</body>
</html>