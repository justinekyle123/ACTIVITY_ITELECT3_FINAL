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
    <title>View Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fc; }
        .card { border-radius: 15px; }
        .card-header {
            background: linear-gradient(90deg, #4e73df, #2a3e9d);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .info-label { font-weight: 600; color: #4e73df; }
        .profile-pic { width: 140px; height: 140px; object-fit: cover; border: 4px solid #4e73df; }
        .detail-box { background: #fff; padding: 12px; border-radius: 8px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <h4><i class="fas fa-user-graduate"></i> Student Details</h4>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="<?= $student['Photo'] ?>" class="rounded-circle profile-pic mb-3">
                <h5 class="fw-bold"><?= $student['Name'] ?></h5>
                <p class="text-muted">Student ID: <?= $student['student_id'] ?></p>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Date Registered:</span>
                        <p><?= $student['Date'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Gender:</span>
                        <p><?= $student['Gender'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Address:</span>
                        <p><?= $student['Address'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Place of Birth:</span>
                        <p><?= $student['Place_of_Birth'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Contact No:</span>
                        <p><?= $student['Contact_no'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Date of Birth:</span>
                        <p><?= $student['Date_of_birth'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Email:</span>
                        <p><?= $student['Email'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Age:</span>
                        <p><?= $student['Age'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Religion:</span>
                        <p><?= $student['Religion'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Citizenship:</span>
                        <p><?= $student['Citizenship'] ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <span class="info-label">Civil Status:</span>
                        <p><?= $student['Civil_status'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="home.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
            <a href="edit_student.php?id=<?= $student['student_id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
        </div>
    </div>
</div>
</body>
</html>
