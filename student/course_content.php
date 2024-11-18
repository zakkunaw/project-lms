<?php
// student/course_content.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];

// Validate parameters
if (!isset($_GET['course_id']) || !isset($_GET['sub_chapter_id'])) {
    header("Location: my_courses.php");
    exit();
}

$course_id = intval($_GET['course_id']);
$sub_chapter_id = intval($_GET['sub_chapter_id']);

// Check if the student is enrolled in this course
$sql_enroll = "SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?";
$stmt_enroll = $conn->prepare($sql_enroll);
$stmt_enroll->bind_param("ii", $student_id, $course_id);
$stmt_enroll->execute();
$result_enroll = $stmt_enroll->get_result();
if ($result_enroll->num_rows == 0) {
    header("Location: my_courses.php");
    exit();
}
$stmt_enroll->close();

// Get sub-chapter information
$sql_sub_chapter = "SELECT sc.*, c.title as chapter_title, c.chapter_number, c.id as chapter_id
                    FROM sub_chapters sc 
                    JOIN chapters c ON sc.chapter_id = c.id 
                    WHERE sc.id = ?";
$stmt_sub = $conn->prepare($sql_sub_chapter);
$stmt_sub->bind_param("i", $sub_chapter_id);
$stmt_sub->execute();
$sub_chapter = $stmt_sub->get_result()->fetch_assoc();
$stmt_sub->close();

if (!$sub_chapter) {
    // Sub-chapter not found
    header("Location: my_courses.php");
    exit();
}

// Get next sub-chapter
$sql_next_sub = "SELECT id FROM sub_chapters 
                 WHERE chapter_id = ? AND id > ? 
                 ORDER BY id ASC LIMIT 1";
$stmt_next_sub = $conn->prepare($sql_next_sub);
$stmt_next_sub->bind_param("ii", $sub_chapter['chapter_id'], $sub_chapter_id);
$stmt_next_sub->execute();
$next_sub = $stmt_next_sub->get_result()->fetch_assoc();
$stmt_next_sub->close();

// Check if the quiz for the current chapter is completed
$sql_quiz_status = "SELECT qc.is_passed
                    FROM quiz_completions qc
                    JOIN quizzes q ON qc.quiz_id = q.id
                    WHERE qc.student_id = ? AND q.chapter_id = ?";
$stmt_quiz_status = $conn->prepare($sql_quiz_status);
$stmt_quiz_status->bind_param("ii", $student_id, $sub_chapter['chapter_id']);
$stmt_quiz_status->execute();
$quiz_status = $stmt_quiz_status->get_result()->fetch_assoc();
$stmt_quiz_status->close();

$is_quiz_completed = $quiz_status && $quiz_status['is_passed'];


// Progress calculation
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

// Fetch chapters
$sql_chapters = "SELECT * FROM chapters WHERE course_id = ? ORDER BY chapter_number ASC";
$stmt_chapters = $conn->prepare($sql_chapters);
$stmt_chapters->bind_param("i", $course_id);
$stmt_chapters->execute();
$chapters = $stmt_chapters->get_result();
$stmt_chapters->close();


?>

<style>
    
