<?php
include_once "config.php";

//  submission for adding students
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
        $success_message = "Student added successfully!";
    } else {
        $error_message = "Error adding student: " . $stmt->error;
    }
    $stmt->close();
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $delete_stmt->bind_param("i", $delete_id);
    
    if ($delete_stmt->execute()) {
        $success_message = "Student deleted successfully!";
    } else {
        $error_message = "Error deleting student: " . $delete_stmt->error;
    }
    $delete_stmt->close();
    
    header("Location: home.php");
    exit();
}

// Get total students
$totalQuery = "SELECT COUNT(*) as total FROM students";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalStudents = $totalRow['total'];

// Get gender distribution
$genderQuery = "SELECT Gender, COUNT(*) as count FROM students GROUP BY Gender";
$genderResult = $conn->query($genderQuery);

$genders = [];
$genderCounts = [];
while ($row = $genderResult->fetch_assoc()) {
    $genders[] = $row['Gender'];
    $genderCounts[] = $row['count'];
}

// Get civil status distribution
$civilQuery = "SELECT Civil_status, COUNT(*) as count FROM students GROUP BY Civil_status";
$civilResult = $conn->query($civilQuery);

$civilStatuses = [];
$civilCounts = [];
while ($row = $civilResult->fetch_assoc()) {
    $civilStatuses[] = $row['Civil_status'];
    $civilCounts[] = $row['count'];
}

// Get all students for listing
$studentsQuery = "SELECT * FROM students ORDER BY student_id DESC";
$studentsResult = $conn->query($studentsQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            max-width: 1200px;
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
        
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card h2 {
            color: #4b6cb7;
            margin-bottom: 10px;
        }
        
        .stat {
            font-size: 36px;
            font-weight: bold;
            color: #182848;
        }
        
        .charts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .chart-container h2 {
            color: #4b6cb7;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .student-form {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .student-form h2 {
            color: #4b6cb7;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
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
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-info {
            background: #17a2b8;
        }
        
        .btn-info:hover {
            background: #138496;
        }
        
        .student-list {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        
        .student-list h2 {
            color: #4b6cb7;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background-color: #f8f9fa;
            color: #4b6cb7;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        table tr:hover {
            background-color: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            padding: 8px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .action-btn i {
            margin-right: 5px;
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
        
        .student-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 600px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: #000;
        }
        
        .student-details {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-top: 20px;
        }
        
        .detail-photo {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
        }
        
        .detail-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .detail-item {
            margin-bottom: 15px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }
        
        .detail-value {
            color: #333;
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
            
            .charts {
                grid-template-columns: 1fr;
            }
            
            .student-details {
                grid-template-columns: 1fr;
            }
            
            .detail-info {
                grid-template-columns: 1fr;
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
                <h1>Student Management</h1>
            </div>
            <ul class="nav-links">
                <li><a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="add.php"><i class="fas fa-user-graduate"></i>Add Students</a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> Reports</a></li>
                <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="dashboard">
            <div class="card">
                <h2>Total Students</h2>
                <div class="stat"><?php echo $totalStudents; ?></div>
            </div>
            <div class="card">
                <h2>Male Students</h2>
                <div class="stat"><?php echo isset($genderCounts[0]) ? $genderCounts[0] : 0; ?></div>
            </div>
            <div class="card">
                <h2>Female Students</h2>
                <div class="stat"><?php echo isset($genderCounts[1]) ? $genderCounts[1] : 0; ?></div>
            </div>
        </div>

        <div class="charts">
            <div class="chart-container">
                <h2>Students by Gender</h2>
                <canvas id="genderChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Students by Civil Status</h2>
                <canvas id="civilStatusChart"></canvas>
            </div>
        </div>

        <div class="student-list">
            <h2>Student List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Age</th>
                        <th>Civil Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($studentsResult->num_rows > 0): ?>
                        <?php while ($student = $studentsResult->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($student['Photo'])): ?>
                                        <img src="<?php echo $student['Photo']; ?>" alt="Student Photo" class="student-photo">
                                    <?php else: ?>
                                        <div class="student-photo" style="background-color: #ddd; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user" style="font-size: 20px; color: #666;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $student['Name']; ?></td>
                                <td><?php echo $student['Gender']; ?></td>
                                <td><?php echo $student['Email']; ?></td>
                                <td><?php echo $student['Contact_no']; ?></td>
                                <td><?php echo $student['Age']; ?></td>
                                <td><?php echo $student['Civil_status']; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="#" class="action-btn btn-info view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="#" class="action-btn btn">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?delete_id=<?php echo $student['student_id']; ?>" class="action-btn btn-danger" onclick="return confirm('Are you sure you want to delete this student?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">No students found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>



    <!-- View Student Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Student Details</h2>
            <div id="studentDetails" class="student-details">
               
            </div>
        </div>
    </div>

    <script>
        // Gender Pie Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($genders); ?>,
                datasets: [{
                    data: <?php echo json_encode($genderCounts); ?>,
                    backgroundColor: [
                        '#4b6cb7',
                        '#ff6384',
                        '#36a2eb'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Civil Status Bar Chart
        const civilCtx = document.getElementById('civilStatusChart').getContext('2d');
        const civilChart = new Chart(civilCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($civilStatuses); ?>,
                datasets: [{
                    label: 'Number of Students',
                    data: <?php echo json_encode($civilCounts); ?>,
                    backgroundColor: [
                        '#4b6cb7',
                        '#ff6384',
                        '#36a2eb',
                        '#ffcd56',
                        '#4bc0c0'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
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

        // // Modal functionality
        // const modal = document.getElementById('viewModal');
        // const closeBtn = document.querySelector('.close');
        // const viewButtons = document.querySelectorAll('.view-btn');

        // viewButtons.forEach(button => {
        //     button.addEventListener('click', function() {
        //         const studentId = this.getAttribute('data-id');
        //         loadStudentDetails(studentId);
        //         modal.style.display = 'block';
        //     });
        // });

        // closeBtn.addEventListener('click', function() {
        //     modal.style.display = 'none';
        // });

        // window.addEventListener('click', function(event) {
        //     if (event.target === modal) {
        //         modal.style.display = 'none';
        //     }
        // });

        // Function to load student details via AJAX
        function loadStudentDetails(studentId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `get_student_details.php?id=${studentId}`, true);
            
            xhr.onload = function() {
                if (this.status === 200) {
                    document.getElementById('studentDetails').innerHTML = this.responseText;
                } else {
                    document.getElementById('studentDetails').innerHTML = '<p>Error loading student details.</p>';
                }
            };
            
            xhr.onerror = function() {
                document.getElementById('studentDetails').innerHTML = '<p>Error loading student details.</p>';
            };
            
            xhr.send();
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>