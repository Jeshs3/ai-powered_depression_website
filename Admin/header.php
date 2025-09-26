<?php
  $currentpg = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../CSS/global.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-header px-5">
    <div class="container-fluid">
      <!-- Left Section: Profile + Title -->
      <div class="d-flex align-items-center">
        <img src="../assets/admin-avatar.png" 
             alt="Admin Profile" 
             class="rounded-circle me-2" 
             style="width:40px; height:40px;">
        <h1 class="h4 text-white mb-0">Admin Dashboard</h1>
      </div>

      <!-- Toggle button for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Nav links -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="../Admin/analytics.php" 
               class="nav-link <?= $currentpg == '../Admin/analytics.php' ? 'active' : '' ?>" 
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="HOME">
              <i class="fa-solid fa-house me-2 icon-md"></i>
            </a>
          </li>
          <li class="nav-item">
            <a href="../Admin/response_list.php" 
               class="nav-link <?= $currentpg == '../Admin/response_list.php' ? 'active' : '' ?>" 
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="RESPONSES">
              <i class="fa-solid fa-clipboard-list me-2 icon-md"></i>
            </a>
          </li>
          <li class="nav-item">
            <a href="../Admin/user_list.php" 
               class="nav-link <?= $currentpg == '../Admin/user_list.php' ? 'active' : '' ?>" 
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="RECORDS">
              <i class="fa-solid fa-table-list me-2 icon-md"></i>
            </a>
          </li>
          <!-- this is notification bell -->
           <?php include '../components/bell.php'; ?>
           
          <li class="nav-item">
            <a href="../components/setting.php"
               class="nav-link <?= $currentpg == '../components/setting.php' ? 'active' : '' ?>" 
               data-bs-toggle="tooltip" data-bs-placement="bottom" title="SETTINGS">
              <i class="fa-solid fa-gear me-2 icon-md"></i>
            </a>
          </li>
          <li class="nav-item">
            <form action="../user_action/logout.php" method="POST" class="d-inline">
              <button type="submit" class="btn nav-link border-0 bg-transparent" data-bs-toggle="tooltip" title="LOGOUT">
                <i class="fa-solid fa-right-from-bracket me-2 icon-md"></i>
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Enable tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
      new bootstrap.Tooltip(el);
    });
  </script>
</body>
</html>