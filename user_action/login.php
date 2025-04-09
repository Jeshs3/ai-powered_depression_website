<?php
    session_start();
    include "../Admin/connection.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Prepare SQL statement to prevent SQL Injection
        $stmt = $dbhandle->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
    
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $first_name, $hashed_password);
            $stmt->fetch();
    
            // Verify password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['id'] = $id;
                $_SESSION['first_name'] = $first_name;
                
                header("Location: ../dashboard/depression_test.php"); // Redirect to dashboard
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "User not found.";
        }
    
        $stmt->close();
        $dbhandle->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
    <div class="main-container">
        <div class="title">
            <h2>Welcome Back!</h2>
            <p>Log in to view your previous assessment results and track your mental health journey.</p>
        </div>
        <div class="login-container">
            <h1>Log In to View Your Results</h1>
            <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form method="post">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name ="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name = "password" id="password" required minlength = "4" maxlength="8">
                </div>
                <input type="submit" value="Login" >
                <div class="links">
                    <a href="google.com">Sign in with Google?</a>
                    <a href="registration.php">Register an account</a>
                    <br>
                    <a href="../dashboard/depression_test.php">Try Depression Test again</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>