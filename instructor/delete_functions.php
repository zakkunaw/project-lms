<?php
require_once '../includes/db_connect.php';

function delete_course($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Error deleting course'];
    }
}

function delete_chapter($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM chapters WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Error deleting chapter'];
    }
}

function delete_sub_chapter($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM sub_chapters WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Error deleting sub-chapter'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'delete_course') {
        $id = $_POST['id'];
        $result = delete_course($conn, $id);
        echo json_encode($result);
    }

    if ($action == 'delete_chapter') {
        $id = $_POST['id'];
        $result = delete_chapter($conn, $id);
        echo json_encode($result);
    }

    if ($action == 'delete_sub_chapter') {
        $id = $_POST['id'];
        $result = delete_sub_chapter($conn, $id);
        echo json_encode($result);
    }
}
?>