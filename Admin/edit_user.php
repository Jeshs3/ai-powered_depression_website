<?php
    include "connection.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        $sql = "SELECT * FROM users WHERE id = $id";
        $result = $dbhandle->query($sql);
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
        } else {
            echo "User not found.";
            exit();
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $last_name = $_POST["last_name"];
        $dob = $_POST["dob"];
        $email = $_POST["email"];
        $gender = $_POST["gender"];
        $year = $_POST["year"];
        $course = $_POST["course"];
    
        $sql = "UPDATE users SET 
                first_name='$first_name', 
                middle_name='$middle_name', 
                last_name='$last_name', 
                dob = '$dob',
                email = '$email',
                gender = '$gender',
                year = '$year',
                course = '$course'
                WHERE id=$id";
    
        if ($dbhandle->query($sql) === TRUE) {
            echo "<script>
                alert('User information is edited successfully!');
                window.location.href = 'view_user.php';
                </script>";
            exit();
        } else {
            echo "Error updating record: " . $dbhandle->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Form</title>
    <link rel="stylesheet" href="../CSS/register.css">
</head>
<body>
    <div class="container">
        <h2>Edit User Information</h2>

        <br>
        <br>
        <br> 
        <form method="post">
            <div>
                <label for="firstname">First Name:</label>
                <input type="text" name="first_name" value="<?= $row['first_name'] ?>" placeholder="Enter your first name" required>
            </div>

            <div>
                <label for="middlename">Middle Name:</label>
                <input type="text" name="middle_name" value="<?= $row['middle_name'] ?>" placeholder="Enter your middle name"required>
            </div>

            <div>
                <label for="lastname">Last Name:</label>
                <input type="text" name="last_name" value="<?=$row['last_name'] ?>" placeholder="Enter your last name" required>
            </div>

            <div>
                <label for="dob">Birthdate:</label>
                <input type="date" name="dob" min="1970-01-01" max="2025-01-01" value="<?= $row['dob'] ?>" required>
            </div>

            <div>
                <label>Gender:</label>
                <div class="gender-group">
                    <div class="gender-option">
                        <input type="radio" name="gender" id="male" value = "M" <?= ($row['gender'] == 'M') ? 'checked' : '' ?> required>
                        <label for="male">Male</label>
                    </div>
                    <div class="gender-option">
                        <input type="radio" name="gender" id="female" value="F" <?= ($row['gender'] == 'F') ? 'checked' : '' ?> required>
                        <label for="female">Female</label>
                    </div>
                    <div class="gender-option">
                        <input type="radio" name="gender" id="others" value = "0" <?= ($row['gender'] == '0') ? 'checked' : '' ?> required>
                        <label for="others">Others</label>
                    </div>
                </div>
            </div>

            <div>
                <label for="year">Year:</label>
                <select name="year" id="year" required>
                    <option value="" disabled checked>Select a year</option>
                    <option value="1st" <?= ($row['year'] == '1st') ? 'selected' : '' ?> >First Year</option>
                    <option value="2nd" <?= ($row['year'] == '2nd') ? 'selected' : '' ?> >Second Year</option>
                    <option value="3rd" <?= ($row['year'] == '3rd') ? 'selected' : '' ?> >Third Year</option>
                    <option value="4th" <?= ($row['year'] == '4th') ? 'selected' : '' ?> >Fourth Year</option>
                    <option value="5th" <?= ($row['year'] == '5th') ? 'selected' : '' ?> >Fifth Year</option>
                </select>
            </div>

            <div>
                <label for="course">Course:</label>
                <select name="course" id="course" required>
                    <option value="" disabled checked>Select a course</option>
                    <option value="bscs" <?= ($row['course'] == 'bcss') ? 'selected' : '' ?> >BSCS</option>
                    <option value="bshm" <?= ($row['course'] == 'bshm') ? 'selected' : '' ?> >BSHM</option>
                    <option value="bses" <?= ($row['course'] == 'bses') ? 'selected' : '' ?> >BSES</option>
                    <option value="bsed_math" <?= ($row['course'] == 'bsed_math') ? 'selected' : '' ?> >BSEd-Math</option>
                    <option value="btle-he" <?= ($row['course'] == 'btle-he') ? 'selected' : '' ?> >BTLE-HE</option>
                    <option value="beed" <?= ($row['course'] == 'beed') ? 'selected' : '' ?> >BEEd</option>
                </select>
            </div>

            
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?= $row['email'] ?>" placeholder="Enter your email address"required>
            </div>

            <p id="password_error" style="color: red; display: none;">Passwords do not match!</p>

            <input type="submit" value="Edit Information">
        </form>
    </div>
</body>
</html>