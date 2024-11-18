<?php
// process_guest_access.php
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = intval($_POST['course_id']);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Insert data into guest_access table
    $sql_insert = "INSERT INTO guest_access (full_name, email, phone) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    
    if ($stmt_insert) {
        $stmt_insert->bind_param("sss", $full_name, $email, $phone);
        $stmt_insert->execute();
        $stmt_insert->close();
    } else {
        // Handle error if prepared statement fails
        die("Error preparing statement: " . $conn->error);
    }

    // Redirect to course page with email, phone, and full_name parameters
    header("Location: courses/course.php?id=$course_id&full_name=$full_name&email=$email&phone=$phone&limit=5");
    exit();
}
?>
