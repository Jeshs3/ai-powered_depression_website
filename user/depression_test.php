<?php
    session_start();
    $status = "";
    if (!empty($_SESSION['status'])) {
        $status = $_SESSION['status'];
        unset($_SESSION['status']); // clear it so alert only shows once
    }
    
    // Database connection
    require_once '../database/connection.php'; // Include your database connection file
    
    // Check user status
    $user_status = 'guest'; // default
    $user_data = null;
    
    if (!empty($_SESSION['id'])) {
        // User is logged in - fetch data from database
        $user_id = $_SESSION['id'];
        
        // Prepare and execute query
        $stmt = $dbhandle->prepare("SELECT first_name, middle_name, last_name, email, DATE_FORMAT(dob, '%M %e, %Y') as dob_formatted, 
                               cn_num, gender, year, course, created_at 
                               FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Format the user data for display
            $user_data = [
                'name' => $user['first_name'] . 
                         (!empty($user['middle_name']) ? ' ' . $user['middle_name'] . ' ' : ' ') . 
                         $user['last_name'],
                'email' => $user['email'],
                'dob' => $user['dob_formatted'],
                'contact' => $user['cn_num'],
                'gender' => $user['gender'],
                'year' => $user['year'],
                'course' => $user['course'],
                'join_date' => date('F j, Y', strtotime($user['created_at'])),
                'avatar' => 'default_avatar.png'
            ];
            
            // // Count tests taken by this user (if you have a tests table
            // $tests_stmt = $dbhandle->prepare("SELECT COUNT(*) as test_count FROM tests WHERE user_id = ?");
            // $tests_stmt->bind_param("i", $user_id);
            // $tests_stmt->execute();
            // $tests_result = $tests_stmt->get_result();
            
            // if ($tests_result) {
            //     $tests_data = $tests_result->fetch_assoc();
            //     $user_data['tests_taken'] = $tests_data['test_count'];
            // } else {
            //     // Handle case where tests table doesn't exist or query fails
            //     $user_data['tests_taken'] = 0;
            // }
            
            $user_status = 'logged_in';
            
            // Close statements
            if (isset($tests_stmt)) {
                $tests_stmt->close();
            }
        }
        $stmt->close();
    } elseif (!empty($_SESSION['registered'])) {
        // User just registered but not logged in
        $user_status = 'registered';
    }
    
    // Close database connection
    $dbhandle->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Depression Self-Assessment Test</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="../CSS/d_styles.css">
</head>
<body>

<?php if (!empty($status) && $status === "registered"): ?>
<script>
  Swal.fire({
    title: 'You are registered!',
    text: 'You can now take the test.',
    icon: 'success',
    confirmButtonText: 'Start'
  });
</script>
<?php endif; ?>

<header>
  <div class="header-text">
    <h1>Depression Self-Assessment Test</h1>
    <h4 style="margin:0;">Are you feeling alright?</h4>
  </div>
  <button class="profile-btn">
    <i class="fas fa-user-circle"></i>
  </button>
</header>

<!-- Profile Modal -->
<div id="profileModal" class="profile-modal">
  <div class="profile-content">
    <span class="close-btn" onclick="closeProfile()">&times;</span>
    
    <?php if ($user_status === 'guest'): ?>
      <div class="no-profile">
        <div class="no-profile-icon">
          <i class="fas fa-user-slash"></i>
        </div>
        <h3>No Profile Available</h3>
        <p>You are currently browsing as a guest.</p>
        <div class="login-prompt">
          <p>Log in to view your profile and save your test results.</p>
          <button class="login-btn" onclick="redirectToLogin()">Log In / Register</button>
        </div>
      </div>
    
    <?php elseif ($user_status === 'registered'): ?>
      <div class="profile-header">
        <img src="../assets/default_avatar.png" alt="Avatar" class="profile-avatar">
        <h2>Welcome!</h2>
        <p>Your registration was successful.</p>
      </div>
      <div class="login-prompt">
        <p>Please log in to access your full profile.</p>
        <button class="login-btn" onclick="redirectToLogin()">Log In</button>
      </div>
    
    <?php elseif ($user_status === 'logged_in' && $user_data): ?>
    <div class="profile-header">
      <img src="../assets/<?php echo $user_data['avatar']; ?>" alt="Avatar" class="profile-avatar">
      <h2><?php echo htmlspecialchars($user_data['name']); ?></h2>
    </div>
    
    <div class="profile-details">
      <div class="detail-row">
        <span class="detail-label">Email:</span>
        <span><?php echo htmlspecialchars($user_data['email']); ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Date of Birth:</span>
        <span><?php echo htmlspecialchars($user_data['dob']); ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Contact Number:</span>
        <span><?php echo htmlspecialchars($user_data['contact']); ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Gender:</span>
        <span><?php echo htmlspecialchars($user_data['gender']); ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Year & Course:</span>
        <span><?php echo htmlspecialchars($user_data['year'] . ' - ' . $user_data['course']); ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Member since:</span>
        <span><?php echo $user_data['join_date']; ?></span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Tests taken:</span>
        <span><?php echo $user_data['tests_taken']; ?></span>
      </div>
    </div>
    
    <div class="login-prompt">
      <button class="login-btn" onclick="logout()">Log Out</button>
    </div>
  <?php endif; ?>
  </div>
</div>

<div class="container">
  <div id="loading-overlay" style="display:none;">
    <div class="loading-content">
        <div class="spinner"></div>
        <p>Predicting...</p>
    </div>
 </div>

  <!-- Question Section -->
  <div id="question-card">
    <div id="progress-container">
      <div id="progress-bar"></div>
    </div>
    <div id="progress-text">0% Complete</div>
    <h2 id="question-text"></h2>
    <div id="options" class="options-grid"></div>
    <div class="navigation-controls">
      <button class="nav-btn" id="prev-btn"><i class="fas fa-chevron-left"></i></button>
      <span class="question-count">Question <span id="current-q">1</span>/20</span>
      <button class="nav-btn" id="next-btn">Continue</button>
    </div>
  </div>

  <!-- Info -->
  <div class="info">
    <h2>What is Depression and Anxiety?</h2>
    <p>Depression is a mood disorder that causes persistent sadness and loss of interest. It affects how you feel, think, and behave, often interfering with daily life.</p>
    <p>Anxiety disorders involve excessive and persistent worry, sometimes leading to panic attacks. <a href="https://www.mayoclinic.org/diseases-conditions/depression/symptoms-causes/syc-20356007">Source: Mayo Clinic</a></p>
  </div>

  <!-- Videos -->
  <div class="videos_to_help">
    <h3>Helpful Resources</h3>
    <div class="video-wrapper">
      <iframe src="https://www.youtube.com/embed/7BPQq9QdECo"></iframe>
      <iframe src="https://www.youtube.com/embed/AjEZRc0dyiQ"></iframe>
      <iframe src="https://www.youtube.com/embed/zzfREEPbUsA"></iframe>
    </div>
  </div>
</div>

<script src="../script/header.js"></script>
<script src="../script/test.js"></script>
<script src="../script/answer.js"></script>
<script src="../script/user_info.js"></script>
<script>
  document.addEventListener("showPredictionModal", function () {
    const modalEl = document.getElementById("resultPopup");
    const resultModal = new bootstrap.Modal(modalEl);
    resultModal.show();
  });
</script>
</body>
</html>

