<?php

require 'connection.php'; // Adjust path to your DB connection

header('Content-Type: application/json');

$sql = "SELECT user_id, submission_date, question_answer, total_score, status FROM user_submissions ORDER BY submission_date DESC";
$result = $dbhandle->query($sql);

$submissions = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = [
            "userId" => $row["user_id"],
            "date" => $row["submission_date"],
            "answers" => explode(" - ", $row["question_answer"]),
            "total" => (int)$row["total_score"],
            "status" => (int)$row["status"]
        ];
    }
}

echo json_encode($submissions);
$dbhandle->close();
?>
