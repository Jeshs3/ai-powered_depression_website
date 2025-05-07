<?php
session_start();
include "../Admin/connection.php"; 

header('Content-Type: application/json');

// Logging for debug
file_put_contents("debug_log.txt", "Starting prediction...\n", FILE_APPEND);

try {
    if (!isset($_POST['answers'])) {
        throw new Exception("Missing 'answers' parameter.");
    }

    $answers = $_POST['answers'];
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

    // Parse output
    $parts = explode(",", trim($output));
    if (count($parts) !== 2) {
        throw new Exception("Unexpected output format from Python: $output");
    }

    list($prediction, $probability) = $parts;

    echo json_encode([
        "prediction" => intval($prediction),
        "probability" => floatval($probability)
    ]);
} catch (Exception $e) {
    file_put_contents("debug_log.txt", "Error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
