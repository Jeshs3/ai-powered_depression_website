<?php
session_start();

if (isset($_SESSION['id'])) {
    echo $_SESSION['id'];  
} else {
    http_response_code(401);
    echo "Unauthorized";
}
?>

