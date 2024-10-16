<?php
// instructor/manage_courses.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

// Handle penambahan kursus
if(isset($_POST['add_course'])){
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // Penanganan upload gambar
    if(isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0){
        $allowed = ['jpg','jpeg','png','gif'];
        $filename = $_FILES['cover_image']['name'];
        $file_tmp = $_FILES['cover_image']['tmp_name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array(strtolower($file_ext), $allowed)){
            $new_filename = uniqid() . '.' . $file_ext;
            $upload_dir = '../assets/images/courses/';
            if(move_uploaded_file($file_tmp, $upload_dir . $new_filename)){
                $cover_image = $new_filename;
            } else {
                $error = "Gagal mengunggah gambar.";
            }
        } else {
            $error = "Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        }
    } else {
        $cover_image = NULL; // Opsional
    }

    if(!isset($error)){
        $instructor_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("INSERT INTO courses (title, description, instructor_id, cover_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $title, $description, $instructor_id, $cover_image);
        if($stmt->execute()){
            $success = "Kursus berhasil ditambahkan.";
        } else {
            $error = "Terjadi kesalahan saat menambahkan kursus.";
        }
        $stmt->close();
    }
}

// Ambil kursus yang dibuat oleh instruktur
$instructor_id = $_SESSION['user_id'];
$sql_courses = "SELECT * FROM courses WHERE instructor_id = $instructor_id";
$courses = $conn->query($sql_courses);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Kelola Kursus - Instruktur</title>
    <!-- Sertakan CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Kelola Kursus</h2>

        <!-- Tampilkan pesan sukses atau error -->
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <!-- Form untuk menambahkan kursus baru -->
        <div class="card mb-4">
            <div class="card-header">Tambahkan Kursus Baru</div>
            <div class="card-body">
                <form method="POST" action="manage_courses.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Kursus</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Topik Kursus</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Sampul Kursus (Opsional)</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                    </div>
                    <button type="submit" name="add_course" class="btn btn-primary">Tambahkan Kursus</button>
                </form>
            </div>
        </div>

        <!-- Daftar kursus yang ada -->
        <h3>Kursus Anda</h3>
        <?php if($courses->num_rows > 0): ?>
            <div class="accordion" id="coursesAccordion">
                <?php while($course = $courses->fetch_assoc()): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?= $course['id'] ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $course['id'] ?>" aria-expanded="false" aria-controls="collapse<?= $course['id'] ?>">
                                <?= htmlspecialchars($course['title']) ?>
                            </button>
                        </h2>
                        <div id="collapse<?= $course['id'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $course['id'] ?>" data-bs-parent="#coursesAccordion">
                            <div class="accordion-body">
                                <!-- Detail kursus -->
                                <?php if($course['cover_image']): ?>
                                    <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" alt="Cover Image" class="img-fluid mb-3" style="max-width: 200px;">
                                <?php endif; ?>
                                <p><strong>Topik:</strong> <?= htmlspecialchars($course['description']) ?></p>
                                
                                <!-- Form untuk menambahkan bab -->
                                <h5>Tambahkan Bab</h5>
                                <?php
                                    // Ambil bab yang sudah ada
                                    $course_id = $course['id'];
                                    $sql_chapters = "SELECT * FROM chapters WHERE course_id = $course_id ORDER BY chapter_number ASC";
                                    $chapters = $conn->query($sql_chapters);
                                ?>
                                    <form method="POST" action="add_chapter.php">
                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                        <div class="mb-3">
                                            <label for="chapter_title_<?= $course['id'] ?>" class="form-label">Judul Bab</label>
                                            <input type="text" class="form-control" id="chapter_title_<?= $course['id'] ?>" name="chapter_title" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="chapter_content_<?= $course['id'] ?>" class="form-label">Konten Bab</label>
                                            <textarea class="form-control" id="chapter_content_<?= $course['id'] ?>" name="chapter_content" required></textarea>
                                            <script>
                                                CKEDITOR.replace('chapter_content_<?= $course['id'] ?>');
                                            </script>
                                        </div>
                                        <button type="submit" name="add_chapter" class="btn btn-secondary">Tambahkan Bab</button>
                                    </form>

                                <!-- Daftar bab -->
                                <?php if($chapters->num_rows > 0): ?>
                                    <ul class="list-group mt-3">
                                        <?php while($chapter = $chapters->fetch_assoc()): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlspecialchars($chapter['title']) ?></strong>
                                                <!-- Tombol untuk menambahkan sub-bab -->
                                                <button class="btn btn-sm btn-success float-end" data-bs-toggle="modal" data-bs-target="#addSubChapterModal<?= $chapter['id'] ?>">Tambah Sub-Bab</button>

                                                <!-- Daftar sub-bab -->
                                                <?php
                                                    $chapter_id = $chapter['id'];
                                                    $sql_sub_chapters = "SELECT * FROM sub_chapters WHERE chapter_id = $chapter_id ORDER BY id ASC";
                                                    $sub_chapters = $conn->query($sql_sub_chapters);
                                                ?>
                                                <?php if($sub_chapters->num_rows > 0): ?>
                                                    <ul class="list-group mt-2">
                                                        <?php while($sub = $sub_chapters->fetch_assoc()): ?>
                                                            <li class="list-group-item">
                                                                <?= htmlspecialchars($sub['title']) ?>
                                                                <!-- Tombol untuk menambahkan kuis -->
                                                                <a href="add_quiz.php?sub_chapter_id=<?= $sub['id'] ?>" class="btn btn-sm btn-info float-end">Tambah Quiz</a>
                                                            </li>
                                                        <?php endwhile; ?>
                                                    </ul>
                                                <?php endif; ?>
                                            </li>

                                            <!-- Modal untuk menambahkan sub-bab -->
                                            <div class="modal fade" id="addSubChapterModal<?= $chapter['id'] ?>" tabindex="-1" aria-labelledby="addSubChapterModalLabel<?= $chapter['id'] ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="add_sub_chapter.php">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="addSubChapterModalLabel<?= $chapter['id'] ?>">Tambah Sub-Bab</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="chapter_id" value="<?= $chapter['id'] ?>">
                                                                <div class="mb-3">
                                                                    <label for="sub_chapter_title_<?= $chapter['id'] ?>" class="form-label">Judul Sub-Bab</label>
                                                                    <input type="text" class="form-control" id="sub_chapter_title_<?= $chapter['id'] ?>" name="sub_chapter_title" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="sub_chapter_content_<?= $chapter['id'] ?>" class="form-label">Konten Sub-Bab</label>
                                                                    <textarea class="form-control" id="sub_chapter_content_<?= $chapter['id'] ?>" name="sub_chapter_content" required></textarea>
                                                                    <script>
                                                                        CKEDITOR.replace('sub_chapter_content_<?= $chapter['id'] ?>');
                                                                    </script>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" name="add_sub_chapter" class="btn btn-primary">Tambahkan Sub-Bab</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Anda belum memiliki kursus. Tambahkan kursus pertama Anda di atas.</p>
        <?php endif; ?>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
