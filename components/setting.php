<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/darkMode.css">
</head>
<body>
  <?php include '../Admin/header.php'; ?>

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
            <form id="profileForm" class="vstack gap-3">
              <div>
                <label class="form-label">Name</label>
                <input type="text" id="username" name="username" class="form-control" required>
              </div>
              <div>
                <label class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
              </div>
              <div>
                <label class="form-label">New Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
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
            <button class="btn btn-outline-primary" id="themeToggle"></button>
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
              <input class="form-check-input" type="checkbox" id="notifications">
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
            <form id="retentionForm" class="vstack gap-3">
              <div>
                <label class="form-label">Keep user results for:</label>
                <select id="dataRetention" name="data_retention" class="form-select">
                  <option value="6">6 months</option>
                  <option value="12">1 year</option>
                  <option value="24">2 years</option>
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

  <script src="../script/setting.js"></script>
</body>
</html>
