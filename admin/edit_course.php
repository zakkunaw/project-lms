<?php
// admin/edit_course.php

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

require_once '../includes/db_connect.php';

// Validasi parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "ID kursus tidak valid.";
    $_SESSION['message_type'] = "danger";
    header("Location: manage_courses.php");
    exit();
}

$course_id = intval($_GET['id']);

// Fetch Course Data
$sql_course = "SELECT * FROM courses WHERE id = ?";
$stmt_course = $conn->prepare($sql_course);
$stmt_course->bind_param("i", $course_id);
$stmt_course->execute();
$course = $stmt_course->get_result()->fetch_assoc();
$stmt_course->close();

if (!$course) {
    $_SESSION['message'] = "Kursus tidak ditemukan.";
    $_SESSION['message_type'] = "danger";
    header("Location: manage_courses.php");
    exit();
}

// Handle Update Request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $instructor_id = intval($_POST['instructor_id']);
    
    // Validasi input
    if (empty($title) || empty($description) || empty($instructor_id)) {
        $error = "Semua field wajib diisi.";
    } else {
        // Handle Cover Image Upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['cover_image']['type'], $allowed_types)) {
                $error = "Jenis file gambar tidak didukung.";
            } else {
                $image_name = basename($_FILES['cover_image']['name']);
                $target_dir = "../assets/images/courses/";
                $target_file = $target_dir . uniqid() . "_" . $image_name;
                
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                    // Optionally, hapus gambar lama jika ada
                    if (!empty($course['cover_image']) && file_exists($target_dir . $course['cover_image'])) {
                        unlink($target_dir . $course['cover_image']);
                    }
                    $cover_image = basename($target_file);
                } else {
                    $error = "Terjadi kesalahan saat mengunggah gambar.";
                }
            }
        } else {
            // Jika tidak ada upload, gunakan gambar lama
            $cover_image = $course['cover_image'];
        }
        
        if (!isset($error)) {
            // Update Kursus
            $sql_update = "UPDATE courses SET title = ?, description = ?, cover_image = ?, instructor_id = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sssii", $title, $description, $cover_image, $instructor_id, $course_id);
            
            if ($stmt_update->execute()) {
                $_SESSION['message'] = "Kursus berhasil diperbarui.";
                $_SESSION['message_type'] = "success";
                header("Location: manage_courses.php");
                exit();
            } else {
                $error = "Terjadi kesalahan saat memperbarui kursus.";
            }
            $stmt_update->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Edit Course - LMS Admin</title>
    <!-- Include Bootstrap CSS if not included in header.php -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    
    <button class="btn" id="menu-toggle"><i class="fas fa-bars"></i></button>
    
    <div class="d-flex" id="wrapper">
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Edit Course</h2>
        
        <!-- Display Error Message -->
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="edit_course.php?id=<?= $course_id ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Judul Kursus</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi Kursus</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?= htmlspecialchars($course['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="instructor_id">Instruktur</label>
                <select class="form-control" id="instructor_id" name="instructor_id" required>
                    <option value="">-- Pilih Instruktur --</option>
                    <?php
                        // Fetch All Instructors
                        $sql_instructors = "SELECT id, username FROM users WHERE role = 'instructor'";
                        $stmt_instructors = $conn->prepare($sql_instructors);
                        $stmt_instructors->execute();
                        $instructors = $stmt_instructors->get_result();
                        $stmt_instructors->close();
                        
                        while($instructor = $instructors->fetch_assoc()):
                    ?>
                        <option value="<?= $instructor['id'] ?>" <?= ($instructor['id'] == $course['instructor_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($instructor['username']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cover_image">Gambar Sampul</label>
                <?php if(!empty($course['cover_image'])): ?>
                    <div class="mb-2">
                        <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" alt="Cover Image" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control-file" id="cover_image" name="cover_image" accept="image/*">
                <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti gambar sampul.</small>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Kursus</button>
            <a href="manage_courses.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    
    <!-- Include jQuery and Bootstrap JS if not included in footer.php -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
