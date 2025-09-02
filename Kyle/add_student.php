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
    <title>Add Student - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .form-container {
            max-width: 1000px;
            margin: 2rem auto;
        }
        
        .student-form {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: white;
        }
        
        .form-header {
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
            color: white;
            padding: 1.5rem 2rem;
        }
        
        .form-body {
            padding: 2rem;
        }
        
        .section-title {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 1.2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto 1rem;
            background: #f8f9fc;
            transition: all 0.3s;
        }
        
        .photo-preview:hover {
            border-color: var(--primary);
        }
        
        .photo-preview img {
            max-width: 100%;
            max-height: 100%;
            display: none;
        }
        
        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
        }
        
        .form-navigation {
            background: #f8f9fc;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #e3e6f0;
        }
        
        .required-field::after {
            content: "*";
            color: var(--danger);
            margin-left: 4px;
        }
        
        .form-section {
            margin-bottom: 2.5rem;
        }
        
        @media (max-width: 768px) {
            .form-container {
                margin: 1rem;
            }
            
            .form-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                        <a class="nav-link" href="home.php"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="add_student.php"><i class="fas fa-user-plus me-1"></i> Add Student</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-info-circle me-1"></i> About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container form-container">
        <div class="student-form">
            <div class="form-header">
                <h2 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add New Student</h2>
                <p class="mb-0">Fill in the details below to add a new student to the system</p>
            </div>
            
            <div class="form-body">
                <form id="addStudentForm" method="POST" action="add_student.php" enctype="multipart/form-data">
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h4 class="section-title"><i class="fas fa-user me-2"></i>Personal Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label required-field">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="form-label required-field">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label required-field">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="age" class="form-label required-field">Age</label>
                                    <input type="number" class="form-control" id="age" name="age" required readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="civil_status" class="form-label required-field">Civil Status</label>
                                    <select class="form-select" id="civil_status" name="civil_status" required>
                                        <option value="">Select Civil Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="religion" class="form-label required-field">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h4 class="section-title"><i class="fas fa-address-book me-2"></i>Contact Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label required-field">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_no" class="form-label required-field">Contact Number</label>
                                    <input type="tel" class="form-control" id="contact_no" name="contact_no" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="form-label required-field">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="form-section">
                        <h4 class="section-title"><i class="fas fa-info-circle me-2"></i>Additional Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="place_of_birth" class="form-label required-field">Place of Birth</label>
                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="citizenship" class="form-label required-field">Citizenship</label>
                                    <input type="text" class="form-control" id="citizenship" name="citizenship" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group text-center">
                                    <label for="photo" class="form-label">Student Photo</label>
                                    <div class="photo-preview" id="photoPreview">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <img id="previewImage" src="" alt="Preview">
                                    </div>
                                    <input type="file" class="form-control d-none" id="photoUpload" accept="image/*">
                                    <button type="button" class="btn btn-outline-primary" id="uploadTrigger">
                                        <i class="fas fa-upload me-1"></i>Upload Photo
                                    </button>
                                    <input type="hidden" id="photo" name="photo">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="form-navigation">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
                <button type="submit" form="addStudentForm" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Student
                </button>
            </div>
        </div>
    </div>

    <footer class="bg-white mt-5 py-4 text-center">
        <div class="container">
            <p class="text-muted mb-0">© 2023 Student Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('date_of_birth').addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            document.getElementById('age').value = age;
        });
        
        // Photo upload functionality
        document.getElementById('uploadTrigger').addEventListener('click', function() {
            document.getElementById('photoUpload').click();
        });
        
        document.getElementById('photoUpload').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    document.querySelector('.upload-icon').style.display = 'none';
                    document.getElementById('photo').value = e.target.result;
                }
                
                reader.readAsDataURL(file);
            }
        });
        
        // Form submission with SweetAlert
        document.getElementById('addStudentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Add Student?',
                text: 'Are you sure you want to add this student to the system?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, add student!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Student Added!',
                        text: 'Student has been successfully added to the system.',
                        confirmButtonColor: '#4e73df'
                    }).then(() => {
                        this.submit();
                    });
                }
            });
        });
    </script>
</body>
</html>