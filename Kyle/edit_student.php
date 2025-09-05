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

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['Name'];
    $gender = $_POST['Gender'];
    $address = $_POST['Address'];
    $birthplace = $_POST['Place_of_Birth'];
    $contact = $_POST['Contact_no'];
    $dob = $_POST['Date_of_birth'];
    $email = $_POST['Email'];
    $age = $_POST['Age'];
    $religion = $_POST['Religion'];
    $citizenship = $_POST['Citizenship'];
    $civil_status = $_POST['Civil_status'];

    // If photo updated
    $photo = $student['Photo'];
    if (!empty($_FILES['Photo']['name'])) {
        $target_dir = "uploads/";
        $photo = $target_dir . time() . "_" . basename($_FILES["Photo"]["name"]);
        move_uploaded_file($_FILES["Photo"]["tmp_name"], $photo);
    }

    $update = "UPDATE students SET 
        Photo='$photo',
        Name='$name',
        Gender='$gender',
        Address='$address',
        Place_of_Birth='$birthplace',
        Contact_no='$contact',
        Date_of_birth='$dob',
        Email='$email',
        Age='$age',
        Religion='$religion',
        Citizenship='$citizenship',
        Civil_status='$civil_status'
        WHERE student_id=$id";

    if ($conn->query($update)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Student record has been updated.',
                    confirmButtonColor: '#4e73df'
                }).then(() => { window.location='view_student.php?id=$id'; });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong while updating.',
                    confirmButtonColor: '#e74a3b'
                });
            });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student | <?= $student['Name'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #2a3e9d;
            --accent: #ff6b6b;
            --light: #f8f9fc;
            --dark: #2e3a59;
            --success: #1cc88a;
            --warning: #f6c23e;
            --danger: #e74a3b;
        }
        
        body {
            background-color: #f0f4f9;
            font-family: 'Poppins', sans-serif;
            color: #2e3a59;
        }
        .card{
            margin-top:7%;
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
        
        
        .school-logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo-icon {
            font-size: 2.2rem;
            color: white;
        }
        
        .school-name {
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
        }
        
        .school-tagline {
            font-weight: 300;
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .edit-card {
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            margin-bottom: 30px;
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
        
        .profile-pic-container {
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            display: inline-block;
        }
        
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .photo-upload {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: var(--primary);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }
        
        .form-section {
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
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 2px solid #e0e6ef;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .input-icon {
            color: var(--primary);
            font-size: 1.1rem;
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
        
        .btn-school-success {
            background: linear-gradient(to right, var(--success), #0f9d7a);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-school-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(28, 200, 138, 0.4);
            color: white;
        }
        
        .footer {
            background: var(--dark);
            color: white;
            padding: 20px 0;
            margin-top: 40px;
            border-radius: 20px 20px 0 0;
        }
        
        .field-required::after {
            content: '*';
            color: var(--danger);
            margin-left: 4px;
        }
        
        .age-display {
            background-color: var(--light);
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--primary);
            margin-top: 8px;
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .profile-pic-container {
                display: flex;
                flex-direction: column;
                align-items: center;
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
        <div class="card edit-card">
            <div class="card-header">
                <h4 class="m-0"><i class="fas fa-user-edit me-2"></i> Edit Student Profile</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" enctype="multipart/form-data" id="editStudentForm">
                    <div class="text-center mb-4">
                        <div class="profile-pic-container">
                            <img src="<?= $student['Photo'] ?>" id="profilePreview" class="rounded-circle profile-pic shadow">
                            <label for="Photo" class="photo-upload">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="Photo" id="Photo" class="d-none" accept="image/*">
                        </div>
                        <h5 class="mt-2"><?= $student['Name'] ?></h5>
                        <p class="text-muted">ID: <?= $student['student_id'] ?></p>
                    </div>
                    
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-lg-6 mb-4">
                            <h5 class="section-title"><i class="fas fa-user"></i> Personal Information</h5>
                            
                            <div class="mb-3">
                                <label class="form-label field-required"><i class="fas fa-signature input-icon"></i> Full Name</label>
                                <input type="text" name="Name" value="<?= $student['Name'] ?>" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label field-required"><i class="fas fa-venus-mars input-icon"></i> Gender</label>
                                <select name="Gender" class="form-select" required>
                                    <option value="Male" <?= $student['Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $student['Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= $student['Gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label field-required"><i class="fas fa-birthday-cake input-icon"></i> Date of Birth</label>
                                <input type="date" name="Date_of_birth" id="Date_of_birth" value="<?= $student['Date_of_birth'] ?>" class="form-control" required>
                                <div class="age-display mt-2">Age: <span id="ageDisplay"><?= $student['Age'] ?></span> years</div>
                                <input type="hidden" name="Age" id="Age" value="<?= $student['Age'] ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-map-marker-alt input-icon"></i> Place of Birth</label>
                                <input type="text" name="Place_of_Birth" value="<?= $student['Place_of_Birth'] ?>" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-heart input-icon"></i> Civil Status</label>
                                <select name="Civil_status" class="form-select">
                                    <option value="Single" <?= $student['Civil_status'] == 'Single' ? 'selected' : '' ?>>Single</option>
                                    <option value="Married" <?= $student['Civil_status'] == 'Married' ? 'selected' : '' ?>>Married</option>
                                    <option value="Divorced" <?= $student['Civil_status'] == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
                                    <option value="Widowed" <?= $student['Civil_status'] == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="col-lg-6 mb-4">
                            <h5 class="section-title"><i class="fas fa-address-card"></i> Contact Information</h5>
                            
                            <div class="mb-3">
                                <label class="form-label field-required"><i class="fas fa-envelope input-icon"></i> Email</label>
                                <input type="email" name="Email" value="<?= $student['Email'] ?>" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label field-required"><i class="fas fa-phone input-icon"></i> Contact Number</label>
                                <input type="tel" name="Contact_no" value="<?= $student['Contact_no'] ?>" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label field-required"><i class="fas fa-home input-icon"></i> Address</label>
                                <textarea name="Address" class="form-control" rows="3" required><?= $student['Address'] ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-church input-icon"></i> Religion</label>
                                <input type="text" name="Religion" value="<?= $student['Religion'] ?>" class="form-control">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-globe input-icon"></i> Citizenship</label>
                                <input type="text" name="Citizenship" value="<?= $student['Citizenship'] ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="view_student.php?id=<?= $student['student_id'] ?>" class="btn btn-school-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-school-success">
                            <i class="fas fa-save me-2"></i> Update Student
                        </button>
                    </div>
                </form>
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
    <script>
        // Profile photo preview
        document.getElementById('Photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Calculate age based on date of birth
        function calculateAge(dob) {
            const birthDate = new Date(dob);
            const today = new Date();
            
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            return age;
        }
        
        // Update age when date of birth changes
        document.getElementById('Date_of_birth').addEventListener('change', function() {
            const dob = this.value;
            if (dob) {
                const age = calculateAge(dob);
                document.getElementById('ageDisplay').textContent = age;
                document.getElementById('Age').value = age;
            }
        });
        
        // Form validation
        document.getElementById('editStudentForm').addEventListener('submit', function(e) {
            let valid = true;
            const requiredFields = this.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields.',
                    confirmButtonColor: '#e74a3b'
                });
            }
        });
        
        // Initialize age on page load if date of birth exists
        document.addEventListener('DOMContentLoaded', function() {
            const dob = document.getElementById('Date_of_birth').value;
            if (dob) {
                const age = calculateAge(dob);
                document.getElementById('ageDisplay').textContent = age;
                document.getElementById('Age').value = age;
            }
        });
    </script>
</body>
</html>