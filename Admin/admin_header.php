<?php
  $currentpg = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="topnav">
        <h1>Admin Dashboard</h1>
        <nav class="links">
        <ul>
            <li>
                <a href="home.php" class="<?= $currentpg == 'home.php' ? 'active' : '' ?>"> 
                    <i class="fa-solid fa-house"></i> HOME
                </a>
            </li>
            <li>
                <a href="view_user.php" class="<?= $currentpg == 'view_user.php' ? 'active' : '' ?>">
                    <i class="fa-solid fa-table-list"></i> RECORDS
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-scissors"></i> DELETE RECORDS
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-gear"></i> SETTINGS
                </a>
            </li>
            <li>
                <a href="../user_action/logout.php">
                    <i class="fa-solid fa-right-from-bracket"></i> LOGOUT   
                </a>
            </li>
        </ul>
        </nav>
    </header>
</body>
</html>