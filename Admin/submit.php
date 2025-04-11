<?php
session_start();
include "connection.php";

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Unauthorized - User not logged in";
    exit;
}

$userId = $_SESSION['id'];

// Fetch the POST data
$answers = $_POST['answers'] ?? null;
$score = $_POST['score'] ?? null;
$status = $_POST['status'] ?? "pending-analysis"; // Default to 'pending-analysis' if not provided

// Debug log
file_put_contents("debug_log.txt", json_encode([
    'user_id' => $userId,
    'answers' => $answers,
    'score' => $score,
    'status' => $status
]) . PHP_EOL, FILE_APPEND);

// Validate required fields
if (!$answers || $score === null || $status === null) {
    http_response_code(400);
    echo "Missing data";
    exit;
}

// Convert answers array to JSON if necessary
if (is_array($answers)) {
    $answers = json_encode($answers);
}

// Prepare SQL query (submission_date will be handled automatically)
$stmt = $dbhandle->prepare("
    INSERT INTO user_submissions (user_id, answers, score, status, submission_date)
    VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
");

if (!$stmt) {
    http_response_code(500);
    echo "Prepare failed: " . $dbhandle->error;
    exit;
}

// Bind parameters 
$stmt->bind_param("isis", $userId, $answers, $score, $status);

if ($stmt->execute()) {
    echo "Submission successful";
} else {
    http_response_code(500);
    echo "Database error: " . $stmt->error;
}

$stmt->close();
$dbhandle->close();
?>
