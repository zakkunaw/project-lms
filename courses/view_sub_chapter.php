<?php
// courses/view_sub_chapter.php
require_once '../includes/db_connect.php';

if (!isset($_GET['course_id']) || !isset($_GET['sub_chapter_id']) || !isset($_GET['email']) || !isset($_GET['phone'])) {
    header("Location: ../index.php");
    exit();
}

$course_id = intval($_GET['course_id']);
$sub_chapter_id = intval($_GET['sub_chapter_id']);
$email = $conn->real_escape_string($_GET['email']);
$phone = $conn->real_escape_string($_GET['phone']);

// Fetch sub-chapter
$sql_sub_chapter = "SELECT sc.*, c.title AS chapter_title 
                    FROM sub_chapters_guest sc
                    JOIN chapters_guest c ON sc.chapter_id = c.id
                    WHERE sc.id = ? AND c.course_id = ?";
$stmt_sub_chapter = $conn->prepare($sql_sub_chapter);
$stmt_sub_chapter->bind_param("ii", $sub_chapter_id, $course_id);
$stmt_sub_chapter->execute();
$sub_chapter = $stmt_sub_chapter->get_result()->fetch_assoc();
$stmt_sub_chapter->close();

if (!$sub_chapter) {
    echo "Sub-bab tidak ditemukan.";
    exit();
}

// Fetch all sub-chapters in the current chapter
$sql_all_sub_chapters = "SELECT id, title FROM sub_chapters_guest WHERE chapter_id = ?";
$stmt_all_sub_chapters = $conn->prepare($sql_all_sub_chapters);
$stmt_all_sub_chapters->bind_param("i", $sub_chapter['chapter_id']);
$stmt_all_sub_chapters->execute();
$all_sub_chapters = $stmt_all_sub_chapters->get_result();
$stmt_all_sub_chapters->close();

// Fetch next sub-chapter
$sql_next_sub_chapter = "SELECT id FROM sub_chapters_guest 
                         WHERE chapter_id = ? AND id > ? 
                         ORDER BY id ASC LIMIT 1";
$stmt_next_sub_chapter = $conn->prepare($sql_next_sub_chapter);
$stmt_next_sub_chapter->bind_param("ii", $sub_chapter['chapter_id'], $sub_chapter_id);
$stmt_next_sub_chapter->execute();
$next_sub_chapter = $stmt_next_sub_chapter->get_result()->fetch_assoc();
$stmt_next_sub_chapter->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include '../includes/header.php'; ?>
    <title><?= htmlspecialchars($sub_chapter['title']) ?> - LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --udemy-purple: #5624d0;
            --udemy-black: #1c1d1f;
            --udemy-gray: #6a6f73;
        }
        body {
            font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, Roboto, 'Segoe UI', Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            color: var(--udemy-black);
            background-color: #f7f9fa;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.08);
        }
        .sidebar {
            background-color: #fff;
            border-left: 1px solid #d1d7dc;
            height: calc(100vh - 56px);
            overflow-y: auto;
        }
        .content-area {
            padding: 2rem;
        }
        .chapter-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .sub-chapter-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .nav-link {
            color: var(--udemy-black);
            padding: 0.75rem 1rem;
        }
        .nav-link:hover, .nav-link:focus {
            background-color: #f7f9fa;
        }
        .nav-link.active {
            background-color: #e8e8e8;
            font-weight: 700;
        }
        .btn-udemy {
            background-color: var(--udemy-purple);
            color: white;
            font-weight: 700;
        }
        .btn-udemy:hover {
            background-color: #401b9c;
            color: white;
        }
        .footer {
            background-color: #1c1d1f;
            color: white;
            padding: 1rem 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a href="javascript:history.back()" class="navbar-brand">
                <i class="fas fa-arrow-left"></i> Back to Course
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-search"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-comments"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <main class="col-md-8 col-lg-9 content-area">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Course</a></li>
                        <li class="breadcrumb-item"><a href="#"><?= htmlspecialchars($sub_chapter['chapter_title']) ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($sub_chapter['title']) ?></li>
                    </ol>
                </nav>
                <h1 class="sub-chapter-title"><?= htmlspecialchars($sub_chapter['title']) ?></h1>
                <div class="content mb-4">
                    <?= $sub_chapter['content'] ?>
                </div>
                <div class="d-flex justify-content-between mt-5">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Previous Lesson
                    </a>
                    <?php if ($next_sub_chapter): ?>
                        <a href="view_sub_chapter.php?course_id=<?= $course_id ?>&sub_chapter_id=<?= $next_sub_chapter['id'] ?>&email=<?= urlencode($email) ?>&phone=<?= urlencode($phone) ?>" class="btn btn-udemy">
                            Next Lesson <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    <?php else: ?>
                        <button id="whatsappButton" class="btn btn-udemy">
                            Complete & Continue <i class="fas fa-check ms-2"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </main>

            <!-- Sidebar -->
            <aside class="col-md-4 col-lg-3 sidebar p-0">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0">Course content</h5>
                </div>
                <nav class="nav flex-column">
                    <?php while ($sub = $all_sub_chapters->fetch_assoc()): ?>
                        <a class="nav-link <?= $sub['id'] == $sub_chapter_id ? 'active' : '' ?>" 
                           href="view_sub_chapter.php?course_id=<?= $course_id ?>&sub_chapter_id=<?= $sub['id'] ?>&email=<?= urlencode($email) ?>&phone=<?= urlencode($phone) ?>">
                            <i class="fas fa-play-circle me-2"></i>
                            <?= htmlspecialchars($sub['title']) ?>
                        </a>
                    <?php endwhile; ?>
                </nav>
            </aside>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <a href="#" class="text-white text-decoration-none">Daftar Referensi</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('whatsappButton').addEventListener('click', function() {
        const messages = [
            {
                title: 'Selamat datang di LMS Hatese! 👋',
                text: 'Akses kursus penuh, fitur spesial, dan belajar lebih efektif. Daftar sekarang!'
            },
            {
                title: 'Siap untuk level berikutnya? 🌟',
                text: 'Materi terarah, siap sertifikasi. Daftar via WhatsApp dan mulai belajar!'
            }
        ];



        let index = 0;

        function showMessage() {
            Swal.fire({
                title: messages[index].title,
                text: messages[index].text,
                showCancelButton: index > 0,
                confirmButtonText: index === messages.length - 1 ? 'Daftar di WhatsApp' : 'Next',
                cancelButtonText: 'Previous',
                icon: 'info'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (index === messages.length - 1) {
                        window.location.href = 'https://wa.me/<?= urlencode($phone) ?>?text=<?= urlencode("Saya telah menyelesaikan sub-chapter ini.") ?>';
                    } else {
                        index++;
                        showMessage();
                    }
                } else if (result.isDismissed && index > 0) {
                    index--;
                    showMessage();
                }
            });
        }

        showMessage();
    });
    </script>
</body>
</html>