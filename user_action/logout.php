<?php
    session_start();
    include "../database/connection.php";

    if (isset($_SESSION['log_id'])) {
        $log_id = $_SESSION['log_id'];
        $stmt = $dbhandle->prepare("UPDATE user_log SET time_out = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("i", $log_id);
        $stmt->execute();
        $stmt->close();
    }

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
?>
