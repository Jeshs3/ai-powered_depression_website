<?php
session_start();
header("Content-Type: application/json");
include "connection.php";

// Get logged-in admin ID
$adminId = $_SESSION['admin_id'] ?? null;

if (!$adminId) {
    echo json_encode([
        "success" => false,
        "message" => "Not authenticated"
    ]);
    exit();
}

// Fetch admin settings
$sql = "SELECT username, email, theme, notifications, data_retention 
        FROM admins 
        WHERE admin_id = ?";
$stmt = $dbhandle->prepare($sql);
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "admin" => $row  // renamed 'settings' -> 'admin'
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No admin found"
    ]);
}
