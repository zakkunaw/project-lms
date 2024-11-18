<!-- navbar.php -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>

    /* CSS untuk sidebar */
    #wrapper {
        display: flex;
        align-items: stretch;
        transition: all 0.5s ease;
    }

    #sidebar-wrapper {
        min-height: 95vh;
        width: 250px;
        background-color: #343a40;
        color: #fff;
        transition: all 0.5s ease;
        border-radius: 20px;
        margin-left: 15px; /* Jarak dari tepi kiri */
        margin-top: 15px;
    }

    #sidebar-wrapper .list-group-item {
        background-color: #343a40;
        color: #fff;
        border: none;
        padding: 15px;
        font-size: 16px;
    }

    #sidebar-wrapper .list-group-item:hover {
        background-color: #495057;
    }

    /* Tombol hamburger dan close untuk mobile */
    #menu-toggle {
        display: none;
    }

    #close-sidebar {
        display: none;
    }

    @media (max-width: 768px) {
        #sidebar-wrapper {
            position: fixed;
            z-index: 1000;
            margin-left: -250px;
            top: 0;
            border-radius: 0; /* Hilangkan radius di mobile */
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        #menu-toggle {
            display: block;
            font-size: 20px;
            color: #343a40;
            cursor: pointer;
            padding: 10px;
            background-color: transparent;
            border: none;
            outline: none;
            position: relative;
            z-index: 1050;
        }

        #close-sidebar {
            display: block;
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff;
            background-color: transparent;
            border: none;
            font-size: 20px;
        }
    }
</style>


<div id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4">
        <a class="navbar-brand text-light" href="../index.php">LMS</a>
        <button id="close-sidebar" class="btn"><i class="fas fa-times"></i></button>
    </div>
    <div class="list-group list-group-flush">
        <a href="../admin/dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
        <a href="../admin/manage_users.php" class="list-group-item list-group-item-action">Kelola Pengguna</a>
        <a href="../admin/manage_courses.php" class="list-group-item list-group-item-action">Kelola Kursus</a>
        <a href="../admin/manage_courses_guest.php" class="list-group-item list-group-item-action">Kelola Kursus Tamu</a>
        <!-- Add this logout button to your admin dashboard -->
<button onclick="confirmLogout()" class="list-group-item list-group-item-action">Logout</button>
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

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

<script>
    // Script untuk toggle sidebar
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    // Script untuk menutup sidebar dengan tombol "X"
    $("#close-sidebar").click(function (e) {
        e.preventDefault();
        $("#wrapper").removeClass("toggled");
    });
</script>
