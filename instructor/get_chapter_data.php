<?php
require_once '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM chapters WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $chapter = $result->fetch_assoc();
    echo json_encode($chapter);
}
?>