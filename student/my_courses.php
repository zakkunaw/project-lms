<?php
// student/my_courses.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];

// Fetch enrolled courses with detailed information
$sql_enrolled_courses = "
    SELECT c.*, u.username as instructor_name, e.enrolled_at
    FROM courses c
    JOIN enrollments e ON c.id = e.course_id
    JOIN users u ON c.instructor_id = u.id
    WHERE e.student_id = ?
    ORDER BY e.enrolled_at DESC
";
$stmt_enrolled = $conn->prepare($sql_enrolled_courses);
$stmt_enrolled->bind_param("i", $student_id);
$stmt_enrolled->execute();
$enrolled_courses = $stmt_enrolled->get_result();
$stmt_enrolled->close();

// Handle course enrollment
if (isset($_POST['enroll_course'])) {
    $course_id = intval($_POST['course_id']);

    // Check if already enrolled
    $stmt_check = $conn->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt_check->bind_param("ii", $student_id, $course_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 0) {
        // Enroll in the course
        $stmt_enroll = $conn->prepare("INSERT INTO enrollments (student_id, course_id, enrolled_at) VALUES (?, ?, NOW())");
        $stmt_enroll->bind_param("ii", $student_id, $course_id);

        if ($stmt_enroll->execute()) {
            $_SESSION['success_message'] = "You have successfully enrolled in the course.";
        } else {
            $_SESSION['error_message'] = "An error occurred while enrolling in the course. Please try again.";
        }
        $stmt_enroll->close();
    } else {
        $_SESSION['error_message'] = "You are already enrolled in this course.";
    }
    $stmt_check->close();

    // Redirect to avoid form resubmission
    header("Location: my_courses.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <title>My Courses</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <?php include '../includes/student/navbar.php'; ?>
    <div class="container mt-5">
          <h1 class="mb-4">Kursus saya
            <button id="generateMotivation" class="btn btn-outline-success ml-3">Motivasi ❤</button>
        </h1>
                <div id="motivationalText" class="mb-4"></div>
  <div class="row mb-4">
        <div class="col-md-12">
            <input type="text" id="courseSearch" class="form-control" placeholder="Cari kursus...">
        </div>

    </div>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <h2 class="mb-3">Kursus terdaftar</h2>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
            <?php while ($course = $enrolled_courses->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <?php if ($course['cover_image']): ?>
                             <img src="../assets/images/courses/<?= htmlspecialchars($course['cover_image']) ?>" class="card-img-top img-fluid" alt="Cover image for <?= htmlspecialchars($course['title']) ?>" style="height: 100%; object-fit: cover;">
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
                            <div class="mt-auto">
                                <a href="course_overview.php?course_id=<?= $course['id'] ?>" class="btn btn-primary w-100 mb-2">Akses materi</a>
                                <button class="btn btn-success w-100" disabled>Terdaftar (<?= date('M d, Y', strtotime($course['enrolled_at'])) ?>)</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
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
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

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

    function stripHtml(html) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = html;
        return tempDiv.textContent || tempDiv.innerText || '';
    }

    $(document).ready(function() {
        // Pencarian Kursus
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

        // Pengurutan Kursus
        $('#courseSort').on('change', function() {
            const sortValue = $(this).val();
            $('.course-card').each(function() {
                const level = $(this).data('level');
                if (sortValue === 'all' || level === sortValue) {
                    $(this).closest('.col-md-4').show();
                } else {
                    $(this).closest('.col-md-4').hide();
                }
            });
        });
    });


    document.getElementById('generateMotivation').addEventListener('click', function() {
        const motivationalQuotes = [
            "Percayalah pada dirimu sendiri!",
            "Kamu bisa melakukannya!",
            "Jangan pernah menyerah!",
            "Tetap positif, bekerja keras, dan wujudkan!",
            "Kesuksesan bukanlah akhir, kegagalan bukanlah fatal: Keberanian untuk terus melanjutkan yang penting.",
            "Belajar bahasa Jepang adalah perjalanan yang menantang tapi menyenangkan!",
            "Setiap hari adalah kesempatan baru untuk belajar sesuatu yang baru.",
            "Bahasa Jepang membuka pintu ke budaya yang kaya dan menarik.",
            "Jangan takut membuat kesalahan, karena dari kesalahan kita belajar.",
            "Semakin banyak kamu berlatih, semakin baik kamu akan menjadi.",
            "Belajar bahasa Jepang akan memperluas wawasan dan peluangmu.",
            "Tetap semangat dan terus berusaha, hasilnya akan sepadan!",
            "Bahasa Jepang adalah kunci untuk memahami anime dan manga favoritmu.",
            "Setiap kata yang kamu pelajari membawa kamu lebih dekat ke kefasihan.",
            "Jangan biarkan kesulitan menghalangimu, teruslah maju!",
            "Belajar bahasa Jepang adalah investasi untuk masa depanmu.",
            "Nikmati proses belajar dan hargai setiap kemajuan kecil.",
            "Bahasa Jepang adalah jendela ke dunia baru yang menakjubkan.",
            "Kamu lebih kuat dari yang kamu kira, teruslah belajar!",
            "Setiap usaha yang kamu lakukan akan membawa kamu lebih dekat ke tujuanmu."
        ];
        const randomQuote = motivationalQuotes[Math.floor(Math.random() * motivationalQuotes.length)];
        const motivationalTextElement = document.getElementById('motivationalText');
        
        // Add Bootstrap alert class and fade-in animation
        motivationalTextElement.className = 'alert alert-info';
        motivationalTextElement.style.opacity = 0;
        motivationalTextElement.innerText = randomQuote;
        
        // Fade-in animation
        $(motivationalTextElement).animate({ opacity: 1 }, 1000);
    });
    </script>
</body>

</html>