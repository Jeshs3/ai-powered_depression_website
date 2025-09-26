<?php
    include "../database/connection.php"; 
    include "header.php";

    // Only fetch active users (not archived)
    $sql = "SELECT id, first_name, middle_name, last_name, dob, email, cn_num, gender, year, course 
            FROM users 
            WHERE archived = 0";
    $result = $dbhandle->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <th>Contact Number</th>
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
                            <td>{$row['cn_num']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['year']}</td>
                            <td>". strtoupper($row['course']) ."</td>
                            <td>
                                <button class='btn btn-sm btn-primary edit-btn' data-id='{$row['id']}'>
                                    <i class='fa-solid fa-pen-to-square me-1'></i>Edit
                                </button>
                            </td>
                            <td>
                                <button class='btn btn-sm btn-danger delete-btn' data-id='{$row['id']}'>
                                    <i class='fa-solid fa-trash me-1'></i>Delete
                                </button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr>
                        <td colspan='9' class='text-muted fst-italic py-3'>
                          No active users found
                        </td>
                      </tr>";
            }
            $dbhandle->close();
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="../script/edit_user.js"></script>
</body>
</html>
