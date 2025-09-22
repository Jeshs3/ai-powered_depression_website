<?php
require 'connection.php'; 

header('Content-Type: application/json');

// Fetch submissions with user details (LEFT JOIN for guests)
$sql = "SELECT 
            us.submission_id,
            us.user_id,
            us.score,
            us.status,
            us.probability,
            us.submission_date,
            CONCAT(u.first_name, ' ', COALESCE(u.middle_name, ''), ' ', u.last_name) AS username,
            TIMESTAMPDIFF(YEAR, u.dob, CURDATE()) AS age,
            u.gender AS gender
        FROM user_submissions us
        LEFT JOIN users u ON us.user_id = u.id
        ORDER BY us.submission_date DESC";

$result = $dbhandle->query($sql);

$submissions = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = [
            "submission_id" => (int)$row["submission_id"],
            "userid"        => (int)$row["user_id"],
            "username"      => $row["username"] ?? "Guest",
            "age"           => $row["age"] ?? null,
            "gender"        => $row["gender"] ?? null,
            "total"         => (float)$row["score"],
            "status"        => $row["status"],
            "probability"   => (float)$row["probability"],
            "date"          => $row["submission_date"]
        ];
    }
}

echo json_encode($submissions, JSON_PRETTY_PRINT);
$dbhandle->close();
?>
