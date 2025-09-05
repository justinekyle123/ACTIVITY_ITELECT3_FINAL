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
        .btn-primary{
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
            transition: transform 0.3s;
            
        }
        .btn-primary:hover{
             transform: translateY(-2px);
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100vw;
            top:0;
            z-index: 999;
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
                        <a class="nav-link active" href="home.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="add_student.php"><i class="fas fa-user-plus me-1"></i> Add Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i  class="fa-solid fa-right-from-bracket"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

  
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>