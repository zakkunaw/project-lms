<?php
// instructor/add_sub_chapter.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';
if (isset($_POST['add_sub_chapter'])) {
    $chapter_id = intval($_POST['chapter_id']);
    $sub_chapter_title = $conn->real_escape_string($_POST['sub_chapter_title']);
    
    // Get the raw content from TinyMCE
    $sub_chapter_content = $_POST['sub_chapter_content'];
    
    // Bersihkan konten tanpa menghilangkan HTML dan styling
    $sub_chapter_content = trim($sub_chapter_content);
    
    // Insert into sub_chapters table
    $stmt = $conn->prepare("INSERT INTO sub_chapters (chapter_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $chapter_id, $sub_chapter_title, $sub_chapter_content);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Sub-chapter added successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to add sub-chapter.";
    }

    $stmt->close();
    header("Location: manage_courses.php");
    exit();
}
?>