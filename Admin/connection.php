<?php
    $database = "depression_db";
    $port = 3306;
    $username = "admin_1000";
    $password = "ORANGEblueyellow";
    $hostname = "localhost";
    $dbhandle = mysqli_connect($hostname, $username, $password, $database, $port) or die("Unable to connect to mySQL");

    #echo "<script>alert('You are now connected.')</script>";
    echo "";

    $selected = mysqli_select_db($dbhandle, $database) or die("Could not select database");
?>