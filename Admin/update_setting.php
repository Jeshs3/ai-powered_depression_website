<?php
session_start();
header("Content-Type: application/json");

include "../database/connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['key']) || !isset($input['value'])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit();
}

$key = $input['key'];
$value = $input['value'];
$adminId = $_SESSION['admin_id'] ?? null;

if (!$adminId) {
    echo json_encode(["success" => false, "message" => "Not authenticated"]);
    exit();
}

try {
    switch ($key) {
        case "theme":
            $stmt = $dbhandle->prepare("UPDATE admins SET theme = ? WHERE admin_id = ?");
            $stmt->bind_param("si", $value, $adminId);
            break;

        case "notifications":
            $stmt = $dbhandle->prepare("UPDATE admins SET notifications = ? WHERE admin_id = ?");
            $stmt->bind_param("ii", $value, $adminId);
            break;

        case "data_retention":
            $stmt = $dbhandle->prepare("UPDATE admins SET data_retention = ? WHERE admin_id = ?");
            $stmt->bind_param("ii", $value, $adminId);
            break;

        case "profile":
            // Profile update is an object { username, email, password? }
            $username = $value['username'] ?? null;
            $email = $value['email'] ?? null;
            $password = $value['password'] ?? null;

            if ($password && strlen(trim($password)) > 0) {
                $hashed = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $dbhandle->prepare(
                    "UPDATE admins SET username = ?, email = ?, password = ? WHERE admin_id = ?"
                );
                $stmt->bind_param("sssi", $username, $email, $hashed, $adminId);
            } else {
                $stmt = $dbhandle->prepare(
                    "UPDATE admins SET username = ?, email = ? WHERE admin_id = ?"
                );
                $stmt->bind_param("ssi", $username, $email, $adminId);
            }
            break;

        default:
            echo json_encode(["success" => false, "message" => "Unknown setting key"]);
            exit();
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Setting updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed"]);
    }

    $stmt->close();
    $dbhandle->close();

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
