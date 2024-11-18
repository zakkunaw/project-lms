<?php
function fetchUserData($conn, $user_id) {
    $sql = "SELECT profile_picture, username, email, phone, created_at FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
?>
