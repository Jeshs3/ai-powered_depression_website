<?php
include 'connection.php';
session_start();

$action = $_GET['action'] ?? null;

switch ($action) {
    case 'delete_submission':
        // expects JSON with userId
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
            $stmt->close();
        } else {
            http_response_code(400);
            echo "Invalid request: Missing user ID.";
        }
        break;

    case 'delete_user':
        // expects ?id=123
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $dbhandle->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "<script>alert('User deleted successfully.')</script>";
                header("Location: view_user.php");
                exit();
            } else {
                echo "Error deleting user.";
            }
            $stmt->close();
        } else {
            http_response_code(400);
            echo "Invalid request: Missing user ID.";
        }
        break;

    case 'check_session':
        if (isset($_SESSION['id'])) {
            echo $_SESSION['id'];
        } else {
            http_response_code(401);
            echo "Unauthorized";
        }
        break;

    default:
        http_response_code(400);
        echo "Invalid action.";
        break;
}

$dbhandle->close();
?>
