<?php
// includes/navbar.php
// Pastikan session telah dimulai di halaman yang menyertakan navbar ini
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="/index.php">LMS</a>

        <!-- Toggler/collapsibe Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" 
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['role'] == 'admin'): ?>
                    <!-- Links untuk Admin -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/manage_users.php">Kelola Pengguna</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/manage_courses.php">Kelola Kursus</a>
                        </li>
                    </ul>
                <?php elseif($_SESSION['role'] == 'instructor'): ?>
                    <!-- Links untuk Instruktur -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="../instructor/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../instructor/manage_courses.php">Kelola Kursus</a>
                        </li>
                    </ul>
                <?php elseif($_SESSION['role'] == 'student'): ?>
                    <!-- Links untuk Siswa -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="../student/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../student/my_courses.php">Kursus Saya</a>
                        </li>
                    </ul>
                <?php endif; ?>
            <?php else: ?>
                <!-- Links untuk Tamu -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">Kursus</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login.php">Login</a>
                    </li>
                </ul>
            <?php endif; ?>

            <!-- Bagian Kanan Navbar -->
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- Menampilkan Nama Pengguna (Opsional) -->
                    <?php
                        // Ambil username dari session atau database
                        // Pastikan saat login, username juga disimpan di session
                        $username = $_SESSION['username'] ?? 'Pengguna';
                    ?>
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            <?php echo htmlspecialchars($username); ?>
                        </span>
                    </li>
                    <!-- Tombol Logout -->
                    <li class="nav-item">
                        <a class="btn btn-outline-danger" href="../logout.php">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
