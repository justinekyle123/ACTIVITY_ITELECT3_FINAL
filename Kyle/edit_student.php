<?php
include_once 'config.php';

// Get form data
$student_id = $_POST['student_id'];
$name = $_POST['Name'];
$email = $_POST['Email'];
$contact_no = $_POST['Contact_no'];
$date_of_birth = $_POST['Date_of_birth'];
$gender = $_POST['Gender'];
$age = $_POST['Age'];
$religion = $_POST['Religion'];
$citizenship = $_POST['Citizenship'];
$civil_status = $_POST['Civil_status'];
$address = $_POST['Address'];
$place_of_birth = $_POST['Place_of_Birth'];

// Handle file upload
if (!empty($_FILES['Photo']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["Photo"]["name"]);
    move_uploaded_file($_FILES["Photo"]["tmp_name"], $target_file);
    $photo = $target_file;
    
    // Update query with photo
    $sql = "UPDATE students SET 
            Photo='$photo', 
            Name='$name', 
            Email='$email', 
            Contact_no='$contact_no', 
            Gender='$gender', 
            Address='$address', 
            Place_of_Birth='$place_of_birth', 
            Date_of_birth='$date_of_birth', 
            Age='$age', 
            Religion='$religion', 
            Citizenship='$citizenship', 
            Civil_status='$civil_status' 
            WHERE student_id=$student_id";
} else {
    // Update query without photo
    $sql = "UPDATE students SET 
            Name='$name', 
            Email='$email', 
            Contact_no='$contact_no', 
            Gender='$gender', 
            Address='$address', 
            Place_of_Birth='$place_of_birth', 
            Date_of_birth='$date_of_birth', 
            Age='$age', 
            Religion='$religion', 
            Citizenship='$citizenship', 
            Civil_status='$civil_status' 
            WHERE student_id=$student_id";
}

if ($conn->query($sql) === TRUE) {
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Student updated successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'index.html';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Error updating student: " . $conn->error . "',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>";
}

$conn->close();
?>