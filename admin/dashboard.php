<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
require_once '../includes/db_connect.php';

// Analisis jumlah akun
$sql_admin = "SELECT COUNT(*) as total_admin FROM users WHERE role='admin'";
$sql_instructor = "SELECT COUNT(*) as total_instructor FROM users WHERE role='instructor'";
$sql_student = "SELECT COUNT(*) as total_student FROM users WHERE role='student'";

$result_admin = $conn->query($sql_admin)->fetch_assoc();
$result_instructor = $conn->query($sql_instructor)->fetch_assoc();
$result_student = $conn->query($sql_student)->fetch_assoc();

// Fetch visitor data
$sql_visitors = "SELECT DATE(access_time) as date, COUNT(*) as count FROM guest_access GROUP BY DATE(access_time)";
$result_visitors = $conn->query($sql_visitors);

$visitor_data = [];
while ($row = $result_visitors->fetch_assoc()) {
    $visitor_data[] = $row;
}

$visitor_dates = array_column($visitor_data, 'date');
$visitor_counts = array_column($visitor_data, 'count');

// Only the student count for the second dataset
$student_count = (int)$result_student['total_student'];
$student_data = array_fill(0, count($visitor_dates), $student_count); // Fill array with student count
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php include '../includes/header.php'; ?>
    <title>Admin Dashboard - LMS</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles for the chart */
        #visitorChart {
            height: 400px;
            width: 100%;
        }
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            margin: auto;
        }
    </style>
</head>
<body>
    <!-- Tombol Hamburger untuk mobile -->
    <button class="btn" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <?php include '../includes/navbar.php'; ?>

        <!-- Page Content -->
        <div id="page-content-wrapper" style="flex: 1; padding: 20px; background-color: #f8f9fa;">
            <div class="container-fluid">
                <h1 class="mt-4">Selamat Datang, Admin</h1>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Admin</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $result_admin['total_admin'] ?></h5>
                                <p class="card-text">Jumlah Admin</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Instruktur</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $result_instructor['total_instructor'] ?></h5>
                                <p class="card-text">Jumlah Instruktur</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Siswa</div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $result_student['total_student'] ?></h5>
                                <p class="card-text">Jumlah Siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Visitor Analysis</div>
                            <div class="card-body chart-container">
                                <canvas id="visitorChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = <?= json_encode($visitor_dates) ?>;
            const visitorData = <?= json_encode($visitor_counts) ?>;

            // Data for the second dataset (number of students)
            const studentData = <?= json_encode($student_data) ?>;

            // Configuration for the chart
            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Jumlah Pengunjung',
                        data: visitorData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.1
                    },
                    {
                        label: 'Jumlah Siswa',
                        data: studentData,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                        tension: 0.1,
                        yAxisID: 'y1' // Bind this dataset to the second y-axis
                    }
                ]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    stacked: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Analisis Pengunjung dan Jumlah Siswa'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: {
                                drawOnChartArea: false, // Only want the grid lines for one axis to show up
                            },
                        },
                    }
                },
            };

            const ctx = document.getElementById('visitorChart').getContext('2d');
            const visitorChart = new Chart(ctx, config);
        });
    </script>
</body>
</html>
