<?php
header('Content-Type: application/json');

try {
    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (!isset($data['answers'])) {
        throw new Exception("Missing 'answers' parameter.");
    }

    // Send request to FastAPI
    $ch = curl_init("http://127.0.0.1:8000/prediction");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    if ($response === false) {
        throw new Exception("FastAPI request failed: " . curl_error($ch));
    }
    curl_close($ch);

    echo $response; // already JSON

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
