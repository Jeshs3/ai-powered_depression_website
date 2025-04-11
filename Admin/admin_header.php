<?php
  $currentpg = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/home.css">
</head>
<body>
    <header class="topnav">
        <h1>Admin Dashboard</h1>
        <nav class="links">
        <ul>
            <li><a href="home.php" class="<?= $currentpg == 'home.php' ? 'active' : '' ?>">🏠 HOME</a></li>
            <li><a href="view_user.php" class="<?= $currentpg == 'view_user.php' ? 'active' : '' ?>">RECORDS</a></li>
            <li><a href="#">✂️ DELETE RECORDS</a></li>
            <li><a href="#">⚙️ SETTINGS</a></li>
            <li><a href="../user_action/logout.php">🚪 LOGOUT</a></li>
        </ul>
        </nav>
    </header>
</body>
</html>