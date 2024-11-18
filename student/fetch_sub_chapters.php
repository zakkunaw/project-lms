<?php
// fetch_sub_chapters.php
require_once '../includes/db_connect.php';

// Validasi `chapter_id`
$chapter_id = isset($_POST['chapter_id']) ? intval($_POST['chapter_id']) : 0;
$course_id = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;

if ($chapter_id === 0 || $course_id === 0) {
    echo "Invalid chapter ID or course ID.";
    exit();
}

// Query untuk mendapatkan sub-bab berdasarkan chapter_id
$sql_sub_chapters = "SELECT * FROM sub_chapters WHERE chapter_id = ? ORDER BY sub_chapter_number ASC";
$stmt_sub_chapters = $conn->prepare($sql_sub_chapters);
$stmt_sub_chapters->bind_param("i", $chapter_id);
$stmt_sub_chapters->execute();
$sub_chapters = $stmt_sub_chapters->get_result();
$stmt_sub_chapters->close();

// Cek apakah ada sub-bab
if ($sub_chapters->num_rows > 0) {
    // Loop melalui setiap sub-bab dan tampilkan dalam bentuk list
    while ($sub = $sub_chapters->fetch_assoc()): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($sub['title']) ?>
            <a href="course_content.php?course_id=<?= $course_id ?>&sub_chapter_id=<?= $sub['id'] ?>" 
               class="btn btn-sm btn-primary sub-chapter-link" 
               data-sub-chapter-number="<?= htmlspecialchars($sub['sub_chapter_number']) ?>">Access</a>
        </li>
    <?php endwhile;
} else {
    echo "<li class='list-group-item'>No sub-chapters available.</li>";
}
?>
