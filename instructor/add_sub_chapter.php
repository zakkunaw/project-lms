<?php
// instructor/add_sub_chapter.php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor'){
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

if(isset($_POST['add_sub_chapter'])){
    $chapter_id = intval($_POST['chapter_id']);
    $sub_chapter_title = $conn->real_escape_string($_POST['sub_chapter_title']);
    $sub_chapter_content = $conn->real_escape_string($_POST['sub_chapter_content']);

    // Insert ke tabel sub_chapters
    $stmt = $conn->prepare("INSERT INTO sub_chapters (chapter_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $chapter_id, $sub_chapter_title, $sub_chapter_content);
    if($stmt->execute()){
        $success = "Sub-bab berhasil ditambahkan.";
    } else {
        $error = "Terjadi kesalahan saat menambahkan sub-bab.";
    }
    $stmt->close();
    header("Location: manage_courses.php"); // Kembali ke manage_courses.php
    exit();
}
?>
