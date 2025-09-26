<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/a2e0e6ad5c.js" crossorigin="anonymous"></script>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .notification-card {
      transition: all 0.2s ease;
      cursor: pointer;
    }
    .notification-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .notification-unread {
      border-left: 4px solid #0d6efd;
      background-color: #e9f2ff;
    }
    .notification-read {
      border-left: 4px solid transparent;
      background-color: #ffffff;
    }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold">Notifications</h2>
      <button id="markAllRead" class="btn btn-sm btn-outline-primary">
        <i class="fa-solid fa-check-double me-1"></i> Mark All as Read
      </button>
    </div>

    <!-- Notifications list -->
    <div id="notificationList" class="row g-3">
      <!-- Dynamic notifications go here -->
    </div>
  </div>
    <script src="../script/notification.js"></script>
</body>
</html>
