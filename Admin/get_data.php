<?php

require 'connection.php'; 

header('Content-Type: application/json');

$sql = "SELECT user_id, answers, score, status, probability, submission_date FROM user_submissions ORDER BY submission_date DESC";
$result = $dbhandle->query($sql);

$submissions = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = [
            "userid" => $row["user_id"],
            "answers" => json_decode($row["answers"], true),
            "total" => (int)$row["score"],
            "status" => $row["status"],
            'probability' => (float)$row["probability"],
            "date" => $row["submission_date"]
        ];
    }
}

echo json_encode($submissions);
$dbhandle->close();

?>
