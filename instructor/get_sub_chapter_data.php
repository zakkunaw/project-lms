<?php
require_once '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM sub_chapters WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sub_chapter = $result->fetch_assoc();
    echo json_encode($sub_chapter);
}
?>