<?php
    include "../database/connection.php"; 
    include "../database/delete_user.php";
    include "header.php";

    $sql = "SELECT id, first_name, middle_name, last_name, dob, email, gender, year, course FROM users";
    $result = $dbhandle->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-5">
    <div class="card shadow-lg rounded-3">
      <div class="card-body">
        <h2 class="card-title text-center text-primary mb-4">
          <i class="fa-solid fa-users me-2"></i> List of Users
        </h2>

        <div class="table-responsive">
          <table class="table table-hover align-middle text-center">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Year</th>
                <th>Course</th>
                <th colspan="2">Actions</th>
              </tr>
            </thead>
            <tbody>
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
                              <td>". strtoupper($row['course']) ."</td>
                              <td>
                                <a href='edit_user.php?id={$row['id']}'
                                   class='btn btn-sm btn-primary'
                                   onclick=\"return confirm('Are you sure you want to edit this user?');\">
                                   <i class='fa-solid fa-pen-to-square me-1'></i>Edit
                                </a>
                              </td>
                              <td>
                                <a href='../database/action.php?action=delete_user&id={$row['id']}'
                                  class='btn btn-sm btn-danger'
                                  onclick=\"return confirm('Are you sure you want to delete this user?');\">
                                  <i class='fa-solid fa-trash me-1'></i>Delete
                                </a>
                              </td>
                            </tr>";
                  }
              } else {
                  echo "<tr><td colspan='9' class='text-muted fst-italic py-3'>No users found</td></tr>";
              }
              $dbhandle->close();
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>