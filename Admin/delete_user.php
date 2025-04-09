<?php
    include "connection.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    
        // Prepare statement to prevent SQL injection
        $stmt = $dbhandle->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            echo "<script>alert('User deleted successfully.')</script>";

            $stmt->close();
            $dbhandle->close();

            // Redirect to refresh the user list
            header("Location: view_user.php");
            exit();
        } else {
            echo "Error deleting user.";
        }
    
        $stmt->close();
    }
?>


