<?php
// courses/course.php
require_once '../includes/db_connect.php';

if (!isset($_GET['id']) || !isset($_GET['email']) || !isset($_GET['phone']) || !isset($_GET['full_name'])) {
    header("Location: ../index.php");
    exit();
}

$course_id = intval($_GET['id']);
$full_name = $conn->real_escape_string($_GET['full_name']);
$email = $conn->real_escape_string($_GET['email']);
$phone = $conn->real_escape_string($_GET['phone']);
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 5;

// Fetch course
$sql_course = "SELECT * FROM courses_guest WHERE id = ?";
$stmt_course = $conn->prepare($sql_course);
$stmt_course->bind_param("i", $course_id);
$stmt_course->execute();
$course = $stmt_course->get_result()->fetch_assoc();
$stmt_course->close();

if (!$course) {
    echo "Kursus tidak ditemukan.";
    exit();
}

// Fetch chapters
$sql_chapters = "SELECT * FROM chapters_guest WHERE course_id = ? ORDER BY id ASC LIMIT ?";
$stmt_chapters = $conn->prepare($sql_chapters);
$stmt_chapters->bind_param("ii", $course_id, $limit);
$stmt_chapters->execute();
$chapters = $stmt_chapters->get_result();
$stmt_chapters->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($course['title']) ?> - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
        }
        .course-header {
            background-image: url('<?= !empty($course['cover_image']) ? "../assets/images/courses/" . htmlspecialchars($course['cover_image']) : "/path/to/placeholder/image.jpg" ?>');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 60px 0;
            position: relative;
        }
        .course-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
        }
        .course-header .container {
            position: relative;
            z-index: 1;
        }
        .sidebar {
            position: sticky;
            top: 0;
            height: calc(100vh - 56px);
            overflow-y: auto;
            padding-top: 20px;
        }
        .chapter-list {
            list-style-type: none;
            padding-left: 0;
        }
        .chapter-list .chapter {
            margin-bottom: 15px;
        }
        .chapter-list .chapter-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .sub-chapter-list {
            list-style-type: none;
            padding-left: 20px;
        }
        .sub-chapter-list li {
            margin-bottom: 5px;
        }
        .btn-outline-primary {
            color: #0056b3;
            border-color: #0056b3;
        }
        .btn-outline-primary:hover {
            color: white;
            background-color: #0056b3;
        }
        .what-youll-learn {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .what-youll-learn ul {
            columns: 2;
            -webkit-columns: 2;
            -moz-columns: 2;
        }
        .instructor-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .instructor-info img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../hateselogo.png" alt="HKS Logo" height="30">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#roadmap">Roadmap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#course">Course</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php#contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="../login.php" class="btn btn-outline-primary me-2">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <header class="course-header">
        <div class="container">
            <h1 class="display-4"><?= htmlspecialchars($course['title']) ?></h1>
            <p class="lead">Belajar bahasa</p>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row">
            <main class="col-lg-8">
                <section class="what-youll-learn mb-4">
                    <h2 class="h4 mb-3">Apa yang akan dipelajari ?</h2>
                    <ul class="fa-ul">
                        <?php foreach (explode("\n", $course['what_youll_learn']) as $item): ?>
                            <li><span class="fa-li"><i class="fas fa-check"></i></span><?= htmlspecialchars($item) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </section>

                <section class="course-content mb-4">
                    <h2 class="h4 mb-3">Konten materi</h2>
                    <ul class="chapter-list">
                        <?php if ($chapters): ?>
                            <?php while ($chapter = $chapters->fetch_assoc()): ?>
                                <li class="chapter">
                                    <div class="chapter-title"><?= htmlspecialchars($chapter['title']) ?></div>
                                    <?php
                                    $sql_sub_chapters = "SELECT * FROM sub_chapters_guest WHERE chapter_id = ? ORDER BY id ASC LIMIT 5";
                                    $stmt_sub_chapters = $conn->prepare($sql_sub_chapters);
                                    $stmt_sub_chapters->bind_param("i", $chapter['id']);
                                    $stmt_sub_chapters->execute();
                                    $sub_chapters = $stmt_sub_chapters->get_result();
                                    $stmt_sub_chapters->close();
                                    ?>
                                    <ul class="sub-chapter-list">
                                        <?php if ($sub_chapters): ?>
                                            <?php while ($sub_chapter = $sub_chapters->fetch_assoc()): ?>
                                                <li class="clearfix">
                                                    <i class="far fa-play-circle me-2"></i>
                                                    <?= htmlspecialchars($sub_chapter['title']) ?>
                                                    <a href="view_sub_chapter.php?course_id=<?= $course_id ?>&sub_chapter_id=<?= $sub_chapter['id'] ?>&full_name=<?= urlencode($full_name) ?>&email=<?= urlencode($email) ?>&phone=<?= urlencode($phone) ?>" class="btn btn-sm btn-outline-primary float-end">Preview</a>
                                                </li>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </ul>
                    <?php if ($chapters->num_rows >= $limit): ?>
                        <div class="mt-3">
                            <p>To see more chapters, please <a href="../login.php">login</a> or <a href="../register.php">register</a>.</p>
                        </div>
                    <?php endif; ?>
                </section>

                <style>
                    .clearfix::after {
                        content: "";
                        display: table;
                        clear: both;
                    }
                </style>



                <section class="course-description">
                    <h2 class="h4 mb-3">Deskripsi kursus</h2>
                    <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                </section>
            </main>

            <aside class="col-lg-4 sidebar">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-lock"></i> Fitur kursus premium</h5>
                        <ul class="list-unstyled">
                            <?php foreach (explode("\n", $course['features']) as $feature): ?>
                                <li><i class="fas fa-check me-2"></i><?= htmlspecialchars($feature) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="https://wa.me/yourphonenumber?text=I%20want%20to%20access%20the%20course%20<?= urlencode($course['title']) ?>%20as%20a%20premium%20user" class="btn btn-primary btn-lg w-100 mt-3">Access Premium</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>