<?php

include 'connection.php'; // or your connection file

// Get the POSTed JSON
$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'] ?? null;

if ($userId) {
    $stmt = $dbhandle->prepare("DELETE FROM user_submissions WHERE user_id = ?");
    $stmt->bind_param("s", $userId);

    if ($stmt->execute()) {
        echo "Submission for user ID '$userId' deleted successfully.";
    } else {
        http_response_code(500);
        echo "Database error: " . $stmt->error;
    }
} else {
    http_response_code(400);
    echo "Invalid request: Missing user ID.";
}
?>
