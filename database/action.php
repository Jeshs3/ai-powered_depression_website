<?php
session_start();
include 'connection.php';
include '../Admin/notification.php'; // include notification functions


$action = $_GET['action'] ?? null;

switch ($action) {
    case 'delete_submission':
    // expects JSON with submissionId
    $data = json_decode(file_get_contents('php://input'), true);
    $submissionId = $data['submissionId'] ?? null;

    if ($submissionId) {
        // Prepare statement to delete a single submission
        $stmt = $dbhandle->prepare("DELETE FROM user_submissions WHERE submission_id = ?");
        $stmt->bind_param("i", $submissionId);

        if ($stmt->execute()) {
            echo "Submission ID '$submissionId' deleted successfully.";
        } else {
            http_response_code(500);
            echo "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        http_response_code(400);
        echo "Invalid request: Missing submission ID.";
    }
    break;

    case 'delete_user':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);

            // Copy user data to archived_users
            $archiveStmt = $dbhandle->prepare("
                INSERT INTO archived_users 
                (archive_id, first_name, middle_name, last_name, dob, cn_num, email, gender, year, course, password, created_at)
                SELECT id, first_name, middle_name, last_name, dob, cn_num, email, gender, year, course, password, created_at
                FROM users
                WHERE id = ?
            ");
            $archiveStmt->bind_param("i", $id);
            if (!$archiveStmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Error archiving user: ' . $archiveStmt->error]);
                $archiveStmt->close();
                exit();
            }
            $archiveStmt->close();

            // Fetch user email for notification
            $userEmail = '';
            $emailStmt = $dbhandle->prepare("SELECT email FROM users WHERE id = ?");
            $emailStmt->bind_param("i", $id);
            $emailStmt->execute();
            $emailStmt->bind_result($userEmail);
            $emailStmt->fetch();
            $emailStmt->close();

            // Mark user as archived instead of deleting
            $updateStmt = $dbhandle->prepare("UPDATE users SET archived = 1 WHERE id = ?");
            $updateStmt->bind_param("i", $id);
            if ($updateStmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'User archived successfully']);

                // ✅ Add notification here
                $archivedDate = date("Y-m-d H:i:s");
                $adminId = $_SESSION['admin_id']; // Ensure session started
                notifyUserArchived($dbhandle, $userEmail, $archivedDate, $adminId);

            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating user status: ' . $updateStmt->error]);
            }
            $updateStmt->close();

        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request: Missing user ID']);
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
