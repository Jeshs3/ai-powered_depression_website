<?php
session_start();
include "../database/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['guest_mode'])) {
        // Guest mode → skip registration, go straight to test
        $_SESSION['guest'] = true;
        header("Location: depression_test.php");
        exit();
    }

    if (isset($_SESSION['registered'])) {
        $status = $_SESSION['registered'];
        unset($_SESSION['registered']);
    }

    // Normal registration flow
    $first_name = $_POST["first_name"];
    $middle_name = $_POST["middle_name"];
    $last_name = $_POST["last_name"];
    $dob = $_POST["dob"];
    $cn_num = $_POST["cn_num"];
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

    $sql = "INSERT INTO users (
                    first_name, 
                    middle_name, 
                    last_name, 
                    dob, 
                    cn_num,
                    email, 
                    gender, 
                    year, 
                    course,
                    password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $dbhandle->prepare($sql);
    $stmt->bind_param(
        "ssssssssss", 
        $first_name, 
        $middle_name, 
        $last_name, 
        $dob, 
        $cn_num,
        $email, 
        $gender, 
        $year, 
        $course,
        $hashed_password
    );

    if ($stmt->execute()) {
        $_SESSION['id'] = $dbhandle->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['status'] = "registered";
        header("Location: depression_test.php");
        exit();
    } else {
        if ($dbhandle->errno == 1062) { // duplicate email error
            $_SESSION['status'] = "duplicate";
        } else {
            error_log("DB Error: " . $stmt->error);
            $_SESSION['status'] = "error";
        }
        header("Location: registration.php");
        exit();
    }


    $stmt->close();
    $dbhandle->close();

}
?>

<!-- Registration Page with Guest Option -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Depression Test - Signup or Guest</title>
  <link rel="stylesheet" href="../CSS/register.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php if (!empty($_SESSION['status'])): ?>
  <script>
    <?php if ($_SESSION['status'] === "duplicate"): ?>
      Swal.fire({
        title: "Duplicate Email",
        text: "You entered a duplicated email. Use another one.",
        icon: "warning",
        confirmButtonText: "Try Again"
      });

    <?php elseif ($_SESSION['status'] === "password_mismatch"): ?>
      Swal.fire({
        title: "Password Error",
        text: "Passwords do not match!",
        icon: "error",
        confirmButtonText: "Retry"
      });

    <?php elseif ($_SESSION['status'] === "error"): ?>
      Swal.fire({
        title: "Unexpected Error",
        text: "Something went wrong. Please try again later.",
        icon: "error",
        confirmButtonText: "OK"
      });
    <?php endif; ?>
  </script>
  <?php unset($_SESSION['status']); ?>
<?php endif; ?>


  <div class="main-container">
    <div class="intro-box">
      <h2>Are you feeling down or anxious?</h2>
      <p>Take this confidential depression test. You can either sign up to save your results, or continue as a guest.</p>
    </div>

    <div class="options-container">
      <!-- Sign Up Form -->
      <div class="form-card">
        <h3>Sign Up & Save Results</h3>
        <form action="registration.php" method="post">
          <div class="form-grid">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="middle_name" placeholder="Middle Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="date" name="dob" min="1970-01-01" max="2025-01-01" required>
            
            <select name="gender" required>
              <option value="" disabled selected>Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Others</option>
            </select>

            <select name="year" required>
              <option value="" disabled selected>Year Level</option>
              <option value="1st">First Year</option>
              <option value="2nd">Second Year</option>
              <option value="3rd">Third Year</option>
              <option value="4th">Fourth Year</option>
              <option value="5th">Fifth Year</option>
            </select>

            <select name="course" required>
              <option value="" disabled selected>Course</option>
              <option value="bscs">BSCS</option>
              <option value="bshm">BSHM</option>
              <option value="bses">BSES</option>
              <option value="bsed_math">BSEd-Math</option>
              <option value="btle-he">BTLE-HE</option>
              <option value="beed">BEEd</option>
            </select>
            
            <input type="number" name="cn_num" placeholder="Contact Number" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password (4-8 chars)" minlength="4" maxlength="8" required>
            <input type="password" name="confirm_pass" placeholder="Confirm Password" minlength="4" maxlength="8" required>
          </div>

          <button type="submit" class="btn primary-btn">Register & Start Test</button>
          <p class="alt-link">Already have an account? <a href="../user_action/login.php">Login here</a></p>
        </form>
      </div>

      <!-- Guest Option -->
      <div class="form-card guest">
        <h3>Continue as Guest</h3>
        <form action="registration.php" method="post">
          <input type="hidden" name="guest_mode" value="1">
          <button type="submit" class="btn secondary-btn">Take Test Without Signing Up</button>
          <p class="note">⚠ Your results will not be saved.</p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
