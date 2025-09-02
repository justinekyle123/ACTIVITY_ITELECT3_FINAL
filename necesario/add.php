<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $place_of_birth = $_POST['place_of_birth'];
    $contact_no = $_POST['contact_no'];
    $date_of_birth = $_POST['date_of_birth'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $religion = $_POST['religion'];
    $citizenship = $_POST['citizenship'];
    $civil_status = $_POST['civil_status'];
    
    // Handle file upload
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $photo_name = time() . '_' . basename($_FILES['photo']['name']);
        $photo_path = $upload_dir . $photo_name;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
            $photo = $photo_path;
        }
    }
    
    // Insert student
    $stmt = $conn->prepare("INSERT INTO students (Photo, Name, Gender, Address, Place_of_Birth, Contact_no, Date_of_birth, Email, Age, Religion, Citizenship, Civil_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssisss", $photo, $name, $gender, $address, $place_of_birth, $contact_no, $date_of_birth, $email, $age, $religion, $citizenship, $civil_status);
    
    if ($stmt->execute()) {
        header("Location: home.php?success=Student added successfully!");
        exit();
    } else {
        header("Location: home.php?error=Error adding student: " . $stmt->error);
        exit();
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Reuse the same styles from the main page */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            color: white;
            padding: 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            max-width: 100%;
        }
        
        .logo {
            display: flex;
            align-items: center;
        }
        
        .logo h1 {
            font-size: 24px;
            margin-left: 10px;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 25px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 10px 15px;
            border-radius: 5px;
        }
        
        .nav-links a:hover, .nav-links a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffcc00;
        }
        
        .student-form {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .student-form h2 {
            color: #4b6cb7;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
        }
        
        .student-form h2 i {
            margin-right: 10px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #4b6cb7;
            outline: none;
        }
        
        .btn {
            background: #4b6cb7;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn:hover {
            background: #182848;
        }
        
        .btn-back {
            background: #6c757d;
            margin-right: 15px;
        }
        
        .btn-back:hover {
            background: #5a6268;
        }
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .alert i {
            margin-right: 10px;
            font-size: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 10px;
            }
            
            .nav-links {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .nav-links li {
                margin: 5px;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-back {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="white" stroke-width="2"/>
                    <path d="M12 14C6.47715 14 2 18.4772 2 24H22C22 18.4772 17.5228 14 12 14Z" stroke="white" stroke-width="2"/>
                </svg>
                <h1>Student Management System</h1>
            </div>
            <ul class="nav-links">
                <li><a href="home.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="add_student.php" class="active"><i class="fas fa-user-plus"></i> Add Student</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="index.php"><i class="fa-solid fa-right-from-bracket"></i></i> Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $_GET['success']; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_GET['error']; ?>
            </div>
        <?php endif; ?>

        <div class="student-form">
            <h2><i class="fas fa-user-plus"></i> Add New Student</h2>
            <form action="add.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact Number *</label>
                        <input type="tel" id="contact_no" name="contact_no" required>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age *</label>
                        <input type="number" id="age" name="age" required>
                    </div>
                    <div class="form-group">
                        <label for="civil_status">Civil Status *</label>
                        <select id="civil_status" name="civil_status" required>
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="religion">Religion *</label>
                        <input type="text" id="religion" name="religion" required>
                    </div>
                    <div class="form-group">
                        <label for="citizenship">Citizenship *</label>
                        <input type="text" id="citizenship" name="citizenship" required>
                    </div>
                    <div class="form-group">
                        <label for="place_of_birth">Place of Birth *</label>
                        <input type="text" id="place_of_birth" name="place_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address *</label>
                        <textarea id="address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="photo">Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>
                </div>
                <div class="form-actions">
                    <a href="home.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
                    <button type="submit" name="add_student" class="btn btn-success" ><i class="fas fa-save"></i> Add Student</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>