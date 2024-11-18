<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

$student_id = $_SESSION['user_id'];
$chapter_id = isset($_GET['chapter_id']) ? intval($_GET['chapter_id']) : 0;

if ($chapter_id === 0) {
    header("Location: my_courses.php");
    exit();
}

// Fetch quizzes for the chapter
$sql_quizzes = "SELECT q.*, c.course_id, c.title as chapter_title, co.title as course_title
                FROM quizzes q
                JOIN chapters c ON q.chapter_id = c.id
                JOIN courses co ON c.course_id = co.id
                WHERE q.chapter_id = ?";
$stmt_quizzes = $conn->prepare($sql_quizzes);
$stmt_quizzes->bind_param("i", $chapter_id);
$stmt_quizzes->execute();
$quizzes = $stmt_quizzes->get_result();
$stmt_quizzes->close();

if ($quizzes->num_rows === 0) {
    header("Location: my_courses.php");
    exit();
}

$quiz_data = [];
while ($quiz = $quizzes->fetch_assoc()) {
    $quiz_data[] = $quiz;
}

$course_id = $quiz_data[0]['course_id'];
$chapter_title = $quiz_data[0]['chapter_title'];
$course_title = $quiz_data[0]['course_title'];

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    $total_score = 0;
    $total_quizzes = count($quiz_data);
    
    // Calculate total score
    foreach ($quiz_data as $quiz) {
        $selected_answer = $_POST['answer_' . $quiz['id']] ?? '';
        if ($selected_answer === strtolower($quiz['correct_option'])) { // Ensure case-insensitive comparison
            $total_score += 100 / $total_quizzes;
        }
    }

    $is_passed = $total_score >= 80 ? 1 : 0;

    // Update or insert quiz completion
    $sql_upsert = "INSERT INTO quiz_completions (student_id, quiz_id, is_passed, completed_at)
                VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE is_passed = VALUES(is_passed), completed_at = VALUES(completed_at)";
    
    foreach ($quiz_data as $quiz) {
        $stmt_upsert = $conn->prepare($sql_upsert);
        $stmt_upsert->bind_param("iii", $student_id, $quiz['id'], $is_passed);
        $stmt_upsert->execute();
        $stmt_upsert->close();
    }

    // Return the score as JSON
    echo json_encode(['score' => round($total_score)]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Take Quiz - <?= htmlspecialchars($course_title) ?></title>
       <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-white">
    <?php include '../includes/student/navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Quiz: <?= htmlspecialchars($chapter_title) ?></h1>
        <p><strong>Course:</strong> <?= htmlspecialchars($course_title) ?></p>
        <form id="quizForm">
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="mb-4"><strong>Soal kategori: <?= $chapter_title ?></strong></div>
                    <div class="btn-group-horizontal w-100">
                        <?php for ($i = 0; $i < count($quiz_data); $i++): ?>
                            <button type="button" id="btn_<?php echo $i; ?>" class="btn btn-outline-secondary <?php echo $i === 0 ? 'active' : ''; ?>" onclick="navigateQuiz(<?php echo $i; ?>)"><?php echo $i + 1; ?></button>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php foreach ($quiz_data as $i => $quiz): ?>
                        <div class="quiz-question" id="question_<?php echo $i; ?>" style="display: <?php echo $i === 0 ? 'block' : 'none'; ?>">
                            <div class="mb-4"><strong><?php echo ($i + 1) . ". " .$quiz['question']; ?></strong></div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer_<?php echo $quiz['id']; ?>" value="a" required>
                                <label class="form-check-label">
                                    <?php echo htmlspecialchars($quiz['option_a']); ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer_<?php echo $quiz['id']; ?>" value="b" required>
                                <label class="form-check-label">
                                    <?php echo htmlspecialchars($quiz['option_b']); ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer_<?php echo $quiz['id']; ?>" value="c" required>
                                <label class="form-check-label">
                                    <?php echo htmlspecialchars($quiz['option_c']); ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer_<?php echo $quiz['id']; ?>" value="d" required>
                                <label class="form-check-label">
                                    <?php echo htmlspecialchars($quiz['option_d']); ?>
                                </label>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="navigateQuiz(-1)" style="display: <?php echo $i === 0 ? 'none' : 'inline-block'; ?>">
                                    <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                                </button>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-secondary" id="nextBtn" onclick="navigateQuiz(1)" style="display: <?php echo $i === count($quiz_data) - 1 ? 'none' : 'inline-block'; ?>">
                                        Selanjutnya
                                        <i class="fas fa-arrow-right ml-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" style="display: <?php echo $i === count($quiz_data) - 1 ? 'inline-block' : 'none'; ?>">
                                        Kirim
                                        <i class="fas fa-check ml-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </form>
        <div id="result" class="mt-4" style="display: none;"></div>
    </div>
    <?php include '../includes/footer.php'; ?>
 <script>
    let currentQuestion = 0; // Track the current question index
    const totalQuestions = <?= count($quiz_data) ?>; // Total number of questions

    function navigateQuiz(index) {
        const current = document.getElementById(`question_${currentQuestion}`);
        current.style.display = 'none';
        document.getElementById(`btn_${currentQuestion}`).classList.remove('active');

        if (index === 1 && currentQuestion < totalQuestions - 1) {
            currentQuestion++;
        } else if (index === -1 && currentQuestion > 0) {
            currentQuestion--;
        } else {
            currentQuestion = index;
        }

        const next = document.getElementById(`question_${currentQuestion}`);
        next.style.display = 'block';
        document.getElementById(`btn_${currentQuestion}`).classList.add('active');

        document.getElementById('prevBtn').style.display = currentQuestion === 0 ? 'none' : 'inline-block';
        document.getElementById('nextBtn').style.display = currentQuestion === totalQuestions - 1 ? 'none' : 'inline-block';
        document.getElementById('submitBtn').style.display = currentQuestion === totalQuestions - 1 ? 'inline-block' : 'none';
    }

    $(document).ready(function() {
        $('#quizForm').on('submit', function(e) {
            e.preventDefault();
            
            // Check for unanswered questions
            let unanswered = [];
            <?php foreach ($quiz_data as $i => $quiz): ?>
                if (!$(`input[name="answer_<?= $quiz['id'] ?>"]:checked`).length) {
                    unanswered.push(<?= $i + 1 ?>); // Store the question number
                }
            <?php endforeach; ?>

            if (unanswered.length > 0) {
                // Show notification for unanswered questions
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Anda belum menjawab pertanyaan nomor: ' + unanswered.join(', '),
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                });
            } else {
                // Show confirmation to complete the quiz
                Swal.fire({
                    title: 'Yakin Menyelesaikan Kuis?',
                    text: 'Apakah Anda yakin ingin menyelesaikan kuis ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, selesaikan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form data using AJAX if confirmed
                        $.ajax({
                            type: 'POST',
                            url: '', 
                            data: $(this).serialize() + '&ajax=1', 
                            dataType: 'json',
                            success: function(response) {
                                $('#quizForm').hide();
                                $('#result').show();

                                let message = `<h2 class="text-lg">Skor Anda: ${response.score}</h2>`;
                                
                                if (response.score === 0) {
                                    message += '<p class="text-danger">Anda harus mengulang kuis ini. Silakan coba lagi!</p>';
                                } else if (response.score === 100) {
                                    message += '<p class="text-success">Selamat! Anda mendapatkan skor sempurna!</p>';
                                    Swal.fire({
                                        title: 'Selamat!',
                                        text: 'Skor Anda 100! Silakan melanjutkan materi selanjutnya.',
                                        icon: 'success',
                                        confirmButtonText: 'Lanjut',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = `course_overview.php?course_id=<?= $course_id ?>`;
                                        }
                                    });
                                } else if (response.score >= 80) {
                                    message += '<p class="text-success">Selamat! Anda memenuhi syarat!</p>';
                                    setTimeout(() => {
                                        window.location.href = `course_overview.php?course_id=<?= $course_id ?>`;
                                    }, 2000);
                                } else {
                                    message += '<p class="text-warning">Anda belum memenuhi syarat. Silakan coba lagi!</p>';
                                }

                                if (response.score === 0 || response.score < 80) {
                                    message += '<button class="btn btn-primary" onclick="restartQuiz()">Kembali ke Kuis</button>';
                                }

                                $('#result').html(message);
                            },
                            error: function() {
                                alert('Terjadi kesalahan saat mengirim kuis.');
                            }
                        });
                    }
                });
            }
        });

        // Function to restart the quiz with SweetAlert confirmation
        window.restartQuiz = function() {
            Swal.fire({
                title: 'Mulai Ulang Kuis',
                text: 'Apakah Anda ingin mengulang kuis dari awal?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, mulai ulang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `course_overview.php?course_id=<?= $course_id ?>`;
                }
            });
        };
    });
</script>




</body>
</html>
