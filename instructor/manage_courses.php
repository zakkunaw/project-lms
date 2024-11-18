<?php
// instructor/manage_courses.php
session_start();
// Tambahkan pengecekan session timeout
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: ../login.php");
    exit();
}
$_SESSION['last_activity'] = time();
require_once '../includes/db_connect.php';

// Handle penambahan kursus
if (isset($_POST['add_course'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string(trim(str_replace(array("\r", "\n"), '', $_POST['description']))); // Bersihkan deskripsi

    // Penanganan upload gambar
    // ...

    // Penanganan upload gambar
    $cover_image = NULL; // Inisialisasi variabel
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['cover_image']['name'];
        $file_tmp = $_FILES['cover_image']['tmp_name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($file_ext), $allowed)) {
            $new_filename = uniqid() . '.' . $file_ext;
            $upload_dir = '../assets/images/courses/';
            if (move_uploaded_file($file_tmp, $upload_dir . $new_filename)) {
                $cover_image = $new_filename;
            } else {
                $error = "Gagal mengunggah gambar.";
            }
        } else {
            $error = "Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
        }
    }

    if (!isset($error)) {
        $instructor_id = $_SESSION['user_id'];
        $description = trim(preg_replace('/\r\n|\r|\n/', '<br>', $description)); // Bersihkan deskripsi
        $stmt = $conn->prepare("INSERT INTO courses (title, description, instructor_id, cover_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $title, $description, $instructor_id, $cover_image);
        if ($stmt->execute()) {
            $success = "Kursus berhasil ditambahkan.";
        } else {
            $error = "Terjadi kesalahan saat menambahkan kursus.";
        }
        $stmt->close();
    }
}

// Ambil kursus yang dibuat oleh instruktur
$instructor_id = $_SESSION['user_id'];
$sql_courses = "SELECT * FROM courses WHERE instructor_id = ?";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->bind_param("i", $instructor_id);
$stmt_courses->execute();
$courses = $stmt_courses->get_result();

// Handle success and error messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <?php include '../includes/header.php'; ?>
    <title>Kelola Kursus - Instruktur</title>
       <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <!-- <script type="text/javascript" src="../ckeditor/ckeditor.js"></script> -->
    <!-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> -->
    <script src="https://cdn.tiny.cloud/1/y6tj02ora74mku8eg7ugh9hx7qst3qu7kihrjv8zgtivaqzp/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- <script src="../assets/js/tinymce/js/tinymce/tinymce.min.js"></script> -->

</head>

<body>
    <?php include '../includes/header.php'; ?>

    <button class="btn" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="d-flex" id="wrapper">
        <?php include '../includes/instruktur/navbar.php'; ?>
        <div class="container mt-5">
            <h2>Kelola Kursus</h2>

            <!-- Tampilkan pesan sukses atau error -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
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
                            <label for="description" class="form-label">Deskripsi Course</label>
                            <textarea class="form-control tinymce-editor" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Sampul Kursus (Opsional)</label>
                            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                        </div>
                        <button type="submit" name="add_course" class="btn btn-primary">Tambahkan Kursus</button>
                    </form>
                </div>
            </div>

    <script type="text/javascript">
     tinymce.init({  
       selector: '.tinymce-editor',
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table emoticons hr code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | link image media emoticons | code preview',
        height: 400,
        menubar: true,
        image_title: true,
        automatic_uploads: true,
        file_picker_types: 'file image media',
        media_live_embeds: true,
            file_picker_callback: function(callback, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                
                if (meta.filetype === 'image') {
                    input.setAttribute('accept', 'image/*');
                } else if (meta.filetype === 'media') {
                    input.setAttribute('accept', 'audio/*,video/*');
                }

                input.onchange = function() {
                    var file = this.files[0];
                    var formData = new FormData();
                    formData.append('file', file);
                    formData.append('chapter_name', 'media');

                    $.ajax({
                        url: 'upload_media.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            var json = JSON.parse(response);
                            if (json.status === 'success') {
                                if (meta.filetype === 'media') {
                                    // For audio/video, add the appropriate HTML tag
                                    if (file.type.includes('audio')) {
                                        callback(json.url, {
                                            embed: '<audio controls="controls">' +
                                                '<source src="' + json.url + '" type="' + file.type + '" />' +
                                                '</audio>'
                                        });
                                    } else if (file.type.includes('video')) {
                                        callback(json.url, {
                                            embed: '<video controls="controls" width="300" height="150">' +
                                                '<source src="' + json.url + '" type="' + file.type + '" />' +
                                                '</video>'
                                        });
                                    }
                                } else {
                                    // For images
                                    callback(json.url, { title: file.name });
                                }
                            } else {
                                console.error('Upload failed:', json.message);
                                alert('Upload failed: ' + json.message);
                            }
                        },
                        error: function() {
                            console.error('Upload failed');
                            alert('Upload failed. Please try again.');
                        }
                    });
                };

                input.click();
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            setup: function(editor) {
                editor.on('change', function() {
                    tinymce.triggerSave();
                });
            }
        });


    </script>


            <!-- Daftar kursus yang ada -->

            <!-- Display success or error messages -->
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($success_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <h3>Kursus Anda</h3>
            <?php if ($courses->num_rows > 0): ?>
                <div class="accordion" id="coursesAccordion">
                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading<?= $course['id'] ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $course['id'] ?>" aria-expanded="false" aria-controls="collapse<?= $course['id'] ?>">
                                    <?= htmlspecialchars($course['title']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= $course['id'] ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $course['id'] ?>" data-bs-parent="#coursesAccordion">
                                <div class="accordion-body">
                                    <!-- Detail kursus -->
                                    <?php if ($course['cover_image']): ?>
                                        <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" alt="Cover Image" class="img-fluid mb-3" style="max-width: 200px;">
                                    <?php endif; ?>
                                    <p><strong>Topik:</strong> <?= $course['description']; ?></p>

                                    <!-- Form untuk menambahkan bab -->
                                    <h5>Tambahkan Bab</h5>
                                    <?php
                                    // Ambil bab yang sudah ada
                                    $course_id = $course['id'];
                                    $sql_chapters = "SELECT * FROM chapters WHERE course_id = ? ORDER BY chapter_number ASC";
                                    $stmt_chapters = $conn->prepare($sql_chapters);
                                    $stmt_chapters->bind_param("i", $course_id);
                                    $stmt_chapters->execute();
                                    $chapters = $stmt_chapters->get_result();
                                    ?>
                                    <form method="POST" action="add_chapter.php">
                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                        <div class="mb-3">
                                            <label for="chapter_title_<?= $course['id'] ?>" class="form-label">Judul Bab</label>
                                            <input type="text" class="form-control" id="chapter_title_<?= $course['id'] ?>" name="chapter_title" required>
                                        </div>
                                        <button type="submit" name="add_chapter" class="btn btn-secondary">Tambahkan Bab</button>
                                    </form>
                                    <!-- Daftar bab -->
                                    <?php if ($chapters->num_rows > 0): ?>
                                        <ul class="list-group mt-3">
                                            <?php while ($chapter = $chapters->fetch_assoc()): ?>
                                                <li class="list-group-item">
                                                    <strong><?= htmlspecialchars($chapter['title']) ?></strong>
                                                    <!-- Tombol untuk menambahkan sub-bab -->
                                                    <button class="btn btn-sm btn-success float-end" onclick="showSubChapterForm(<?= $chapter['id'] ?>)">Tambah Sub-Bab</button>

                                                    <!-- Form dinamis untuk menambahkan sub-bab (akan muncul saat tombol ditekan) -->
                                                    <div id="subChapterForm_<?= $chapter['id'] ?>" style="display: none;" class="mt-3">
                                                        <form method="POST" action="add_sub_chapter.php">
                                                            <input type="hidden" name="chapter_id" value="<?= $chapter['id'] ?>">
                                                            <div class="mb-3">
                                                                <label for="sub_chapter_title_<?= $chapter['id'] ?>" class="form-label">Judul Sub-Bab</label>
                                                                <input type="text" class="form-control" id="sub_chapter_title_<?= $chapter['id'] ?>" name="sub_chapter_title" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="sub_chapter_content_<?= $chapter['id'] ?>" class="form-label">Konten Sub-Bab</label>
                                                                <textarea class="form-control tinymce-editor" name="sub_chapter_content" required></textarea>
                                                            </div>
                                                            <button type="submit" name="add_sub_chapter" class="btn btn-primary">Tambahkan Sub-Bab</button>
                                                        </form>
                                                    </div>

                                                    <!-- Daftar sub-bab -->
                                                    <?php
                                                    $chapter_id = $chapter['id'];
                                                    $sql_sub_chapters = "SELECT * FROM sub_chapters WHERE chapter_id = ? ORDER BY id ASC";
                                                    $stmt_sub_chapters = $conn->prepare($sql_sub_chapters);
                                                    $stmt_sub_chapters->bind_param("i", $chapter_id);
                                                    $stmt_sub_chapters->execute();
                                                    $sub_chapters = $stmt_sub_chapters->get_result();
                                                    ?>
                                                    <?php if ($sub_chapters->num_rows > 0): ?>
                                                        <ul class="list-group mt-2">
                                                            <?php while ($sub = $sub_chapters->fetch_assoc()): ?>
                                                                <li class="list-group-item">
                                                                    <?= htmlspecialchars($sub['title']) ?>
                                                                    <!-- Tombol untuk menambahkan kuis -->
                                                                    <!-- <a href="add_quiz.php?sub_chapter_id=<?= $sub['id'] ?>" class="btn btn-sm btn-info float-end">Tambah Quiz</a> -->
                                                                </li>
                                                            <?php endwhile; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                    <!-- Tombol untuk menambahkan kuis di bab -->
                                                    <a href="view_quizzes.php?chapter_id=<?= $chapter['id'] ?>" class="btn btn-sm btn-info mt-3">Lihat Quiz Bab <?= htmlspecialchars($chapter['chapter_number']) ?></a>
                                                    <a href="add_quiz.php?chapter_id=<?= $chapter['id'] ?>" class="btn btn-sm btn-info float-end">Tambah Quiz Bab</a>

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
                <p>Anda belum memiliki kursus. Tambahkan kursus pertama Anda di atas.</p>
            <?php endif; ?>

            <script type="text/javascript">
                tinymce.init({
                    selector: '.tinymce-editor',
                    setup: function(editor) {
                        editor.on('change', function() {
                            tinymce.triggerSave();
                        });
                    }
                });
            </script>


            <?php include '../includes/footer.php'; ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.text').richText();
                });
            </script>
            <script>
                function showSubChapterForm(chapterId) {
                    // Hide other forms
                    const forms = document.querySelectorAll('[id^="subChapterForm_"]');
                    forms.forEach(form => {
                        if (form.id !== 'subChapterForm_' + chapterId) {
                            form.style.display = 'none';
                        }
                    });

                    // Toggle the display of the selected form
                    const subChapterForm = document.getElementById('subChapterForm_' + chapterId);
                    if (subChapterForm.style.display === 'none' || subChapterForm.style.display === '') {
                        subChapterForm.style.display = 'block';
                        // Optional: scroll to the form
                        subChapterForm.scrollIntoView({
                            behavior: 'smooth'
                        });
                    } else {
                        subChapterForm.style.display = 'none';
                    }
                }
            </script>
        </div>
    </div>
</body>

</html>