</style>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <script src="../assets/js/course_progress.js"></script> <!-- Include progress script -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
       <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <title><?= htmlspecialchars($sub_chapter['title']) ?> - LMS</title>
    <style>
        :root {
            --primary-color: #001f3f;
            --secondary-color: #003366;
            --accent-color: #0074D9;
            --text-color: #333;
            --bg-color: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--primary-color);
        }

        .content-wrapper {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .chapter-title {
            color: var(--primary-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 0.5rem;
        }

        .sub-chapter-title {
            color: var(--secondary-color);
        }

        .content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .progress-bar {
            background-color: var(--accent-color);
        }

        .sidebar {
            background-color: var(--secondary-color);
            color: white;
            padding: 1rem;
            border-radius: 8px;
        }

        .sidebar h4 {
            color: #ffffff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 0.5rem;
        }

        .sidebar ul {
            list-style-type: none;
            padding-left: 0;
        }

        .sidebar li {
            margin-bottom: 0.5rem;
        }

        .sidebar a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #ffffff;
        }

        .content {
            font-size: 1.1rem;
            line-height: 1.6;
        }



        audio {
    width: 100%; /* Mengatur lebar audio player agar responsif */
    background-color: #f9f9f9; /* Warna latar belakang */
    border: 1px solid #ccc; /* Border */
    border-radius: 5px; /* Sudut yang melengkung */
    padding: 10px; /* Ruang di dalam audio player */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Bayangan */
}

audio::-webkit-media-controls-panel {
    background-color: #f1f1f1; /* Latar belakang panel kontrol */
    border-radius: 5px; /* Sudut yang melengkung */
}

audio::-webkit-media-controls-play-button {
    background-color: green; /* Warna tombol play */
    border-radius: 50%; /* Membuat tombol bulat */
    color: red; /* Warna teks tombol */
}

audio::-webkit-media-controls-volume-slider {
    background-color: #ddd; /* Warna slider volume */
}

audio::-webkit-media-controls-current-time-display,
audio::-webkit-media-controls-time-remaining-display {
    color: #333; /* Warna teks waktu */
}
video {
    width: 100%;          /* Membuat video responsif sesuai lebar kontainer */
    height: auto;         /* Menyesuaikan tinggi secara proporsional */
    background-color: #000; /* Pastikan background tidak tembus */
    display: block;       /* Menghilangkan jarak atau margin default */
}


    </style>
</head>

<body>
    <?php include '../includes/student/navbar.php'; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="content-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="my_courses.php">My Courses</a></li>
                            <li class="breadcrumb-item"><a href="course_overview.php?course_id=<?= $course_id ?>">Course Overview</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($sub_chapter['title']) ?></li>
                        </ol>
                    </nav>

                    <h2 class="chapter-title mb-4">Chapter <?= $sub_chapter['chapter_number'] ?>: <?= htmlspecialchars($sub_chapter['chapter_title']) ?></h2>
                    <h3 class="sub-chapter-title mb-4"><?= htmlspecialchars($sub_chapter['title']) ?></h3>

                    <!-- Display sub-chapter content -->
                    <div class="mb-4 content">
                        <?= htmlspecialchars_decode($sub_chapter['content']) ?>
                    </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <a href="course_overview.php?course_id=<?= $course_id ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Back to Course Overview
                    </a>
                    <?php if ($next_sub): ?>
                        <a href="course_content.php?course_id=<?= $course_id ?>&sub_chapter_id=<?= $next_sub['id'] ?>" 
                        class="btn btn-primary next-sub-chapter access-content" 
                        data-sub-chapter-id="<?= $next_sub['id'] ?>" 
                        data-chapter-id="<?= $sub_chapter['chapter_id'] ?>">
                            Next Sub-chapter <i class="bi bi-arrow-right"></i>
                        </a>
                        <?php elseif (!$is_quiz_completed): ?>
                            <?php
                            // Fetch quiz ID for this chapter
                            $sql_quiz = "SELECT id FROM quizzes WHERE chapter_id = ?";
                            $stmt_quiz = $conn->prepare($sql_quiz);
                            $stmt_quiz->bind_param("i", $sub_chapter['chapter_id']);
                            $stmt_quiz->execute();
                            $quiz_result = $stmt_quiz->get_result();
                            $quiz = $quiz_result->fetch_assoc();
                            $stmt_quiz->close();
                            ?>
                            <a href="take_quiz.php?chapter_id=<?= $sub_chapter['chapter_id'] ?>&course_id=<?= $course_id ?>" 
                            class="btn btn-primary take-quiz access-content" 
                            data-quiz-id="<?= $quiz['id'] ?>" 
                            data-chapter-id="<?= $sub_chapter['chapter_id'] ?>">
                                Take Quiz <i class="bi bi-question-circle"></i>
                            </a>
                        <?php endif; ?>
                </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const quizButtons = document.querySelectorAll('.take-quiz');
    const progressBar = document.querySelector('.progress-bar');

    function updateProgress(itemType, itemId, chapterId) {
        const courseId = new URLSearchParams(window.location.search).get('course_id');
        
        // Log the data being sent
        console.log('Sending data:', {
            itemType,
            itemId,
            chapterId,
            courseId
        });

        return fetch('../update_progress.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `item_type=${itemType}&item_id=${itemId}&chapter_id=${chapterId}&course_id=${courseId}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response:', data); // Log the response
            if (data.success) {
                progressBar.style.width = `${data.progress}%`;
                progressBar.setAttribute('aria-valuenow', data.progress);
                progressBar.textContent = `${data.progress}%`;
                return true;
            } else {
                console.error('Failed to update progress:', data.message);
                return false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            return false;
        });
    }

    quizButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const quizId = this.dataset.quizId;
            const chapterId = this.dataset.chapterId;
            const href = this.href;

            console.log('Quiz button clicked:', {
                quizId,
                chapterId,
                href
            });

            updateProgress('quiz', quizId, chapterId)
                .then(success => {
                    if (success) {
                        window.location.href = href;
                    } 
                });
        });
    });
});
</script>
                </div>
            </div>
            <div class="col-md-4">
                <div class="sidebar">
                    <h4>Course Progress</h4>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: <?= isset($progress_percentage) ? $progress_percentage : 0; ?>%;" 
                            aria-valuenow="<?= isset($progress_percentage) ? $progress_percentage : 0; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= isset($progress_percentage) ? $progress_percentage : 0; ?>%
                        </div>
                    </div>
                    <h4>Chapter Contents</h4>
                    <ul>
                        <?php 
                        if (isset($chapters) && $chapters->num_rows > 0):
                            $chapters->data_seek(0); // Reset the chapters result pointer
                            while ($chapter = $chapters->fetch_assoc()): 
                        ?>
                            <li>
                                <a href="#" <?= ($chapter['id'] == $sub_chapter['chapter_id']) ? 'class="active"' : ''; ?>>
                                    <?= htmlspecialchars($chapter['chapter_number']) ?>. <?= htmlspecialchars($chapter['title']) ?>
                                </a>
                                <?php if ($chapter['id'] == $sub_chapter['chapter_id']): ?>
                                    <ul>
                                        <?php
                                        $sql_sub_chapters = "SELECT * FROM sub_chapters WHERE chapter_id = ? ORDER BY id ASC";
                                        $stmt_sub_chapters = $conn->prepare($sql_sub_chapters);
                                        $stmt_sub_chapters->bind_param("i", $chapter['id']);
                                        $stmt_sub_chapters->execute();
                                        $sub_chapters = $stmt_sub_chapters->get_result();
                                        while ($sub = $sub_chapters->fetch_assoc()):
                                        ?>
                                            <li>
                                                <a href="course_content.php?course_id=<?= $course_id ?>&sub_chapter_id=<?= $sub['id'] ?>"
                                                <?= ($sub['id'] == $sub_chapter_id) ? 'class="active"' : ''; ?>>
                                                    <?= htmlspecialchars($sub['title']) ?>
                                                </a>
                                            </li>
                                        <?php endwhile; 
                                        $stmt_sub_chapters->close();
                                        ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <li>No chapters available</li>
                        <?php endif; ?>
                    </ul>
                
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>