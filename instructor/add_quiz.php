<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.php");
    exit();
}

require_once '../includes/db_connect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['chapter_id'])) {
    $chapter_id = intval($_GET['chapter_id']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Tambah Quiz</title>
    <script src="../assets/js/tinymce.min.js"></script>
     <script src="https://cdn.tiny.cloud/1/y6tj02ora74mku8eg7ugh9hx7qst3qu7kihrjv8zgtivaqzp/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .quiz-set {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .form-control {
            margin-bottom: 10px;
        }
        #loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div id="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="d-flex" id="wrapper">
        <?php include '../includes/instruktur/navbar.php'; ?>

        <div class="container-fluid mt-5">
            <h2>Tambah Quiz</h2>
            
            <div id="alertContainer"></div>

            <form id="quizForm" method="POST">
                <input type="hidden" name="chapter_id" value="<?= htmlspecialchars($chapter_id) ?>">
                
                <div id="questions-form">
                    <div class="quiz-set">
                        <h5>Pertanyaan 1</h5>
                        <div class="mb-3">
                            <label for="question_0" class="form-label">Pertanyaan</label>
                            <textarea class="form-control tinymce-editor" id="question_0" name="question[]" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="option_a_0" class="form-label">Pilihan A</label>
                            <input type="text" class="form-control" id="option_a_0" name="option_a[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="option_b_0" class="form-label">Pilihan B</label>
                            <input type="text" class="form-control" id="option_b_0" name="option_b[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="option_c_0" class="form-label">Pilihan C</label>
                            <input type="text" class="form-control" id="option_c_0" name="option_c[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="option_d_0" class="form-label">Pilihan D</label>
                            <input type="text" class="form-control" id="option_d_0" name="option_d[]" required>
                        </div>
                        <div class="mb-3">
                            <label for="correct_option_0" class="form-label">Pilihan Benar</label>
                            <select class="form-control" id="correct_option_0" name="correct_option[]" required>
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mt-3" onclick="addQuestion()">Tambah Pertanyaan</button>
                <button type="submit" class="btn btn-primary mt-3" id="submitQuiz">Simpan Quiz</button>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
        function addQuestion() {
            const questionForm = document.getElementById('questions-form');
            const questionCount = questionForm.children.length;
            const newQuestion = `
                <div class="quiz-set">
                    <h5>Pertanyaan ${questionCount + 1}</h5>
                    <div class="mb-3">
                        <label for="question_${questionCount}" class="form-label">Pertanyaan</label>
                        <textarea class="form-control tinymce-editor" id="question_${questionCount}" name="question[]" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="option_a_${questionCount}" class="form-label">Pilihan A</label>
                        <input type="text" class="form-control" id="option_a_${questionCount}" name="option_a[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_b_${questionCount}" class="form-label">Pilihan B</label>
                        <input type="text" class="form-control" id="option_b_${questionCount}" name="option_b[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_c_${questionCount}" class="form-label">Pilihan C</label>
                        <input type="text" class="form-control" id="option_c_${questionCount}" name="option_c[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_d_${questionCount}" class="form-label">Pilihan D</label>
                        <input type="text" class="form-control" id="option_d_${questionCount}" name="option_d[]" required>
                    </div>
                    <div class="mb-3">
                        <label for="correct_option_${questionCount}" class="form-label">Pilihan Benar</label>
                        <select class="form-control" id="correct_option_${questionCount}" name="correct_option[]" required>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>
                </div>
            `;
            questionForm.insertAdjacentHTML('beforeend', newQuestion);
            tinymce.init({
                selector: `#question_${questionCount}`,
                height: 300,
                plugins: 'advlist autolink lists link image charmap print preview anchor',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                menubar: false,
                branding: false,
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
        }

        // Ajax form submission
        $('#quizForm').on('submit', function(e) {
            e.preventDefault();
            
            // Show loading spinner
            $('#loading').show();
            
            // Save TinyMCE content
            tinymce.triggerSave();
            
            // Collect form data
            let formData = new FormData(this);
            
            $.ajax({
                url: 'save_quiz.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loading').hide();
                    try {
                        let result = JSON.parse(response);
                        if(result.status === 'success') {
                            $('#alertContainer').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${result.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                            setTimeout(() => {
                                window.location.href = 'manage_courses.php';
                            }, 2000);
                        } else {
                            $('#alertContainer').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${result.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            `);
                        }
                    } catch(e) {
                        $('#alertContainer').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Terjadi kesalahan sistem
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#loading').hide();
                    $('#alertContainer').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            Gagal menghubungi server
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            });
        });
    </script>
</body>
</html>