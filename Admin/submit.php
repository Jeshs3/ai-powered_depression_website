<?php
session_start();
include "connection.php";

if (!isset($_SESSION['id'])) {
    http_response_code(403);
    echo "Unauthorized - User not logged in";
    exit;
}

$userId = $_SESSION['id'];  // Get the user ID from session
$date = $_POST['date'] ?? null;
$answers = $_POST['answers'] ?? null;  // The answers array
$totalScore = $_POST['totalScore'] ?? null;
$status = $_POST['status'] ?? null;  // Should be an integer, like 1 or 0

// Debugging: Log the incoming values
file_put_contents("debug_log.txt", json_encode([
    'userId' => $userId,
    'date' => $date,
    'answers' => $answers,
    'totalScore' => $totalScore,
    'status' => $status
]), FILE_APPEND);

// Validate data
if (!$date || !$answers || $totalScore === null || $status === null) {
    http_response_code(400);
    echo "Missing data";
    exit;
}

// Prepare SQL query
$stmt = $dbhandle->prepare("
    INSERT INTO user_submissions (user_id, submission_date, question_answer, total_score, status)
    VALUES (?, CURRENT_TIMESTAMP, ?, ?, ?)
");

if (!$stmt) {
    echo "Prepare failed: " . $dbhandle->error;
    exit;
}

// Bind the parameters (user_id, submission_date, answers, total_score, status)
$stmt->bind_param("issi", $userId, $answers, $totalScore, $status);

if ($stmt->execute()) {
    echo "Submission successful";  // Success message
} else {
    echo "Database error: " . $stmt->error;  // Detailed error message
}

$stmt->close();
$dbhandle->close();
?>
