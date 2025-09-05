<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die("Student ID is required.");
}

$id = (int) $_GET['id'];
$sql = "SELECT * FROM students WHERE student_id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | <?= $student['Name'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #2a3e9d;
            --accent: #ff6b6b;
            --light: #f8f9fc;
            --dark: #2e3a59;
            --success: #36b9cc;
        }
        
        body {
            background-color: #f0f4f9;
            font-family: 'Poppins', sans-serif;
            color: #2e3a59;
        }
        
        .navbar {
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100vw;
            top:0;
            z-index: 999;
            
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        
        .school-tagline {
            font-weight: 300;
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .profile-card {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
        }
        .card{
            margin-top:7%;
        }
        
        .card-header {
            background: linear-gradient(120deg, var(--primary), var(--secondary));
            color: white;
            padding: 20px 25px;
            position: relative;
        }
        
        .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--accent), #ff9e9e);
        }
        
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .student-name {
            font-weight: 700;
            color: var(--dark);
            margin-top: 15px;
            font-size: 1.8rem;
        }
        
        .student-id {
            background: var(--light);
            color: var(--primary);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-block;
            margin-top: 5px;
            font-size: 0.9rem;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(78, 115, 223, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-item {
            margin-bottom: 15px;
            display: flex;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--dark);
            min-width: 140px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-value {
            color: #5a6a85;
            flex-grow: 1;
        }
        
        .detail-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .detail-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .btn-school-primary {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-school-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
            color: white;
        }
        
        .btn-school-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
            border-radius: 8px;
            padding: 8px 18px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-school-secondary:hover {
            background: var(--light);
            transform: translateY(-2px);
            color: var(--primary);
        }
        
        .accent-icon {
            color: var(--accent);
            font-size: 1.1rem;
        }
        
        .footer {
            background: var(--dark);
            color: white;
            padding: 20px 0;
            margin-top: 40px;
            border-radius: 20px 20px 0 0;
        }
        
        @media (max-width: 768px) {
            .info-item {
                flex-direction: column;
                margin-bottom: 20px;
            }
            
            .info-label {
                min-width: 100%;
                margin-bottom: 5px;
            }
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

    <div class="container mb-5">
        <!-- Student Profile Card -->
        <div class="card profile-card">
            <div class="card-header">
                <h4 class="m-0"><i class="fas fa-user-graduate me-2"></i> Student Profile</h4>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-5">
                    <img src="<?= $student['Photo'] ?>" class="rounded-circle profile-pic shadow">
                    <h2 class="student-name"><?= $student['Name'] ?></h2>
                    <div class="student-id">
                        <i class="fas fa-id-card me-2"></i> ID: <?= $student['student_id'] ?>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Personal Information -->
                    <div class="col-lg-6 mb-4">
                        <div class="detail-box">
                            <h5 class="section-title"><i class="fas fa-user accent-icon"></i> Personal Information</h5>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-venus-mars text-primary"></i> Gender:</span>
                                <span class="info-value"><?= $student['Gender'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-birthday-cake text-primary"></i> Date of Birth:</span>
                                <span class="info-value"><?= $student['Date_of_birth'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-map-marker-alt text-primary"></i> Place of Birth:</span>
                                <span class="info-value"><?= $student['Place_of_Birth'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-calendar-alt text-primary"></i> Age:</span>
                                <span class="info-value"><?= $student['Age'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-heart text-primary"></i> Civil Status:</span>
                                <span class="info-value"><?= $student['Civil_status'] ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="col-lg-6 mb-4">
                        <div class="detail-box">
                            <h5 class="section-title"><i class="fas fa-address-card accent-icon"></i> Contact Information</h5>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-envelope text-primary"></i> Email:</span>
                                <span class="info-value"><?= $student['Email'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-phone text-primary"></i> Contact No:</span>
                                <span class="info-value"><?= $student['Contact_no'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-home text-primary"></i> Address:</span>
                                <span class="info-value"><?= $student['Address'] ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="col-lg-12 mb-4">
                        <div class="detail-box">
                            <h5 class="section-title"><i class="fas fa-info-circle accent-icon"></i> Additional Information</h5>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-church text-primary"></i> Religion:</span>
                                <span class="info-value"><?= $student['Religion'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-globe text-primary"></i> Citizenship:</span>
                                <span class="info-value"><?= $student['Citizenship'] ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-calendar-check text-primary"></i> Date Registered:</span>
                                <span class="info-value"><?= $student['Date'] ?></span>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="card-footer py-3 px-4 d-flex justify-content-between">
                <a href="home.php" class="btn btn-school-secondary"><i class="fas fa-arrow-left me-2"></i> Back to Dashboard</a>
                <a href="edit_student.php?id=<?= $student['student_id'] ?>" class="btn btn-school-primary"><i class="fas fa-edit me-2"></i> Edit Profile</a>
            </div>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <p class="mb-0">© 2025 South East Asian Institute of Technology.</p>
            <p class="mb-0">Designed for excellence in education</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>