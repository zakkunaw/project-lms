<?php
// admin/manage_courses_guest.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
require_once '../includes/db_connect.php';

// Handle course creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_course'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    // Handle cover image upload
    $cover_image = '';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['cover_image']['type'], $allowed_types)) {
            $image_name = basename($_FILES['cover_image']['name']);
            $target_dir = "../assets/images/courses/";
            $target_file = $target_dir . uniqid() . "_" . $image_name;

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
                $cover_image = basename($target_file);
            } else {
                $notification = "Error uploading the image.";
                $notification_type = "danger";
            }
        } else {
            $notification = "Unsupported image type.";
            $notification_type = "danger";
        }
    }

    // Insert the course into the database
    $sql = "INSERT INTO courses_guest (title, description, cover_image) VALUES ('$title', '$description', '$cover_image')";
    if ($conn->query($sql) === TRUE) {
        $notification = "Course created successfully.";
        $notification_type = "success";
    } else {
        $notification = "Error: " . $conn->error;
        $notification_type = "danger";
    }
}

// Handle new chapter creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_chapter'])) {
    $course_id = $conn->real_escape_string($_POST['course_id']);
    $chapter_title = $conn->real_escape_string($_POST['chapter_title']);

    // Insert into chapters_guest
    $sql = "INSERT INTO chapters_guest (course_id, title, created_at) VALUES ('$course_id', '$chapter_title', NOW())";
    if ($conn->query($sql) === TRUE) {
        $notification = "Chapter created successfully.";
        $notification_type = "success";
    } else {
        $notification = "Error: " . $conn->error;
        $notification_type = "danger";
    }
}

// Handle new sub-chapter creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_sub_chapter'])) {
    $chapter_id = intval($_POST['chapter_id']);
    $sub_chapter_title = $conn->real_escape_string($_POST['sub_chapter_title']);
    $sub_chapter_content = $conn->real_escape_string($_POST['sub_chapter_content']);

    // Insert the new sub-chapter into the database
    $sql_insert = "INSERT INTO sub_chapters_guest (chapter_id, title, content) VALUES ('$chapter_id', '$sub_chapter_title', '$sub_chapter_content')";
    if ($conn->query($sql_insert) === TRUE) {
        $notification = "Sub-chapter created successfully.";
        $notification_type = "success";
    } else {
        $notification = "Error: " . $conn->error;
        $notification_type = "danger";
    }
}

