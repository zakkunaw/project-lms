<?php
// student/dashboard.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

$student_id = $_SESSION['user_id'];

// Handle course enrollment
if (isset($_POST['enroll_course'])) {
    $course_id = intval($_POST['course_id']);

    // Check if already enrolled
    $stmt = $conn->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt->bind_param("ii", $student_id, $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        // Enroll in the course
        $stmt_insert = $conn->prepare("INSERT INTO enrollments (student_id, course_id, enrolled_at) VALUES (?, ?, NOW())");
        $stmt_insert->bind_param("ii", $student_id, $course_id);
        if ($stmt_insert->execute()) {
            $_SESSION['success_message'] = "You have successfully enrolled in the course.";
        } else {
            $_SESSION['error_message'] = "An error occurred while enrolling in the course.";
        }
        $stmt_insert->close();
    } else {
        $_SESSION['error_message'] = "You are already enrolled in this course.";
    }
    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: dashboard.php");
    exit();
}

// Fetch all available courses
$sql_available_courses = "
    SELECT c.*, u.username as instructor_name,
           CASE WHEN e.student_id IS NOT NULL THEN 1 ELSE 0 END as is_enrolled
    FROM courses c
    JOIN users u ON c.instructor_id = u.id
    LEFT JOIN enrollments e ON c.id = e.course_id AND e.student_id = ?
    ORDER BY c.id DESC
";
$stmt_available = $conn->prepare($sql_available_courses);
$stmt_available->bind_param("i", $student_id);
$stmt_available->execute();
$available_courses = $stmt_available->get_result();
$stmt_available->close();

