<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Details | Student Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary: #4e73df;
      --secondary: #6c757d;
      --success: #1cc88a;
      --warning: #f6c23e;
      --danger: #e74a3b;
      --dark: #343a40;
      --light: #f8f9fc;
    }

    body {
      background-color: var(--light);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding-top: 70px;
    }

    .navbar {
      background: linear-gradient(90deg, var(--primary) 0%, #2a3e9d 100%);
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.4rem;
    }

    .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 25px rgba(0,0,0,0.08);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-3px);
    }

    .card-header {
      background: var(--primary);
      color: white;
      padding: 1rem 1.5rem;
      font-weight: 600;
      font-size: 1.2rem;
    }

    .profile-pic {
      border: 5px solid #fff;
      box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    }

    .student-info p {
      font-size: 1rem;
      margin-bottom: 10px;
    }

    .student-info strong {
      width: 140px;
      display: inline-block;
      color: var(--dark);
    }

    .btn {
      border-radius: 8px;
      padding: 8px 18px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-warning {
      background: linear-gradient(90deg, #f6c23e, #da9b00);
      border: none;
      color: #fff;
    }

    .btn-warning:hover {
      opacity: 0.9;
    }

    .btn-secondary {
      background: linear-gradient(90deg, #6c757d, #495057);
      border: none;
      color: #fff;
    }

    .btn-secondary:hover {
      opacity: 0.9;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="home.php">
        <i class="fas fa-graduation-cap me-2"></i> Student Management
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
            <a class="nav-link" href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

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

  <!-- Student Details -->
  <div class="container mt-5">
    <div class="card">
      <div class="card-header">
        <i class="fas fa-user-graduate me-2"></i> Student Details
      </div>
      <div class="card-body text-center">
        <img src="<?= $student['Photo'] ?>" width="140" height="140" class="rounded-circle profile-pic mb-3">
        <div class="student-info text-start mx-auto" style="max-width:500px;">
          <p><strong>ID:</strong> <?= $student['student_id'] ?></p>
          <p><strong>Name:</strong> <?= $student['Name'] ?></p>
          <p><strong>Gender:</strong> <?= $student['Gender'] ?></p>
          <p><strong>Age:</strong> <?= $student['Age'] ?></p>
          <p><strong>Contact:</strong> <?= $student['Contact_no'] ?></p>
          <p><strong>Civil Status:</strong> <?= $student['Civil_status'] ?></p>
        </div>
      </div>
      <div class="card-footer text-end bg-light">
        <a href="home.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="edit_student.php?id=<?= $student['student_id'] ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Edit</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
