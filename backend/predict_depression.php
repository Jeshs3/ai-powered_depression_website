<?php
session_start();
include "../Admin/connection.php"; 

header('Content-Type: application/json');

// Logging for debug
file_put_contents("debug_log.txt", "Starting prediction...\n", FILE_APPEND);

try {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (!isset($data['answers'])) {
        throw new Exception("Missing 'answers' parameter.");
    }

    $answers = $data['answers'];

    file_put_contents("debug_log.txt", "Received answers: " . print_r($answers, true) . "\n", FILE_APPEND);

    // Convert answers array into comma-separated string
    $input_str = implode(",", $answers);

    // Call Python script
    $python = 'C:\Users\haiti\AppData\Local\Programs\Python\Python313\python.exe';
    $script = 'C:\xampp\htdocs\banol6\backend\model.py';
    $command = "\"$python\" \"$script\" " . escapeshellarg($input_str);
    file_put_contents("debug_log.txt", "Running command: $command\n", FILE_APPEND);

    $output = shell_exec($command);

    if ($output === null) {
        throw new Exception("Python script failed or didn't return any output.");
    }

    file_put_contents("debug_log.txt", "Python output: $output\n", FILE_APPEND);

    // Expecting pure JSON output
    $result = json_decode(trim($output), true);

    if (!isset($result['status']) || !isset($result['probability'])) {
        throw new Exception("Missing fields in Python output: $output");
    }

    echo json_encode([
        "status" => $result['status'], 
        "probability" => floatval($result['probability'])
    ]);

} catch (Exception $e) {
    file_put_contents("debug_log.txt", "Error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
