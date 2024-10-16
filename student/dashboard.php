<?php
// student/dashboard.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

// Handle pendaftaran kursus
if(isset($_POST['enroll_course'])){
    $course_id = intval($_POST['course_id']);
    $student_id = $_SESSION['user_id'];

    // Cek apakah sudah mendaftar
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        // Daftar kursus
        $stmt_insert = $conn->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
        $stmt_insert->bind_param("ii", $student_id, $course_id);
        if($stmt_insert->execute()){
            $success = "Anda berhasil mendaftar kursus.";
        } else {
            $error = "Terjadi kesalahan saat mendaftar kursus.";
        }
        $stmt_insert->close();
    } else {
        $error = "Anda sudah terdaftar di kursus ini.";
    }
    $stmt->close();
}

// Ambil semua kursus yang tersedia
$sql_available_courses = "SELECT courses.*, users.username as instructor_name FROM courses JOIN users ON courses.instructor_id = users.id";
$available_courses = $conn->query($sql_available_courses);

// Ambil kursus yang telah diikuti oleh siswa
$student_id = $_SESSION['user_id'];
$sql_enrolled_courses = "SELECT courses.*, users.username as instructor_name FROM courses JOIN enrollments ON courses.id = enrollments.course_id JOIN users ON courses.instructor_id = users.id WHERE enrollments.student_id = ?";
$stmt_enrolled = $conn->prepare($sql_enrolled_courses);
$stmt_enrolled->bind_param("i", $student_id);
$stmt_enrolled->execute();
$enrolled_courses = $stmt_enrolled->get_result();
$stmt_enrolled->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Dashboard Siswa - LMS</title>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Dashboard Siswa</h2>

        <!-- Tampilkan pesan sukses atau error -->
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Kursus Tersedia -->
        <h3>Kursus Tersedia</h3>
        <div class="row">
            <?php while($course = $available_courses->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <?php if($course['cover_image']): ?>
                            <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" class="card-img-top" alt="Cover Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($course['description']) ?></p>
                            <p><strong>Instruktur:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                            <form method="POST" action="dashboard.php">
                                <input type="hidden" name="course_id" value="<?= htmlspecialchars($course['id']) ?>">
                                <button type="submit" name="enroll_course" class="btn btn-primary">Enroll</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Kursus yang Diikuti -->
        <h3>Kursus yang Anda Ikuti</h3>
        <?php if($enrolled_courses->num_rows > 0): ?>
            <div class="accordion" id="enrolledCoursesAccordion">
                <?php while($course = $enrolled_courses->fetch_assoc()): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= htmlspecialchars($course['id']) ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnrolled<?= htmlspecialchars($course['id']) ?>" aria-expanded="false" aria-control[...]
                                <?= htmlspecialchars($course['title']) ?>
                            </button>
                        </h2>
                        <div id="collapseEnrolled<?= htmlspecialchars($course['id']) ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= htmlspecialchars($course['id']) ?>" data-bs-parent="#enrolledCoursesAccordion">
                            <div class="accordion-body">
                                <p><?= htmlspecialchars($course['description']) ?></p>
                                <!-- Ambil bab dan sub-bab -->
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
                                        <?php while($chapter = $chapters->fetch_assoc()): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlspecialchars($chapter['title']) ?></strong>
                                                <!-- Ambil sub-bab -->
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
                                                                // Cek apakah sub-bab dapat diakses
                                                                // Cari semua sub-bab sebelum ini dan cek apakah kuis sudah dipenuhi
                                                                $stmt_prev = $conn->prepare("SELECT sc.id, q.id as quiz_id FROM sub_chapters sc LEFT JOIN quizzes q ON sc.id = q.sub_chapter_id WHERE sc.chapter_id = ? AND sc.id <= ? ORDER BY sc.id ASC");
                                                                $stmt_prev->bind_param("ii", $chapter_id, $sub['id']);
                                                                $stmt_prev->execute();
                                                                $prev_subs = $stmt_prev->get_result();
                                                                $can_access = true;
                                                                while($prev = $prev_subs->fetch_assoc()){
                                                                    if($prev['quiz_id']){
                                                                        // Cek apakah kuis telah dipenuhi
                                                                        $stmt_check = $conn->prepare("SELECT * FROM quiz_completions WHERE student_id = ? AND quiz_id = ? AND is_passed = 1");
                                                                        $stmt_check->bind_param("ii", $student_id, $prev['quiz_id']);
                                                                        $stmt_check->execute();
                                                                        $quiz_result = $stmt_check->get_result();
                                                                        if($quiz_result->num_rows == 0){
                                                                            $can_access = false;
                                                                            break;
                                                                        }
                                                                        $stmt_check->close();
                                                                    }
                                                                }
                                                                $stmt_prev->close();
                                                            ?>
                                                            <li class="list-group-item">
                                                                <?= htmlspecialchars($sub['title']) ?>
                                                                <?php if($can_access): ?>
                                                                    <a href="course_content.php?course_id=<?= htmlspecialchars($course['id']) ?>&sub_chapter_id=<?= htmlspecialchars($sub['id']) ?>" class="btn btn-sm btn-info float-end">Akses</a>
                                                                <?php else: ?>
                                                                    <span class="badge bg-secondary float-end"><i class="bi bi-lock"></i> Terkunci</span>
                                                                <?php endif; ?>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Anda belum mengikuti kursus apapun.</p>
        <?php endif; ?>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>