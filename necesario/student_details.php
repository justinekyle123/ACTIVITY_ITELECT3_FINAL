<?php
include_once 'config.php';

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        
        echo '<div>';
        if (!empty($student['Photo'])) {
            echo '<img src="' . $student['Photo'] . '" alt="Student Photo" class="detail-photo">';
        } else {
            echo '<div style="width: 150px; height: 150px; background-color: #ddd; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user" style="font-size: 50px; color: #666;"></i>
                  </div>';
        }
        echo '</div>';
        
        echo '<div class="detail-info">';
        echo '<div class="detail-item"><span class="detail-label">Name:</span><br><span class="detail-value">' . $student['Name'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Gender:</span><br><span class="detail-value">' . $student['Gender'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Date of Birth:</span><br><span class="detail-value">' . $student['Date_of_birth'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Age:</span><br><span class="detail-value">' . $student['Age'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Email:</span><br><span class="detail-value">' . $student['Email'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Contact No:</span><br><span class="detail-value">' . $student['Contact_no'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Address:</span><br><span class="detail-value">' . $student['Address'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Place of Birth:</span><br><span class="detail-value">' . $student['Place_of_Birth'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Religion:</span><br><span class="detail-value">' . $student['Religion'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Citizenship:</span><br><span class="detail-value">' . $student['Citizenship'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Civil Status:</span><br><span class="detail-value">' . $student['Civil_status'] . '</span></div>';
        echo '<div class="detail-item"><span class="detail-label">Date Registered:</span><br><span class="detail-value">' . $student['Date'] . '</span></div>';
        echo '</div>';
    } else {
        echo '<p>Student not found.</p>';
    }
    
    $stmt->close();
}

$conn->close();
?>