<?php
    session_start();
    include "connection.php";
    
    if (isset($_GET['id'])) {
        $user_id = $_GET['id'];
    
        // Join with users table to fetch the full name by concatenating first, middle, and last names
        $sql = "SELECT 
                    s.*, 
                    u.first_name, u.middle_name, u.last_name, 
                    u.email, u.gender, u.year, u.course, u.dob,
                    CONCAT(u.first_name, ' ', u.middle_name, ' ', u.last_name) AS full_name
                FROM user_submissions s
                JOIN users u ON s.user_id = u.id
                WHERE s.user_id = ?";
    
        $stmt = $dbhandle->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $dbhandle->error);
        }
    
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
        } else {
            echo "No record found.";
            exit();
        }
    
        $stmt->close();
    } else {
        echo "No ID provided.";
        exit();
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="../CSS/profile.css">
</head>
<body>
    <div class="user-info-container">
        <a href="javascript:history.back()" class="back-button">← Back to Previous Page</a>
        <div class="user-header">
            <div class="user-name">
                <h2><?php echo $row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']; ?></h2>
            </div>
            <div class="status-circle <?php echo strtolower($row['status']); ?>">
                <?php echo ucfirst($row['status']); ?>
            </div>
        </div>
        
        <div class="user-details">
            <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
            <p><strong>Gender:</strong> <?php echo $row['gender']; ?></p>
            <p><strong>Year:</strong> <?php echo $row['year']; ?></p>
            <p><strong>Course:</strong> <?php echo $row['course']; ?></p>
            <p><strong>Date of Birth:</strong> <?php echo $row['dob']; ?></p>
            <p><strong>Answers:</strong> <?php echo $row['answers']; ?></p>
            <p><strong>Score:</strong> <?php echo $row['score']; ?></p>
            <p><strong>Submission Date:</strong> <?php echo $row['submission_date']; ?></p>
        </div>
    </div>
</body>
</html>