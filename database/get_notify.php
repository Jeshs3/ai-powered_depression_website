<?php
session_start();
include "../database/connection.php";

// If it's a POST, mark notification(s) as read
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['id'])) {
        $notificationId = intval($data['id']);
        $stmt = $dbhandle->prepare("UPDATE notifications SET is_read = 1 WHERE notification_id = ?");
        $stmt->bind_param("i", $notificationId);
        $success = $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => $success]);
        $dbhandle->close();
        exit;
    }

    // Mark all as read
    if (isset($data['mark_all']) && $data['mark_all'] === true) {
        $success = $dbhandle->query("UPDATE notifications SET is_read = 1");
        echo json_encode(["success" => $success]);
        $dbhandle->close();
        exit;
    }

    echo json_encode(["success" => false, "message" => "Invalid request"]);
    $dbhandle->close();
    exit;
}

// Otherwise, default to GET → fetch notifications
$sql = "SELECT notification_id, type, message, is_read, created_at 
        FROM notifications 
        ORDER BY created_at DESC 
        LIMIT 50";
$result = $dbhandle->query($sql);

$notifications = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}

echo json_encode($notifications);
$dbhandle->close();
