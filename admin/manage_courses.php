<?php
// admin/manage_courses.php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../includes/db_connect.php';

// Handle Delete Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_course_id'])) {
    $delete_course_id = intval($_POST['delete_course_id']);
    
    // Optional: Anda bisa menambahkan cek apakah kursus ada sebelum menghapus
    $sql_delete = "DELETE FROM courses WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $delete_course_id);
    
    if ($stmt_delete->execute()) {
        $_SESSION['message'] = "Kursus berhasil dihapus.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Terjadi kesalahan saat menghapus kursus.";
        $_SESSION['message_type'] = "danger";
    }
    $stmt_delete->close();
    
    header("Location: manage_courses.php");
    exit();
}

// Fetch All Courses with Instructor Names
$sql_courses = "
    SELECT courses.*, users.username AS instructor_name 
    FROM courses 
    JOIN users ON courses.instructor_id = users.id 

";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->execute();
$courses = $stmt_courses->get_result();
$stmt_courses->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Manage Courses - LMS Admin</title>
    <!-- Include Bootstrap CSS if not included in header.php -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Tombol Hamburger untuk mobile -->
    <button class="btn" id="menu-toggle"><i class="fas fa-bars"></i></button>
    
    <div class="d-flex" id="wrapper">
        <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Manage Courses</h2>
        
        <!-- Display Messages -->
        <?php if(isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type']) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['message']) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
        <?php endif; ?>
        
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Judul Kursus</th>
                    <th>Deskripsi</th>
                    <th>Instruktur</th>
                    <th>Gambar Sampul</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if($courses->num_rows > 0): ?>
                    <?php while($course = $courses->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['id']) ?></td>
                            <td><?= htmlspecialchars($course['title']) ?></td>
                            <td><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</td>
                            <td><?= htmlspecialchars($course['instructor_name']) ?></td>
                            <td>
                                <?php if($course['cover_image']): ?>
                                    <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" alt="Cover Image" class="img-thumbnail" style="max-width: 100px;">
                                <?php else: ?>
                                    <span class="text-muted">Tidak Ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit_course.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-primary mb-1">Edit</a>
                                <form method="POST" action="manage_courses.php" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini?');">
                                    <input type="hidden" name="delete_course_id" value="<?= $course['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada kursus yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    </div>
    <?php include '../includes/footer.php'; ?>
    
    <!-- Include jQuery and Bootstrap JS if not included in footer.php -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
