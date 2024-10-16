<?php
// index.php
session_start();
require_once 'includes/db_connect.php';

// Fetch dua kursus teratas
$sql_courses = "SELECT * FROM courses LIMIT 2";
$courses = $conn->query($sql_courses);

// Handle akses kursus gratis
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['access_course'])){
    $course_id = intval($_POST['course_id']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Simpan data tamu atau buat sesi
    $_SESSION['guest_email'] = $email;
    $_SESSION['guest_phone'] = $phone;

    // Redirect ke halaman kursus dengan batasan
    header("Location: courses/course.php?id=$course_id&limit=5");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include 'includes/header.php'; ?>
    <title>Landing Page - LMS</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Selamat Datang di LMS</h1>
        <p>Pelajari kursus kami secara gratis dengan akses terbatas.</p>
        <div class="row">
            <?php while($course = $courses->fetch_assoc()): ?>
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
                            <form method="POST" action="">
                                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                <div class="mb-3">
                                    <label for="email_<?= $course['id'] ?>" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email_<?= $course['id'] ?>" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone_<?= $course['id'] ?>" class="form-label">Nomor HP</label>
                                    <input type="text" class="form-control" id="phone_<?= $course['id'] ?>" name="phone" required>
                                </div>
                                <button type="submit" name="access_course" class="btn btn-primary">Akses Kursus Gratis</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
