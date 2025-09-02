<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #6c757d;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            border: none;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        
        .chart-container {
            height: 300px;
            margin-bottom: 30px;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .student-table {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .student-table th {
            background-color: var(--primary);
            color: white;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
        }
        
        .card-header {
            font-weight: 600;
            background-color: white;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .stat-card {
            color: white;
            border-radius: 10px;
        }
        
        .stat-card i {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(90deg, var(--success) 0%, #0f9d58 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(90deg, var(--info) 0%, #1c7cd6 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(90deg, var(--warning) 0%, #da9b00 100%);
        }
        
        .page-title {
            color: var(--dark);
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <i class="fas fa-graduation-cap me-2"></i>Student Management System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_student.php"><i class="fas fa-user-plus me-1"></i> Add Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-info-circle me-1"></i> About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php
        include 'config.php';

        // Handle delete operation
        if (isset($_GET['delete_id'])) {
            $delete_id = $_GET['delete_id'];
            echo "<script>
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'home.php?confirm_delete=" . $delete_id . "';
                    } else {
                        window.location.href = 'home.php';
                    }
                });
            </script>";
        }

        if (isset($_GET['confirm_delete'])) {
            $delete_id = $_GET['confirm_delete'];
            $sql = "DELETE FROM students WHERE student_id = $delete_id";
            
            if ($conn->query($sql) === TRUE) {
                echo "<script>
                    Swal.fire('Deleted!', 'Student record has been deleted.', 'success')
                    .then(() => { window.location.href = 'home.php'; });
                </script>";
            } else {
                echo "<script>
                    Swal.fire('Error!', 'Error deleting record: " . $conn->error . "', 'error')
                    .then(() => { window.location.href = 'home.php'; });
                </script>";
            }
        }

        // Get total students
        $total_students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
        
        // Get male students count
        $male_students = $conn->query("SELECT COUNT(*) as male FROM students WHERE Gender = 'Male'")->fetch_assoc()['male'];
        
        // Get female students count
        $female_students = $conn->query("SELECT COUNT(*) as female FROM students WHERE Gender = 'Female'")->fetch_assoc()['female'];
        
        // Get students by civil status
        $status_counts = [];
        $status_result = $conn->query("SELECT Civil_status, COUNT(*) as count FROM students GROUP BY Civil_status");
        while ($row = $status_result->fetch_assoc()) {
            $status_counts[$row['Civil_status']] = $row['count'];
        }
        ?>

        <h1 class="page-title">Student Dashboard</h1>

        <!-- Dashboard Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card bg-gradient-primary dashboard-card text-white p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0"><?php echo $total_students; ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card bg-gradient-success dashboard-card text-white p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Male Students</div>
                            <div class="h5 mb-0"><?php echo $male_students; ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-male fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card bg-gradient-info dashboard-card text-white p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Female Students</div>
                            <div class="h5 mb-0"><?php echo $female_students; ?></div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-female fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stat-card bg-gradient-warning dashboard-card text-white p-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Average Age</div>
                            <div class="h5 mb-0">
                                <?php 
                                $avg_age = $conn->query("SELECT AVG(Age) as avg_age FROM students")->fetch_assoc()['avg_age'];
                                echo round($avg_age, 1);
                                ?>
                            </div>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-birthday-cake fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card dashboard-card">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Students by Gender</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card dashboard-card">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Students by Civil Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card dashboard-card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
                <a href="add_student.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus me-1"></i> Add New Student
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover student-table" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th>Contact</th>
                                <th>Civil Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM students";
                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                        <td>{$row['student_id']}</td>
                                        <td class='text-center'><img src='{$row['Photo']}' width='40' height='40' class='rounded-circle'></td>
                                        <td>{$row['Name']}</td>
                                        <td>{$row['Gender']}</td>
                                        <td>{$row['Age']}</td>
                                        <td>{$row['Contact_no']}</td>
                                        <td>{$row['Civil_status']}</td>
                                        <td class='action-buttons'>
                                            <a href='view_student.php?id={$row['student_id']}' class='btn btn-info btn-sm' title='View'><i class='fas fa-eye'></i></a>
                                            <a href='edit_student.php?id={$row['student_id']}' class='btn btn-warning btn-sm' title='Edit'><i class='fas fa-edit'></i></a>
                                            <a href='home.php?delete_id={$row['student_id']}' class='btn btn-danger btn-sm' title='Delete'><i class='fas fa-trash'></i></a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'>No students found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white mt-5 py-3 text-center">
        <div class="container">
            <p class="text-muted mb-0">© 2023 Student Management System. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Gender Pie Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [<?php echo $male_students; ?>, <?php echo $female_students; ?>],
                    backgroundColor: ['#4e73df', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
                }
            }
        });

        // Status Bar Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: [<?php 
                    $status_labels = array_keys($status_counts);
                    foreach($status_labels as $label) {
                        echo "'$label',";
                    }
                ?>],
                datasets: [{
                    label: 'Number of Students',
                    data: [<?php echo implode(',', $status_counts); ?>],
                    backgroundColor: '#1cc88a',
                    hoverBackgroundColor: '#17a673',
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            drawBorder: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>