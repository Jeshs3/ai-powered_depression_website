<?php
    include "connection.php"; 
    include "delete_user.php";

    $sql = "SELECT id, first_name, middle_name, last_name, dob, email, gender, year, course FROM users";
    $result = $dbhandle->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link rel="stylesheet" href="../CSS/view_user.css">
</head>
<body>

    <h2>List of Users</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Gender</th>
            <th>Year</th>
            <th>Course</th>
            <th></th>
            <th></th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>". $row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name'] ."</td>
                        <td>{$row['dob']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['year']}</td>
                        <td>{$row['course']}</td>
                        <td>
                        <a href='delete_user.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete the user?');\">Delete</a>
                        </td>
                        <td>
                        <a href='edit_user.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to edit the user?');\">Edit</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='9'>No users found</td></tr>";
        }
        $dbhandle->close();
        ?>
    </table>

</body>
</html>