// Fetch all courses and chapters
$sql_courses = "SELECT * FROM courses_guest";
$courses = $conn->query($sql_courses);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Manage Guest Courses - Admin</title>
    <!-- <script src="https://cdn.tiny.cloud/1/y6tj02ora74mku8eg7ugh9hx7qst3qu7kihrjv8zgtivaqzp/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> -->
     <script src="../assets/js/tinymce/js/tinymce/tinymce.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <button class="btn" id="menu-toggle"><i class="fas fa-bars"></i></button>
    
    <div class="d-flex" id="wrapper">
        <?php include '../includes/navbar.php'; ?>
    <div class="container mt-5">
        <h2>Manage Guest Courses</h2>

        <!-- Notification area -->
        <?php if (!empty($notification)): ?>
            <div class="alert alert-<?= $notification_type; ?>" role="alert">
                <?= htmlspecialchars($notification); ?>
            </div>
        <?php endif; ?>

        <!-- Form to create a new course -->
        <div class="card mb-4">
            <div class="card-header">Create New Guest Course</div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control tinymce-editor" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Cover Image</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image">
                    </div>
                    <button type="submit" name="create_course" class="btn btn-primary">Create Course</button>
                </form>
            </div>
        </div>

        <!-- Form to create a new chapter -->
        <div class="card mb-4">
            <div class="card-header">Create New Chapter</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select class="form-control" id="course_id" name="course_id" required>
                            <?php while ($course = $courses->fetch_assoc()): ?>
                                <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="chapter_title" class="form-label">Chapter Title</label>
                        <input type="text" class="form-control" id="chapter_title" name="chapter_title" required>
                    </div>
                    <button type="submit" name="create_chapter" class="btn btn-primary">Create Chapter</button>
                </form>
            </div>
        </div>

        <!-- Display courses and their chapters and sub-chapters -->
        <?php
        $sql_courses = "SELECT * FROM courses_guest";
        $courses = $conn->query($sql_courses);
        ?>
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
                            <!-- Display chapters -->
                            <?php
                            $course_id = $course['id'];
                            $sql_chapters = "SELECT * FROM chapters_guest WHERE course_id = $course_id";
                            $chapters = $conn->query($sql_chapters);
                            ?>
                            <?php if ($chapters->num_rows > 0): ?>
                                <ul class="list-group">
                                    <?php while ($chapter = $chapters->fetch_assoc()): ?>
                                        <li class="list-group-item">
                                            <strong><?= htmlspecialchars($chapter['title']) ?></strong>

                                            <button class="btn btn-sm btn-success float-end" onclick="showSubChapterForm(<?= $chapter['id'] ?>)">Add Sub-Chapter</button>
                                            <div id="subChapterForm_<?= $chapter['id'] ?>" style="display: none;" class="mt-3">
                                                <form method="POST" action="">
                                                    <input type="hidden" name="chapter_id" value="<?= $chapter['id'] ?>">
                                                    <div class="mb-3">
                                                        <label for="sub_chapter_title_<?= $chapter['id'] ?>" class="form-label">Sub-Chapter Title</label>
                                                        <input type="text" class="form-control" id="sub_chapter_title_<?= $chapter['id'] ?>" name="sub_chapter_title" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="sub_chapter_content_<?= $chapter['id'] ?>" class="form-label">Content</label>
                                                        <textarea class="form-control tinymce-editor" id="sub_chapter_content_<?= $chapter['id'] ?>" name="sub_chapter_content" required></textarea>
                                                    </div>
                                                    <button type="submit" name="create_sub_chapter" class="btn btn-primary">Add Sub-Chapter</button>
                                                </form>
                                            </div>
                                            <?php
                                            $chapter_id = $chapter['id'];
                                            $sql_sub_chapters = "SELECT * FROM sub_chapters_guest WHERE chapter_id = $chapter_id";
                                            $sub_chapters = $conn->query($sql_sub_chapters);
                                            ?>
                                            <?php if ($sub_chapters->num_rows > 0): ?>
                                                <ul class="list-group mt-2">
                                                    <?php while ($sub = $sub_chapters->fetch_assoc()): ?>
                                                        <li class="list-group-item">
                                                            <?= htmlspecialchars($sub['title']) ?>
                                                        </li>
                                                    <?php endwhile; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <p>No chapters available for this course.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
<script type="text/javascript">
tinymce.init({
    selector: '.tinymce-editor',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | image media | help',
    height: 500,
    automatic_uploads: true,
    images_upload_url: '../upload.php',
    images_reuse_filename: true,
    media_live_embeds: true,
    file_picker_types: 'file image media',
    file_picker_callback: function(callback, value, meta) {
        var input = document.createElement('input');
        input.setAttribute('type', 'file');
        
        if (meta.filetype === 'image') {
            input.setAttribute('accept', 'image/*');
        } else if (meta.filetype === 'media') {
            input.setAttribute('accept', 'video/*,audio/*');
        }
        
        input.onchange = function() {
            var file = this.files[0];
            var formData = new FormData();
            formData.append('file', file);
            
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (result.location) {
                    if (meta.filetype === 'media') {
                        if (file.type.includes('video')) {
                            callback(result.location, { title: file.name, 'data-mce-type': 'video', controls: true });
                        } else if (file.type.includes('audio')) {
                            callback(result.location, { title: file.name, 'data-mce-type': 'audio', controls: true });
                        }
                    } else {
                        callback(result.location, { title: file.name });
                    }
                }
            })
            .catch(error => {
                console.error('Upload failed:', error);
            });
        };
        
        input.click();
    },
    setup: function(editor) {
        editor.on('change', function() {
            tinymce.triggerSave();
        });
    },
    extended_valid_elements: 'audio[controls|preload|data-setup],source[src|type],video[*]',
    convert_urls: false,
    relative_urls: false,
    remove_script_host: false,
    media_url_resolver: function(data, resolve) {
        resolve({
            html: '<video controls><source src="' + data.url + '" type="video/mp4"></video>'
        });
    }
});

</script>

        <?php include '../includes/footer.php'; ?>
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