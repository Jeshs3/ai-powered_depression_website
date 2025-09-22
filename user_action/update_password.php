<?php
session_start();
include("connection.php");

$user_id = $_SESSION['user_id'];
$newPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

$sql = "UPDATE users SET password = ? WHERE user_id = ?";
$stmt = $dbhandle->prepare($sql);
$stmt->bind_param("si", $newPassword, $user_id);

if($stmt->execute()) {
    echo "Password updated successfully!";
} else {
    echo "Error updating password.";
}
?>
