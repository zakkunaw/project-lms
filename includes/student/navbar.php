<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php'; // Include the functions file

// Fetch user data
$user_id = $_SESSION['user_id'];
$user = fetchUserData($conn, $user_id);
?>

<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
       <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        @media (min-width: 768px) {
            .mobile-menu {
                display: none;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <a class="navbar-brand" href="#">
            <img alt="Logo with HKS text and circular design" class="d-inline-block align-top" height="50" src="../hateselogo.png" width="50" />
        </a>
        <button aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler" data-target="#navbarNav" data-toggle="collapse" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_courses.php">Kursus Saya</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../student/profile_view.php?user_id=<?= $user_id ?>">
                        <?php if ($user['profile_picture']): ?>
                            <img src="../student/student_image/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" class="rounded-circle" height="30" width="30">
                        <?php else: ?>
                            <img src="../student/student_image/default.jpeg" alt="Profile Picture" class="rounded-circle" height="30" width="30">
                        <?php endif; ?>
                        <?= htmlspecialchars($user['username']) ?>
                    </a>
                </li>
                <li class="nav-item">
                    <script>
                        function confirmLogout() {
                            Swal.fire({
                                title: 'Konfirmasi Logout',
                                text: "Apakah Anda yakin ingin logout?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Logout!',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Show success message before redirecting
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Logout Berhasil!',
                                        text: 'Anda telah berhasil logout.',
                                        showConfirmButton: false,
                                        timer: 1500 // Auto close after 1.5 seconds
                                    }).then(() => {
                                        // Redirect to logout.php after the success message
                                        window.location.href = "../logout.php"; // Update the path to your logout file if necessary
                                    });
                                }
                            });
                        }
                    </script>
                    <a href="#" class="btn btn-danger" onclick="confirmLogout()">Logout</a>

                </li>
            </ul>
        </div>
    </nav>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>