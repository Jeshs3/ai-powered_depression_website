<?php
session_start();
include "../database/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- DEFAULT ADMIN SETUP ---
    $defaultAdminEmail = "admin@gmail.com";
    $defaultAdminPassword = "admin123";

    if ($email === $defaultAdminEmail && $password === $defaultAdminPassword) {

        // Check if admin already exists
        $stmt = $dbhandle->prepare("SELECT admin_id FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            // Insert default admin for the first time
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmtInsert = $dbhandle->prepare("INSERT INTO admins (email, password, username) VALUES (?, ?, ?)");
            $username = "Admin";
            $stmtInsert->bind_param("sss", $email, $hashedPassword, $username);
            $stmtInsert->execute();
            $stmtInsert->close();
        }
        $stmt->close();

        // Log the admin in
        $_SESSION['admin'] = true;
        $_SESSION['first_name'] = "Admin";
        header("Location: ../Admin/home.php");
        exit();
    }

    // --- NORMAL USER LOGIN ---
    $stmtUser = $dbhandle->prepare("SELECT id, first_name, password FROM users WHERE email = ?");
    $stmtUser->bind_param("s", $email);
    $stmtUser->execute();
    $stmtUser->store_result();

    if ($stmtUser->num_rows > 0) {
        $stmtUser->bind_result($id, $first_name, $hashed_password);
        $stmtUser->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['id'] = $id;
            $_SESSION['first_name'] = $first_name;
            header("Location: ../user/depression_test.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "User not found.";
    }

    $stmtUser->close();
    $dbhandle->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Mental Health Tracker</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2>Welcome Back!</h2>
                <p>Log in to view your assessment results and track your mental health journey.</p>
            </div>

            <form method="post" class="login-form">
                <h3>Log In</h3>
                <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="4-8 characters" required minlength="4" maxlength="8">
                </div>

                <button type="submit" class="login-btn">Login</button>

                <div class="login-links">
                    <a href="../user/registration.php">Register an account</a>
                    <a href="../user/depression_test.php">Try Depression Test again</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
