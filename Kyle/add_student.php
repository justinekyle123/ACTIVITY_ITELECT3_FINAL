<?php
// Include database connection
include 'config.php';

// Initialize variables
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $age = (int)$_POST['age'];
    $civil_status = mysqli_real_escape_string($conn, $_POST['civil_status']);
    $religion = mysqli_real_escape_string($conn, trim($_POST['religion']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $contact_no = mysqli_real_escape_string($conn, trim($_POST['contact_no']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $place_of_birth = mysqli_real_escape_string($conn, trim($_POST['place_of_birth']));
    $citizenship = mysqli_real_escape_string($conn, trim($_POST['citizenship']));
    $photo = mysqli_real_escape_string($conn, $_POST['photo']);
    
    // Validate required fields
    if (empty($name) || empty($gender) || empty($date_of_birth) || empty($civil_status) || 
        empty($religion) || empty($email) || empty($contact_no) || empty($address) || 
        empty($place_of_birth) || empty($citizenship)) {
        $error_message = "All required fields must be filled!";
    } else {

            // Insert data into database
            $sql = "INSERT INTO students (name, gender, date_of_birth, age, civil_status, religion, email, contact_no, address, place_of_birth, citizenship, photo) 
                    VALUES ('$name', '$gender', '$date_of_birth', $age, '$civil_status', '$religion', '$email', '$contact_no', '$address', '$place_of_birth', '$citizenship', '$photo')";
            
            if (mysqli_query($conn, $sql)) {
                $success_message = "Student added successfully!";
                // Clear form data after successful submission
                $_POST = array();
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        }
    
}
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
                        <a class="nav-link" href="index.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
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
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender" class="form-label required-field">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_of_birth" class="form-label required-field">Date of Birth</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="age" class="form-label required-field">Age</label>
                                    <input type="number" class="form-control" id="age" name="age" value="<?php echo isset($_POST['age']) ? $_POST['age'] : ''; ?>" required readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="civil_status" class="form-label required-field">Civil Status</label>
                                    <select class="form-select" id="civil_status" name="civil_status" required>
                                        <option value="">Select Civil Status</option>
                                        <option value="Single" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                        <option value="Married" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                        <option value="Divorced" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                        <option value="Widowed" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="religion" class="form-label required-field">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" value="<?php echo isset($_POST['religion']) ? htmlspecialchars($_POST['religion']) : ''; ?>" required>
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
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_no" class="form-label required-field">Contact Number</label>
                                    <input type="tel" class="form-control" id="contact_no" name="contact_no" value="<?php echo isset($_POST['contact_no']) ? htmlspecialchars($_POST['contact_no']) : ''; ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="address" class="form-label required-field">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information Section -->
                    <div class="form-section">
                        <h4 class="section-title"><i class="fas fa-info-circle me-2"></i>Additional Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="place_of_birth" class="form-label required-field">Place of Birth</label>
                                    <input type="text" class="form-control" id="place_of_birth" name="place_of_birth" value="<?php echo isset($_POST['place_of_birth']) ? htmlspecialchars($_POST['place_of_birth']) : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="citizenship" class="form-label required-field">Citizenship</label>
                                    <input type="text" class="form-control" id="citizenship" name="citizenship" value="<?php echo isset($_POST['citizenship']) ? htmlspecialchars($_POST['citizenship']) : ''; ?>" required>
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
                <a href="home.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
                <button type="button" id="submitBtn" class="btn btn-primary">
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
        // Calculate age based on date of birth
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
        
        // SweetAlert confirmation before form submission
        document.getElementById('submitBtn').addEventListener('click', function() {
            // Validate required fields
            const form = document.getElementById('addStudentForm');
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let emptyFields = [];
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    emptyFields.push(field.previousElementSibling.textContent.replace('*', ''));
                }
            });
            
            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Required Fields',
                    html: 'Please fill in the following required fields:<br><br>' + 
                          '<ul style="text-align: left;">' + 
                          emptyFields.map(field => '<li>' + field + '</li>').join('') + 
                          '</ul>',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }
            
            // Show confirmation dialog
            Swal.fire({
                title: 'Confirm Student Registration',
                text: 'Are you sure you want to add this student to the system?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Add Student',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Adding student to the system',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    form.submit();
                }
            });
        });
        
        // Show success/error messages
        <?php if (!empty($success_message)): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $success_message; ?>',
            confirmButtonColor: '#1cc88a',
            timer: 3000,
            timerProgressBar: true
        }).then(() => {
            // Reset form after success
            document.getElementById('addStudentForm').reset();
            document.getElementById('previewImage').style.display = 'none';
            document.querySelector('.upload-icon').style.display = 'block';
        });
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $error_message; ?>',
            confirmButtonColor: '#e74a3b'
        });
        <?php endif; ?>
    </script>
</body>
</html>
