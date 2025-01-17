<?php
// instructor/add_chapter.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'instructor') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

if (isset($_POST['add_chapter'])) {
    $course_id = intval($_POST['course_id']);
    $chapter_title = $conn->real_escape_string($_POST['chapter_title']);
    $chapter_content = $conn->real_escape_string($_POST['chapter_content']); // Tambahkan ini

    // Tentukan nomor bab berikutnya
    $stmt_order = $conn->prepare("SELECT COUNT(*) as total FROM chapters WHERE course_id = ?");
    $stmt_order->bind_param("i", $course_id);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result()->fetch_assoc();
    $chapter_number = $result_order['total'] + 1;
    $stmt_order->close();

    // Insert ke tabel chapters
    $stmt = $conn->prepare("INSERT INTO chapters (course_id, title, content, chapter_number) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $course_id, $chapter_title, $chapter_content, $chapter_number);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Bab berhasil ditambahkan.";
    } else {
        $_SESSION['error_message'] = "Gagal menambahkan bab.";
    }



    $stmt->close();
    header("Location: manage_courses.php"); // Kembali ke manage_courses.php
    exit();
}
