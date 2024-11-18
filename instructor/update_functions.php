<?php
require_once '../includes/db_connect.php';

function update_course($conn, $id, $title, $description, $cover_image, $existing_cover_image) {
    if ($cover_image) {
        $target_dir = "../assets/images/courses/";
        $target_file = $target_dir . basename($cover_image);
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file);
    } else {
        $cover_image = $existing_cover_image;
    }

    $stmt = $conn->prepare("UPDATE courses SET title = ?, description = ?, cover_image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $description, $cover_image, $id);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Error updating course'];
    }
}

function update_chapter($conn, $id, $title, $chapter_number) {
    $stmt = $conn->prepare("UPDATE chapters SET title = ?, chapter_number = ? WHERE id = ?");
    $stmt->bind_param("sii", $title, $chapter_number, $id);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Error updating chapter'];
    }
}

function update_sub_chapter($conn, $id, $title, $content) {
    $stmt = $conn->prepare("UPDATE sub_chapters SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $content, $id);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Error updating sub-chapter'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'update_course') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $cover_image = $_FILES['cover_image']['name'];
        $existing_cover_image = $_POST['existing_cover_image'];
        $result = update_course($conn, $id, $title, $description, $cover_image, $existing_cover_image);
        echo json_encode($result);
    }

    if ($action == 'update_chapter') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $chapter_number = $_POST['chapter_number'];
        $result = update_chapter($conn, $id, $title, $chapter_number);
        echo json_encode($result);
    }

    if ($action == 'update_sub_chapter') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $result = update_sub_chapter($conn, $id, $title, $content);
        echo json_encode($result);
    }
}
?>