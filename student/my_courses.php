<?php
// student/my_courses.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];

// Ambil kursus yang telah di-enroll oleh siswa
$sql_enrolled_courses = "
    SELECT courses.*, users.username as instructor_name 
    FROM courses 
    JOIN enrollments ON courses.id = enrollments.course_id 
    JOIN users ON courses.instructor_id = users.id 
    WHERE enrollments.student_id = ?
";
$stmt = $conn->prepare($sql_enrolled_courses);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$enrolled_courses = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>My Courses - LMS</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>My Courses</h2>

        <?php if($enrolled_courses->num_rows > 0): ?>
            <div class="accordion" id="myCoursesAccordion">
                <?php while($course = $enrolled_courses->fetch_assoc()): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $course['id'] ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $course['id'] ?>" aria-expanded="false" aria-controls="collapse<?= $course['id'] ?>">
                                <?= htmlspecialchars($course['title']) ?> <small class="text-muted">(Instruktur: <?= htmlspecialchars($course['instructor_name']) ?>)</small>
                            </button>
                        </h2>
                        <div id="collapse<?= $course['id'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $course['id'] ?>" data-bs-parent="#myCoursesAccordion">
                            <div class="accordion-body">
                                <!-- Detail kursus -->
                                <?php if($course['cover_image']): ?>
                                    <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" alt="Cover Image" class="img-fluid mb-3" style="max-width: 200px;">
                                <?php endif; ?>
                                <p><?= $course['description'] ?></p>

                              
                                <?php
                                    $course_id = $course['id'];
                                    $sql_chapters = "SELECT * FROM chapters WHERE course_id = ? ORDER BY chapter_number ASC";
                                    $stmt_chapters = $conn->prepare($sql_chapters);
                                    $stmt_chapters->bind_param("i", $course_id);
                                    $stmt_chapters->execute();
                                    $chapters = $stmt_chapters->get_result();
                                    $stmt_chapters->close();
                                ?>
                                <?php if($chapters->num_rows > 0): ?>
                                    <ul class="list-group">
                                        <?php $first_chapter = true; ?>
                                        <?php while($chapter = $chapters->fetch_assoc()): ?>
                                            <li class="list-group-item">
                                                <strong>Bab <?= $chapter['chapter_number'] ?>: <?= htmlspecialchars($chapter['title']) ?></strong>
                                                <?php
                                                    $chapter_id = $chapter['id'];
                                                    $sql_sub_chapters = "SELECT * FROM sub_chapters WHERE chapter_id = ? ORDER BY id ASC";
                                                    $stmt_sub_chapters = $conn->prepare($sql_sub_chapters);
                                                    $stmt_sub_chapters->bind_param("i", $chapter_id);
                                                    $stmt_sub_chapters->execute();
                                                    $sub_chapters = $stmt_sub_chapters->get_result();
                                                    $stmt_sub_chapters->close();
                                                ?>
                                                <?php if($sub_chapters->num_rows > 0): ?>
                                                    <ul class="list-group mt-2">
                                                        <?php while($sub = $sub_chapters->fetch_assoc()): ?>
                                                            <?php
                                                                $sub_chapter_id = $sub['id'];

                                                                // Cek apakah sub-bab ini dapat diakses
                                                                $can_access = $first_chapter; // Bab pertama selalu dapat diakses langsung

                                                                if (!$first_chapter) {
                                                                    // Ambil semua sub-bab sebelumnya dalam bab ini
                                                                    $sql_prev_subs = "SELECT sc.id, q.id as quiz_id 
                                                                                      FROM sub_chapters sc 
                                                                                      LEFT JOIN quizzes q ON sc.id = q.sub_chapter_id 
                                                                                      WHERE sc.chapter_id = ? AND sc.id <= ? 
                                                                                      ORDER BY sc.id ASC";
                                                                    $stmt_prev = $conn->prepare($sql_prev_subs);
                                                                    $stmt_prev->bind_param("ii", $chapter_id, $sub_chapter_id);
                                                                    $stmt_prev->execute();
                                                                    $prev_subs = $stmt_prev->get_result();
                                                                    while($prev = $prev_subs->fetch_assoc()){
                                                                        if($prev['quiz_id']){
                                                                            // Cek apakah kuis telah dipenuhi
                                                                            $sql_check_quiz = "SELECT * FROM quiz_completions WHERE student_id = ? AND quiz_id = ? AND is_passed = 1";
                                                                            $stmt_quiz = $conn->prepare($sql_check_quiz);
                                                                            $stmt_quiz->bind_param("ii", $student_id, $prev['quiz_id']);
                                                                            $stmt_quiz->execute();
                                                                            $quiz_result = $stmt_quiz->get_result();
                                                                            if($quiz_result->num_rows == 0){
                                                                                $can_access = false;
                                                                                $quiz_result->free();
                                                                                $stmt_quiz->close();
                                                                                break;
                                                                            }
                                                                            $quiz_result->free();
                                                                            $stmt_quiz->close();
                                                                        }
                                                                    }
                                                                    $stmt_prev->close();
                                                                }
                                                            ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <?= htmlspecialchars($sub['title']) ?>
                                                                <?php if($can_access): ?>
                                                                    <a href="course_content.php?course_id=<?= $course['id'] ?>&sub_chapter_id=<?= $sub_chapter_id ?>" class="btn btn-sm btn-primary">
                                                                        Akses
                                                                    </a>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary"><i class="bi bi-lock"></i> Terkunci</span>
                                                                <?php endif; ?>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </li>
                                            <?php $first_chapter = false; ?>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted">Tidak ada bab dalam kursus ini.</p>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="mt-3">Anda belum mendaftar ke kursus apapun.</p>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>