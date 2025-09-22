<?php include '../Admin/header.php'; ?>

<?php
// setting.php
session_start();

// Example: Get current admin data (replace with DB fetch)
$admin = [
    "name" => "Admin",
    "email" => "admin@example.com",
    "theme" => "light",
    "notifications" => true,
    "data_retention" => 12 // months
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Settings</title>
    <!-- <script src="assets/js/settings.js" defer></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-5">
    <!-- Header -->
    <header class="text-center mb-5">
      <h1 class="fw-bold text-primary mb-2">
        <i class="fa-solid fa-gear me-2"></i> Admin Settings
      </h1>
      <p class="text-muted">Manage your profile, preferences, and system behavior</p>
    </header>

    <!-- Settings Grid -->
    <div class="row g-4">
      
      <!-- Profile Card -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h2 class="h5 mb-4">
              <i class="fa-solid fa-user text-primary me-2"></i> Profile
            </h2>
            <form method="post" action="update_profile.php" class="vstack gap-3">
              <div>
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" 
                       value="<?= htmlspecialchars($admin['name']) ?>" required>
              </div>
              <div>
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($admin['email']) ?>" required>
              </div>
              <div>
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
                <div class="form-text">Leave blank to keep current password</div>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-save me-1"></i> Save Changes
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Theme Card -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h2 class="h5 mb-3">
              <i class="fa-solid fa-moon text-primary me-2"></i> Theme
            </h2>
            <p class="text-muted">Choose your preferred appearance mode</p>
            <button class="btn btn-outline-primary" id="themeToggle">
              <i class="fa-solid fa-sun me-2"></i> Switch to Light/Dark
            </button>
          </div>
        </div>
      </div>

      <!-- Notifications Card -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h2 class="h5 mb-4">
              <i class="fa-solid fa-bell text-primary me-2"></i> Notifications
            </h2>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="notifications"
                     <?= $admin['notifications'] ? 'checked' : '' ?>>
              <label class="form-check-label" for="notifications">
                Enable system alerts and updates
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Retention Card -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h2 class="h5 mb-4">
              <i class="fa-solid fa-database text-primary me-2"></i> Data Retention
            </h2>
            <form method="post" action="update_retention.php" class="vstack gap-3">
              <div>
                <label class="form-label">Keep user results for:</label>
                <select name="data_retention" class="form-select">
                  <option value="6" <?= $admin['data_retention']==6 ? 'selected' : '' ?>>6 months</option>
                  <option value="12" <?= $admin['data_retention']==12 ? 'selected' : '' ?>>1 year</option>
                  <option value="24" <?= $admin['data_retention']==24 ? 'selected' : '' ?>>2 years</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check me-1"></i> Save Preference
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</body>

</html>
