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
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Student record has been updated.',
                confirmButtonColor: '#4e73df'
            }).then(() => { window.location='view_student.php?id=$id'; });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong while updating.',
                confirmButtonColor: '#e74a3b'
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
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fc; }
        .card { border-radius: 15px; }
        .card-header {
            background: linear-gradient(90deg, #36b9cc, #1c7cd6);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        label { font-weight: 600; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header">
            <h4><i class="fas fa-edit"></i> Edit Student</h4>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="text-center mb-3">
                    <img src="<?= $student['Photo'] ?>" width="120" height="120" class="rounded-circle border mb-2">
                    <input type="file" name="Photo" class="form-control w-50 mx-auto">
                </div>
                <div class="row">
                    <?php foreach ($student as $key => $value): ?>
                        <?php if ($key != 'student_id' && $key != 'Photo' && $key != 'Date'): ?>
                            <div class="col-md-6 mb-3">
                                <label><?= ucwords(str_replace("_", " ", $key)) ?></label>
                                <input type="text" name="<?= $key ?>" value="<?= $value ?>" class="form-control">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="text-end">
                    <a href="view_student.php?id=<?= $student['student_id'] ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancel</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
