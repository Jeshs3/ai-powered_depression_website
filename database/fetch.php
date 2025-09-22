
<?php
include 'connection.php';
session_start();

header('Content-Type: application/json');

// Determine which statistic to fetch
$type = $_GET['type'] ?? '';

switch ($type) {
    case 'user_count':
        $sql = "SELECT COUNT(*) AS total_users FROM users";
        $result = $dbhandle->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode(["total_users" => $row['total_users']]);
        break;

    case 'users_by_course':
        $sql = "SELECT course, COUNT(*) AS total FROM users GROUP BY course";
        $result = $dbhandle->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(["users_by_course" => $data]);
        break;

    case 'users_by_year':
        $sql = "SELECT year, COUNT(*) AS total FROM users GROUP BY year";
        $result = $dbhandle->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(["users_by_year" => $data]);
        break;
    case 'high_risk':
        $sql = "SELECT COUNT(*) AS total_high FROM user_submissions WHERE status='high'";
        $result = $dbhandle->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode(["high_risk" => (int)$row['total_high']]);
        break;

    case 'low_risk':
        $sql = "SELECT COUNT(*) AS total_low FROM user_submissions WHERE status='low'";
        $result = $dbhandle->query($sql);
        $row = $result->fetch_assoc();
        echo json_encode(["low_risk" => (int)$row['total_low']]);
        break;

    case 'depression_rate':
        // percentage of high-risk users
        $sqlHigh = "SELECT COUNT(*) AS total_high FROM user_submissions WHERE status='high'";
        $sqlTotal = "SELECT COUNT(*) AS total_all FROM user_submissions";
        $resHigh = $dbhandle->query($sqlHigh)->fetch_assoc();
        $resTotal = $dbhandle->query($sqlTotal)->fetch_assoc();

        $rate = ($resTotal['total_all'] > 0) ? ($resHigh['total_high'] / $resTotal['total_all']) * 100 : 0;
        echo json_encode(["depression_rate" => round($rate, 2)]);
        break;
    case 'monthly_trend':
        // Count submissions per month by status
        $sql = "SELECT 
                    DATE_FORMAT(submission_date, '%Y-%m') AS month,
                    SUM(CASE WHEN probability >= 0.5 THEN 1 ELSE 0 END) AS high_risk,
                    SUM(CASE WHEN probability < 0.5 THEN 1 ELSE 0 END) AS low_risk
                FROM user_submissions
                GROUP BY month
                ORDER BY month ASC";

        $result = $dbhandle->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[$row['month']] = [
                "high_risk" => (int)$row['high_risk'],
                "low_risk"  => (int)$row['low_risk']
            ];
        }
        echo json_encode($data);
    break;



    default:
        echo json_encode(["error" => "Invalid type"]);
}

$dbhandle->close();
?>
