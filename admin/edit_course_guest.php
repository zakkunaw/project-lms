<?php
// admin/edit_course_guest.php

session_start();
require_once '../includes/db_connect.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_courses_guest.php");
    exit();
}

$course_id = intval($_GET['id']);

// Handle course update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_course'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $instructor_id = intval($_POST['instructor_id']);
    $cover_image = $conn->real_escape_string($_POST['cover_image']);

    $sql = "UPDATE courses_guest SET title='$title', description='$description', instructor_id=$instructor_id, cover_image='$cover_image' WHERE id=$course_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: manage_courses_guest.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch course details
$sql_course = "SELECT * FROM courses_guest WHERE id=$course_id";
$course = $conn->query($sql_course)->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Edit Course - Admin</title>
    <!-- Include TinyMCE -->
     <script src="https://cdn.tiny.cloud/1/y6tj02ora74mku8eg7ugh9hx7qst3qu7kihrjv8zgtivaqzp/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#description',
            height: 300,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
    </script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Edit Guest Course</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($course['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="instructor_id" class="form-label">Instructor ID</label>
                <input type="number" class="form-control" id="instructor_id" name="instructor_id" value="<?= htmlspecialchars($course['instructor_id']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="cover_image" class="form-label">Cover Image</label>
                <input type="text" class="form-control" id="cover_image" name="cover_image" value="<?= htmlspecialchars($course['cover_image']) ?>">
            </div>
            <button type="submit" name="update_course" class="btn btn-primary">Update Course</button>
        </form>

        <!-- Form for guest access -->
        <h2>Guest Access</h2>
        <form method="POST" action="../process_guest_access.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <input type="hidden" name="course_id" value="<?= $course_id ?>">
            <button type="submit" class="btn btn-primary">Access Course</button>
        </form>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>