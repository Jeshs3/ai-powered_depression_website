<?php
session_start();
include "../database/connection.php";
include "../Admin/session.php"; // For admin session check
include "../Admin/notification.php"; // Include notification functions

// Track failed login attempts
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
}

// ========== LOGIN HANDLER ==========
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // --- CHECK IF ACCOUNT LOCKED ---
    $recentFails = countRecentFailedAttempts($dbhandle, $email);
    if ($recentFails >= 5) {
        notifySecurityBreach($dbhandle, "Too many failed login attempts for $email", $_SESSION['admin_id'] ?? null);
        die("Account locked due to multiple failed attempts. Please try again after 15 minutes.");
    }

    // --- DEFAULT ADMIN SETUP ---
    $defaultAdminEmail = "admin@gmail.com";
    $defaultAdminPassword = "admin123";

    if ($email === $defaultAdminEmail && $password === $defaultAdminPassword) {
        $stmt = $dbhandle->prepare("SELECT admin_id, username FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($admin_id, $username);
        $stmt->fetch();

        if ($stmt->num_rows === 0) {
            // Insert default admin for the first time
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $username = "Admin";
            $stmtInsert = $dbhandle->prepare("INSERT INTO admins (email, password, username) VALUES (?, ?, ?)");
            $stmtInsert->bind_param("sss", $email, $hashedPassword, $username);
            $stmtInsert->execute();
            $admin_id = $stmtInsert->insert_id;
            $stmtInsert->close();
        }
        $stmt->close();

        // Reset failed attempts if any
        clearFailedAttempts($dbhandle, $email);

        // Log the admin in
        $_SESSION['admin'] = true;
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['first_name'] = $username;

         // Log admin login time
        $stmtLog = $dbhandle->prepare("INSERT INTO user_log (username) VALUES (?)");
        $stmtLog->bind_param("s", $username);
        $stmtLog->execute();
        $_SESSION['log_id'] = $dbhandle->insert_id; // store log id for logout
        $stmtLog->close();

        header("Location: ../Admin/analytics.php");
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
            // Reset failed attempts
            clearFailedAttempts($dbhandle, $email);

            // Log successful login
            $stmtLog = $dbhandle->prepare("INSERT INTO user_log (username) VALUES (?)");
            $stmtLog->bind_param("s", $first_name);
            $stmtLog->execute();
            $_SESSION['log_id'] = $dbhandle->insert_id; // store log id for logout
            $stmtLog->close();

            $_SESSION['id'] = $id;
            $_SESSION['first_name'] = $first_name;
            header("Location: ../user/depression_test.php");
            exit();
        } else {
            // Wrong password → log attempt
            logFailedAttempt($dbhandle, $email);
            $error = "Invalid email or password.";
        }
    } else {
        // No user found → still log the attempt
        logFailedAttempt($dbhandle, $email);
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
