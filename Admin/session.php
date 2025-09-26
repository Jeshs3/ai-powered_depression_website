<?php
    // session.php
    // session_start();
    // include "../database/connection.php";

    // //Just check if admin session exists
    // $isAdminLoggedIn = isset($_SESSION['admin_id']);

    // // You can also store user info safely if logged in
    // $adminId = $isAdminLoggedIn ? $_SESSION['admin_id'] : null;
    // $adminName = $isAdminLoggedIn ? $_SESSION['username'] ?? 'Admin' : 'Guest';

    
    // Log a failed attempt
    function logFailedAttempt($dbhandle, $email) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $stmt = $dbhandle->prepare("INSERT INTO failed_logins (email, ip_address) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $ip);
        $stmt->execute();
        $stmt->close();
    }

    // Count failed attempts in the last 15 minutes
    function countRecentFailedAttempts($dbhandle, $email) {
        $sql = "SELECT COUNT(*) AS fail_count
                FROM failed_logins
                WHERE email = ?
                AND attempt_time >= NOW() - INTERVAL 15 MINUTE";
        $stmt = $dbhandle->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return (int)$row['fail_count'];
    }

    // Clear failed attempts on successful login
    function clearFailedAttempts($dbhandle, $email) {
        $stmt = $dbhandle->prepare("DELETE FROM failed_logins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();
    }
?>


