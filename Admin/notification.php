<?php

include "../database/connection.php";

// Generic function to add a notification
function addNotification($dbhandle, $userId, $type, $message, $link = null) {
    $stmt = $dbhandle->prepare("
        INSERT INTO notifications (user_id, type, message, link) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $userId, $type, $message, $link);
    $stmt->execute();
    $stmt->close();
}

// New user registered
function notifyNewUser($dbhandle, $email, $registeredDate, $adminId = null) {
    $message = "$email registered on $registeredDate";
    addNotification($dbhandle, $adminId, 'new_user', $message);
}

//User archived
function notifyUserArchived($dbhandle, $email, $archivedDate, $adminId) {
    $message = "$email was archived by admin on $archivedDate";
    addNotification($dbhandle, $adminId, 'user_archived', $message);
}

//User edited
function notifyUserEdited($dbhandle, $email, $editedDate, $adminId) {
    $message = "$email information was edited by admin on $editedDate";
    addNotification($dbhandle, $adminId, 'user_edited', $message);
}

//New submissions received (daily count)
// Daily submissions notification (triggered once daily at 12AM)
function notifyDailySubmissions($dbhandle, $adminId, $count) {
    if ($count > 0) {
        $message = "$count users submitted their responses yesterday";
        addNotification($dbhandle, $adminId, 'daily_submission', $message);
    }
}

//High probability alert
function notifyHighRisk($dbhandle, $adminId, $count) {
    if ($count > 0) {
        $message = "$count users are in high risk today";
        addNotification($dbhandle, $adminId, 'high_risk', $message);
    }
}

//Low probability alert
function notifyLowRisk($dbhandle, $adminId, $count) {
    if ($count > 0) {
        $message = "$count users are in low risk today";
        addNotification($dbhandle, $adminId, 'low_risk', $message);
    }
}

//Database errors
function notifyDatabaseError($dbhandle, $errorMessage, $adminId = null) {
    $message = "Database error occurred: $errorMessage";
    addNotification($dbhandle, $adminId, 'db_error', $message);
}

//Failed login attempts / security breach
function notifySecurityBreach($dbhandle, $details, $adminId = null) {
    $message = "Security breach detected: $details";
    addNotification($dbhandle, $adminId, 'security_breach', $message);
}
?>

