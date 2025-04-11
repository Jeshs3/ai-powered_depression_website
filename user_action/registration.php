<?php
    include "../Admin/connection.php";


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $last_name = $_POST["last_name"];
        $dob = $_POST["dob"];
        $email = $_POST["email"];
        $gender = $_POST["gender"];
        $year = $_POST["year"];
        $course = $_POST["course"];
        $password = $_POST["password"];
        $confirm_pass = $_POST["confirm_pass"];

    if ($password !== $confirm_pass) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password , PASSWORD_DEFAULT); 

    // Use prepared statement
    $sql = "INSERT INTO users (
                    first_name, 
                    middle_name, 
                    last_name, 
                    dob, 
                    email, 
                    gender, 
                    year, 
                    course,
                    password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param(
        "sssssssss", 
        $first_name, 
        $middle_name, 
        $last_name, 
        $dob, 
        $email, 
        $gender, 
        $year, 
        $course,
        $hashed_password
    );


    if ($stmt->execute()) {
        echo "<script>alert('Form submitted successfully.');</script>";
        
        $_SESSION['id'] = $dbhandle->insert_id; 
        $_SESSION['email'] = $email;
        header('Location: ../dashboard/depression_test.php'); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $dbhandle->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="../CSS/register.css">
</head>
<body>

    <div class="main-container">

        <div class="title">
            <h2>Are you having depression?</h2>
            <p>Take this assessment to help determine if you might have depression and learn how to seek help.</p>
        </div> 
        <div class="container">
            <h2>Signup to enter test</h2>

            <form action="registration.php" method="post">
                <div>
                    <label for="firstname">First Name:</label>
                    <input type="text" name="first_name" placeholder="Enter your first name" required>
                </div>

                <div>
                    <label for="middlename">Middle Name:</label>
                    <input type="text" name="middle_name" placeholder="Enter your middle name"required>
                </div>

                <div>
                    <label for="lastname">Last Name:</label>
                    <input type="text" name="last_name" placeholder="Enter your last name" required>
                </div>

                <div>
                    <label for="dob">Birthdate:</label>
                    <input type="date" name="dob" min="1970-01-01" max="2025-01-01" required>
                </div>

                <div>
                    <label>Gender:</label>
                    <div class="gender-group">
                        <div class="gender-option">
                            <input type="radio" name="gender" id="male" value = "M" required>
                            <label for="male">Male</label>
                        </div>
                        <div class="gender-option">
                            <input type="radio" name="gender" id="female" value="F" required>
                            <label for="female">Female</label>
                        </div>
                        <div class="gender-option">
                            <input type="radio" name="gender" id="others" value = "0" required>
                            <label for="others">Others</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="year">Year:</label>
                    <select name="year" id="year" required>
                        <option value="" disabled selected>Select a year</option>
                        <option value="1st">First Year</option>
                        <option value="2nd">Second Year</option>
                        <option value="3rd">Third Year</option>
                        <option value="4th">Fourth Year</option>
                        <option value="5th">Fifth Year</option>
                    </select>
                </div>

                <div>
                    <label for="course">Course:</label>
                    <select name="course" id="course" required>
                        <option value="" disabled selected>Select a course</option>
                        <option value="bscs">BSCS</option>
                        <option value="bshm">BSHM</option>
                        <option value="bses">BSES</option>
                        <option value="bsed_math">BSEd-Math</option>
                        <option value="btle-he">BTLE-HE</option>
                        <option value="beed">BEEd</option>
                    </select>
                </div>

                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" placeholder="Enter your email address"required>
                </div>
                
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" maxlength="8" minlength="4" required>
                </div>
                
                <div>
                    <label for="confirm_pass">Confirm Password:</label>
                    <input type="password" id="confirm_pass" name="confirm_pass" maxlength="8" minlength="4" required>
                </div>

                <a href="login.php">Login account</a>
                
                <input type="submit" value="Register" onclick="return validatePassword()">
            </form>
        </div>
    </div>
    
</body>
</html>