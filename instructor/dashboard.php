<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'instructor') {
    header("Location: /login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Welcome, Instructor!</h1>
            <p class="lead">You are logged in as: <?php echo $_SESSION['role']; ?></p>
            <hr class="my-4">
            <p>This is your dashboard where you can manage your courses and students.</p>
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>