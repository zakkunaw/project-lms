<?php
// view_all_courses.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

// Fetch all courses for the instructor
$instructor_id = $_SESSION['user_id'];
$sql_courses = "SELECT * FROM courses WHERE instructor_id = ?";
$stmt_courses = $conn->prepare($sql_courses);
$stmt_courses->bind_param("i", $instructor_id);
$stmt_courses->execute();
$courses = $stmt_courses->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>View All Courses Data - Instructor</title>
    <!-- <script src="../assets/js/tinymce/js/tinymce/tinymce.min.js"></script> -->
    <script src="https://cdn.tiny.cloud/1/y6tj02ora74mku8eg7ugh9hx7qst3qu7kihrjv8zgtivaqzp/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="d-flex" id="wrapper">
        <?php include '../includes/instruktur/navbar.php'; ?>
        <div class="container-fluid px-4 py-5">
            <h2 class="mb-4">All Courses Data</h2>

            <!-- Courses Table -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Courses</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Cover Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($course = $courses->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($course['id']) ?></td>
                                        <td><?= htmlspecialchars($course['title']) ?></td>
                                        <td><?= substr(strip_tags($course['description']), 0, 100) ?>...</td>
                                        <td>
                                            <?php if ($course['cover_image']): ?>
                                                <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" 
                                                     alt="Cover" class="img-thumbnail" style="max-width: 100px">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm edit-course" 
                                                    data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                                    data-id="<?= $course['id'] ?>">
                                                Edit
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-course" 
                                             data-id="<?= $course['id'] ?>">
                                              Delete
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <?php
                                    // Fetch chapters for this course
                                    $sql_chapters = "SELECT * FROM chapters WHERE course_id = ? ORDER BY chapter_number ASC";
                                    $stmt_chapters = $conn->prepare($sql_chapters);
                                    $stmt_chapters->bind_param("i", $course['id']);
                                    $stmt_chapters->execute();
                                    $chapters = $stmt_chapters->get_result();
                                    ?>
                                    
                                    <?php while ($chapter = $chapters->fetch_assoc()): ?>
                                        <tr class="table-light">
                                            <td></td>
                                            <td colspan="3">
                                                <i class="fas fa-book me-2"></i>
                                                Chapter <?= htmlspecialchars($chapter['chapter_number']) ?>: 
                                                <?= htmlspecialchars($chapter['title']) ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-sm edit-chapter"
                                                        data-bs-toggle="modal" data-bs-target="#editChapterModal"
                                                        data-id="<?= $chapter['id'] ?>">
                                                    Edit
                                                </button>
                                    <button class="btn btn-danger btn-sm delete-chapter"
                                            data-id="<?= $chapter['id'] ?>">
                                        Delete
                                    </button>
                                            </td>
                                        </tr>

                                        <?php
                                        // Fetch sub-chapters for this chapter
                                        $sql_sub_chapters = "SELECT * FROM sub_chapters WHERE chapter_id = ? ORDER BY id ASC";
                                        $stmt_sub_chapters = $conn->prepare($sql_sub_chapters);
                                        $stmt_sub_chapters->bind_param("i", $chapter['id']);
                                        $stmt_sub_chapters->execute();
                                        $sub_chapters = $stmt_sub_chapters->get_result();
                                        ?>

                                        <?php while ($sub = $sub_chapters->fetch_assoc()): ?>
                                            <tr class="table-light">
                                                <td></td>
                                                <td></td>
                                                <td colspan="2">
                                                    <i class="fas fa-angle-right me-3"></i>
                                                    <?= htmlspecialchars($sub['title']) ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm edit-sub-chapter"
                                                            data-bs-toggle="modal" data-bs-target="#editSubChapterModal"
                                                            data-id="<?= $sub['id'] ?>">
                                                        Edit
                                                    </button>
                                                <button class="btn btn-danger btn-sm delete-sub-chapter"
                                                        data-id="<?= $sub['id'] ?>">
                                                    Delete
                                                </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endwhile; ?>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCourseForm">
                    <input type="hidden" id="courseId" name="id">
                    <input type="hidden" id="existingCoverImage" name="existing_cover_image">
                    <div class="mb-3">
                        <label for="courseTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="courseTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="courseDescription" class="form-label">Description</label>
                        <textarea class="tinymce-editor" id="courseDescription" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="courseCover" class="form-label">Cover Image</label>
                        <input type="file" class="form-control" id="courseCover" name="cover_image">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCourseChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Chapter Modal -->
<div class="modal fade" id="editChapterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Chapter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editChapterForm">
                    <input type="hidden" id="chapterId" name="id">
                    <div class="mb-3">
                        <label for="chapterTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="chapterTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="chapterNumber" class="form-label">Chapter Number</label>
                        <input type="number" class="form-control" id="chapterNumber" name="chapter_number" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveChapterChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Sub-Chapter Modal -->
<div class="modal fade" id="editSubChapterModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sub-Chapter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSubChapterForm">
                    <input type="hidden" id="subChapterId" name="id">
                    <div class="mb-3">
                        <label for="subChapterTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="subChapterTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="subChapterContent" class="form-label">Content</label>
                        <textarea class="tinymce-editor" id="subChapterContent" name="content"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveSubChapterChanges">Save changes</button>
            </div>
        </div>
    </div>
</div>

    <script>
 $(document).ready(function() {
     tinymce.init({  
            selector: '.tinymce-editor',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | link image media',
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

    // Edit Course
    $('.edit-course').click(function() {
        var courseId = $(this).data('id');
        $.getJSON('get_course_data.php', {id: courseId}, function(data) {
            $('#courseId').val(data.id);
            $('#courseTitle').val(data.title);
            tinymce.get('courseDescription').setContent(data.description);
            $('#existingCoverImage').val(data.cover_image);
            $('#editCourseModal').modal('show');
        });
    });

    // Edit Chapter
    $('.edit-chapter').click(function() {
        var chapterId = $(this).data('id');
        $.getJSON('get_chapter_data.php', {id: chapterId}, function(data) {
            $('#chapterId').val(data.id);
            $('#chapterTitle').val(data.title);
            $('#chapterNumber').val(data.chapter_number);
            $('#editChapterModal').modal('show');
        });
    });

    // Edit Sub-Chapter
    $('.edit-sub-chapter').click(function() {
        var subChapterId = $(this).data('id');
        $.getJSON('get_sub_chapter_data.php', {id: subChapterId}, function(data) {
            $('#subChapterId').val(data.id);
            $('#subChapterTitle').val(data.title);
            tinymce.get('subChapterContent').setContent(data.content);
            $('#editSubChapterModal').modal('show');
        });
    });

    // Save Course Changes
    $('#saveCourseChanges').click(function() {
        var formData = new FormData($('#editCourseForm')[0]);
        formData.append('action', 'update_course');
        $.ajax({
            url: 'update_functions.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#editCourseModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error updating course: ' + result.message);
                }
            }
        });
    });

    // Save Chapter Changes
    $('#saveChapterChanges').click(function() {
        var formData = new FormData($('#editChapterForm')[0]);
        formData.append('action', 'update_chapter');
        $.ajax({
            url: 'update_functions.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#editChapterModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error updating chapter: ' + result.message);
                }
            }
        });
    });

    // Save Sub-Chapter Changes
    $('#saveSubChapterChanges').click(function() {
        var formData = new FormData($('#editSubChapterForm')[0]);
        formData.append('action', 'update_sub_chapter');
        $.ajax({
            url: 'update_functions.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#editSubChapterModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error updating sub-chapter: ' + result.message);
                }
            }
        });
    });

    // Delete Course
    $('.delete-course').click(function() {
        if (confirm('Are you sure you want to delete this course?')) {
            var courseId = $(this).data('id');
            $.post('delete_functions.php', {action: 'delete_course', id: courseId}, function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    location.reload();
                } else {
                    alert('Error deleting course: ' + result.message);
                }
            });
        }
    });

    // Delete Chapter
    $('.delete-chapter').click(function() {
        if (confirm('Are you sure you want to delete this chapter?')) {
            var chapterId = $(this).data('id');
            $.post('delete_functions.php', {action: 'delete_chapter', id: chapterId}, function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    location.reload();
                } else {
                    alert('Error deleting chapter: ' + result.message);
                }
            });
        }
    });

    // Delete Sub-Chapter
    $('.delete-sub-chapter').click(function() {
        if (confirm('Are you sure you want to delete this sub-chapter?')) {
            var subChapterId = $(this).data('id');
            $.post('delete_functions.php', {action: 'delete_sub_chapter', id: subChapterId}, function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    location.reload();
                } else {
                    alert('Error deleting sub-chapter: ' + result.message);
                }
            });
        }
    });
});
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>