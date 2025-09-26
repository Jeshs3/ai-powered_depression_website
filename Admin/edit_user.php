<?php
    session_start();
    include "../database/connection.php";
    include "../Admin/notification.php"; // <- contains notifyUserEdited + addNotification

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // sanitize input
    
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $dbhandle->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
        } else {
            echo "User not found.";
            exit();
        }
        $stmt->close();
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $last_name = $_POST["last_name"];
        $dob = $_POST["dob"];
        $cn_num = $_POST["cn_num"];
        $email = $_POST["email"];
        $gender = $_POST["gender"];
        $year = $_POST["year"];
        $course = $_POST["course"];

        $sql = "UPDATE users 
                SET first_name=?, middle_name=?, last_name=?, dob=?, email=?, cn_num=?, gender=?, year=?, course=?
                WHERE id=?";
        $stmt = $dbhandle->prepare($sql);
        $stmt->bind_param("sssssssssi", $first_name, $middle_name, $last_name, $dob, $email, $cn_num, $gender, $year, $course, $id);
    
        if ($stmt->execute()) {
            // ✅ Add notification after successful update
            // if (isset($_SESSION['admin_id'])) {
            //     $adminId = $_SESSION['admin_id'];
            //     $editedDate = date("Y-m-d H:i:s");
            //     notifyUserEdited($dbhandle, $email, $editedDate, $adminId);
            // }
            $adminId = 1; // Fixed admin reference
            $editedDate = date("Y-m-d H:i:s");
            notifyUserEdited($dbhandle, $email, $editedDate, $adminId);

            echo <<<HTML
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'User information is edited successfully!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'user_list.php';
                });
            });
            </script>
            HTML;
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Information Form</title>
</head>
<body class=bg-light>
   <div class="container my-5">
    <div class="card shadow-sm rounded-4 p-4">
      <h2 class="mb-4 text-center">Edit User Information</h2>

      <form method="post" class="row g-3 needs-validation" novalidate>
        <!-- First Name -->
        <div class="col-md-4 form-floating">
          <input type="text" class="form-control" id="firstname" name="first_name" 
                 value="<?= $row['first_name'] ?>" placeholder="First Name" required>
          <label for="firstname">First Name</label>
          <div class="invalid-feedback">Please enter a first name.</div>
        </div>

        <!-- Middle Name -->
        <div class="col-md-4 form-floating">
          <input type="text" class="form-control" id="middlename" name="middle_name" 
                 value="<?= $row['middle_name'] ?>" placeholder="Middle Name" required>
          <label for="middlename">Middle Name</label>
          <div class="invalid-feedback">Please enter a middle name.</div>
        </div>

        <!-- Last Name -->
        <div class="col-md-4 form-floating">
          <input type="text" class="form-control" id="lastname" name="last_name" 
                 value="<?= $row['last_name'] ?>" placeholder="Last Name" required>
          <label for="lastname">Last Name</label>
          <div class="invalid-feedback">Please enter a last name.</div>
        </div>

        <!-- Birthdate -->
        <div class="col-md-4 form-floating">
          <input type="date" class="form-control" id="dob" name="dob"
                 min="1970-01-01" max="2025-01-01" value="<?= $row['dob'] ?>" required>
          <label for="dob">Birthdate</label>
          <div class="invalid-feedback">Please select a valid birthdate.</div>
        </div>

        <!-- Gender -->
        <div class="col-md-4">
          <label class="form-label d-block">Gender</label>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="male" value="M" 
                   <?= ($row['gender'] == 'M') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="male">Male</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="female" value="F" 
                   <?= ($row['gender'] == 'F') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="female">Female</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="others" value="0" 
                   <?= ($row['gender'] == '0') ? 'checked' : '' ?> required>
            <label class="form-check-label" for="others">Others</label>
          </div>
          <div class="invalid-feedback d-block">Please select a gender.</div>
        </div>

        <!-- Year -->
        <div class="col-md-4 form-floating">
          <select class="form-select" id="year" name="year" required>
            <option value="" disabled>Select a year</option>
            <option value="1st" <?= ($row['year'] == '1st') ? 'selected' : '' ?>>First Year</option>
            <option value="2nd" <?= ($row['year'] == '2nd') ? 'selected' : '' ?>>Second Year</option>
            <option value="3rd" <?= ($row['year'] == '3rd') ? 'selected' : '' ?>>Third Year</option>
            <option value="4th" <?= ($row['year'] == '4th') ? 'selected' : '' ?>>Fourth Year</option>
            <option value="5th" <?= ($row['year'] == '5th') ? 'selected' : '' ?>>Fifth Year</option>
          </select>
          <label for="year">Year</label>
          <div class="invalid-feedback">Please select a year.</div>
        </div>

        <!-- Course -->
        <div class="col-md-4 form-floating">
          <select class="form-select" id="course" name="course" required>
            <option value="" disabled>Select a course</option>
            <option value="bscs" <?= ($row['course'] == 'bscs') ? 'selected' : '' ?>>BSCS</option>
            <option value="bshm" <?= ($row['course'] == 'bshm') ? 'selected' : '' ?>>BSHM</option>
            <option value="bses" <?= ($row['course'] == 'bses') ? 'selected' : '' ?>>BSES</option>
            <option value="bsed_math" <?= ($row['course'] == 'bsed_math') ? 'selected' : '' ?>>BSEd-Math</option>
            <option value="btle-he" <?= ($row['course'] == 'btle-he') ? 'selected' : '' ?>>BTLE-HE</option>
            <option value="beed" <?= ($row['course'] == 'beed') ? 'selected' : '' ?>>BEEd</option>
          </select>
          <label for="course">Course</label>
          <div class="invalid-feedback">Please select a course.</div>
        </div>

        <!-- Email -->
        <div class="col-md-6 form-floating">
          <input type="email" class="form-control" id="email" name="email" 
                 value="<?= $row['email'] ?>" placeholder="Email" required>
          <label for="email">Email</label>
          <div class="invalid-feedback">Please enter a valid email.</div>
        </div>

        <!-- Contact Number -->
        <div class="col-md-6 form-floating">
          <input type="text" class="form-control" id="cn_num" name="cn_num" 
                 value="<?= $row['cn_num'] ?>" placeholder="Contact Number" required>
          <label for="cn_num">Contact Number</label>
          <div class="invalid-feedback">Please enter a valid contact number.</div>
        </div>

        <!-- Password error -->
        <div class="col-12">
          <p id="password_error" class="text-danger" style="display: none;">Passwords do not match!</p>
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-center">
          <button type="submit" class="btn btn-primary btn-lg px-5">Update Information</button>
        </div>

      </form>
    </div>
  </div>


  <!-- Bootstrap validation script -->
  <script>
    // Enable Bootstrap validation
    (function () {
      'use strict'
      const forms = document.querySelectorAll('.needs-validation')
      Array.from(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }
          form.classList.add('was-validated')
        }, false)
      })

    //this is for phone numbers
    const cnInput = document.getElementById('cn_num');

    cnInput.addEventListener('input', (e) => {
        let x = e.target.value.replace(/\D/g, ''); // Remove non-digits
        if (x.length > 4 && x.length <= 7) {
        x = x.replace(/(\d{4})(\d+)/, '$1 $2');
        } else if (x.length > 7) {
        x = x.replace(/(\d{4})(\d{3})(\d+)/, '$1 $2 $3');
        }
        e.target.value = x;
    });

    // Optional: limit max length
    cnInput.setAttribute('maxlength', '11'); // standard 10 digits + optional 1
    })()
  </script>
</body>
</html>