$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LMS - STUDENT HATESE</title>
       <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        :root {
            --primary-color: #001f3f;
            --secondary-color: #003366;
            --accent-color: #0074D9;
        }

        body {
            background-color: #f8f9fa;
        }

        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="rgba(255,255,255,0.1)" d="M45.4,-76.8C59.2,-69.5,71.3,-58.2,78.9,-44.3C86.5,-30.4,89.5,-13.9,88.7,2.5C87.9,18.8,83.3,35,74.3,48.2C65.4,61.4,52,71.7,37.1,77.7C22.2,83.7,5.9,85.4,-10.1,83.1C-26.1,80.8,-41.8,74.4,-54.8,64.4C-67.8,54.4,-78.1,40.8,-83.7,25.1C-89.4,9.4,-90.4,-8.3,-85.6,-23.9C-80.8,-39.5,-70.2,-52.9,-56.6,-60.2C-43,-67.5,-26.4,-68.7,-10.1,-70.6C6.2,-72.6,31.6,-84.2,45.4,-76.8Z" transform="translate(100 100)"/></svg>') no-repeat center center;
            opacity: 0.1;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .search-container {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-container input {
            width: 100%;
            padding: 1rem 1.5rem;
            padding-left: 3rem;
            border-radius: 50px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }

        .search-container input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(0, 31, 63, 0.1);
            outline: none;
        }

        .search-container i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .announcement-banner {
            background: #e6f3ff;
            border-left: 4px solid var(--accent-color);
            padding: 1rem;
            margin-bottom: 2rem;
            border-radius: 5px;
        }

        .course-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        @keyframes marquee {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .marquee-container {
            overflow: hidden;
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 0;
            margin-bottom: 2rem;
        }

        .marquee-content {
            display: inline-block;
            animation: marquee 20s linear infinite;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .text-primary {
            color: var(--accent-color) !important;
        }
    </style>
</head>

<body>
    <?php include '../includes/student/navbar.php'; ?>

    <!-- Marquee Announcement -->
    <div class="marquee-container">
        <div class="marquee-content">
            🎓 Selamat datang di HATESE Learning Management System! Jelajahi kursus kami dan mulai perjalanan belajar Anda hari ini! 📚 Kursus baru ditambahkan secara berkala - Tetap ikuti untuk pembaruan! 
        </div>
    </div>

    <div class="container mt-5">
        <?php
        // Set timezone to Jakarta (WIB)
        date_default_timezone_set('Asia/Jakarta');

        // Determine greeting based on the current time
        $hour = date("H");

        if ($hour >= 5 && $hour < 12) {
            $greeting = "Ohayou"; // Morning greeting
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = "Konnichiwa"; // Afternoon greeting
        } else {
            $greeting = "Konbanwa"; // Evening greeting
        }
        ?>
        <!-- Welcome Banner -->
            <div class="welcome-banner mb-4">
                <h1 class="mb-3"><?= $greeting ?>, <?= htmlspecialchars($user['username']) ?>! 👋</h1>
                <p class="mb-0">Siap untuk melanjutkan perjalanan belajar Anda? Jelajahi kursus kami di bawah ini.</p>
            </div>
        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stats-card text-center">
                    <i class="bi bi-book text-primary mb-2" style="font-size: 2rem;"></i>
                    <h3>Available Courses</h3>
                    <p class="h2 mb-0 text-primary"><?= $available_courses->num_rows ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-card text-center">
                    <i class="bi bi-person-check text-primary mb-2" style="font-size: 2rem;"></i>
                    <h3>Your Enrollments</h3>
                    <p class="h2 mb-0 text-primary">
                        <?php
                        $enrolled_count = 0;
                        $available_courses->data_seek(0);
                        while ($course = $available_courses->fetch_assoc()) {
                            if ($course['is_enrolled']) $enrolled_count++;
                        }
                        echo $enrolled_count;
                        $available_courses->data_seek(0);
                        ?>
                    </p>
                </div>
            </div>
<!-- In the stats row, change the Learning Hours card -->
<div class="col-md-4 mb-3">
    <div class="stats-card text-center">
        <i class="bi bi-clock-history text-primary mb-2" style="font-size: 2rem;"></i>
        <h3>Learning Hours Today</h3>
        <p class="h2 mb-0 text-primary" id="learningHoursDisplay">0</p>
    </div>
</div>
        </div>

<!-- Search Bar -->
<div class="search-container">
    <i class="bi bi-search"></i>
    <input type="text" id="courseSearch" placeholder="Search for courses..." class="form-control">
</div>

<!-- Available Courses -->
<h3 class="mb-4">Materi tersedia</h3>
<div class="row" id="courseContainer">
    <?php while ($course = $available_courses->fetch_assoc()): ?>
        <div class="col-md-4 mb-4 course-card">
            <div class="card mb-4">
                <?php if ($course['cover_image']): ?>
                    <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" class="card-img-top img-fluid" alt="Cover Image">
                <?php else: ?>
                    <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-book" style="font-size: 4rem;"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                    <p class="card-text course-description" data-full-description="<?= htmlspecialchars($course['description']); ?>">
                        <?= htmlspecialchars(mb_strimwidth(strip_tags($course['description']), 0, 100, '...')); ?>
                    </p>
                    <p class="card-text"><strong>Instructor:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
                    <?php if ($course['is_enrolled']): ?>
                        <button class="btn btn-success mt-auto" disabled>Materi diambil!, silahkan cek kursus saya</button>
                    <?php else: ?>
                        <form method="POST" action="dashboard.php" class="mt-auto">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                            <button type="submit" name="enroll_course" class="btn btn-primary w-100">Ambil materi</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
    document.getElementById('courseSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const courses = document.querySelectorAll('.course-card');

        courses.forEach(course => {
            const title = course.querySelector('.card-title').textContent.toLowerCase();
            const description = course.querySelector('.course-description').textContent.toLowerCase();
            const instructor = course.querySelector('.card-text strong').nextSibling.textContent.toLowerCase();

            if (title.includes(searchTerm) || description.includes(searchTerm) || instructor.includes(searchTerm)) {
                course.parentElement.style.display = 'block';
            } else {
                course.parentElement.style.display = 'none';
            }
        });

        // Reset the layout to ensure grid stays intact
        const courseContainer = document.getElementById('courseContainer');
        courseContainer.style.display = 'none';
        courseContainer.offsetHeight; // Trigger reflow
        courseContainer.style.display = 'flex';
        courseContainer.style.flexWrap = 'wrap';
    });
</script>

    <?php include '../includes/footer.php'; ?>
    <style>
        .my-popup {
            font-family: Arial, sans-serif;
            text-align: left;
            line-height: 1.5;
            max-height: 400px;
            /* Atur tinggi maksimum */
            overflow-y: auto;
            /* Tambahkan scroll jika konten melebihi tinggi */
        }
    </style>
    <script>
        $(document).ready(function() {
            // Course search functionality
            $('#courseSearch').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.course-card').each(function() {
                    const title = $(this).find('.card-title').text().toLowerCase();
                    const description = $(this).find('.card-text').first().text().toLowerCase();
                    const instructor = $(this).find('.card-text').last().text().toLowerCase();

                    if (title.includes(searchTerm) || description.includes(searchTerm) || instructor.includes(searchTerm)) {
                        $(this).closest('.col-md-4').show();
                    } else {
                        $(this).closest('.col-md-4').hide();
                    }
                });
            });
        });

        function stripHtml(html) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = html;
            return tempDiv.textContent || tempDiv.innerText || '';
        }

        // Kemudian gunakan fungsi ini saat mencari deskripsi
        $(document).ready(function() {
            $('#courseSearch').on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                $('.course-card').each(function() {
                    const title = $(this).find('.card-title').text().toLowerCase();
                    const description = stripHtml($(this).find('.course-description').data('full-description')).toLowerCase();
                    const instructor = $(this).find('.card-text').last().text().toLowerCase();

                    if (title.includes(searchTerm) || description.includes(searchTerm) || instructor.includes(searchTerm)) {
                        $(this).closest('.col-md-4').show();
                    } else {
                        $(this).closest('.col-md-4').hide();
                    }
                });
            });

            // Show full description in a modal
            $('.course-description').on('click', function() {
                const fullDescription = $(this).data('full-description');
                Swal.fire({
                    title: 'Course Description',
                    html: `<div style="white-space: pre-wrap;">${fullDescription}</div>`,
                    icon: 'info',
                    confirmButtonText: 'Close',
                    width: '600px',
                    padding: '20px',
                    customClass: {
                        popup: 'my-popup'
                    }
                });
            });
        });

        // Learning hours tracking
$(document).ready(function() {
    // Initialize or get existing learning time
    const today = new Date().toLocaleDateString();
    let learningTime = JSON.parse(localStorage.getItem('learningTime')) || {};
    
    // Check if it's a new day
    if (!learningTime.date || learningTime.date !== today) {
        learningTime = {
            date: today,
            minutes: 0
        };
        localStorage.setItem('learningTime', JSON.stringify(learningTime));
    }

    // Update display
    function updateLearningHoursDisplay() {
        const hours = Math.floor(learningTime.minutes / 60);
        const minutes = learningTime.minutes % 60;
        const displayText = hours > 0 ? 
            `${hours}h ${minutes}m` : 
            `${minutes}m`;
        $('#learningHoursDisplay').text(displayText);
    }

    // Increment time every minute while page is open
    setInterval(() => {
        learningTime.minutes++;
        localStorage.setItem('learningTime', JSON.stringify(learningTime));
        updateLearningHoursDisplay();
    }, 60000); // Update every minute

    // Initial display
    updateLearningHoursDisplay();
});
    </script>
</body>


</html>