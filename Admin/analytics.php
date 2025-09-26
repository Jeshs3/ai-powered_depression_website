<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Depression Detection Analytics Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/darkMode.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container-fluid py-3 px-3">
    
    <!-- Dashboard Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
      <h1 class="h3 mb-3 mb-lg-0">Depression Detection Analytics Dashboard</h1>
      <div class="d-flex gap-2">
        <select id="timeRange" class="form-select">
          <option value="7">Last 7 Days</option>
          <option value="30" selected>Last 30 Days</option>
          <option value="90">Last 90 Days</option>
          <option value="365">Last Year</option>
        </select>
        <button class="btn btn-primary" onclick="applyDateFilter()">Apply</button>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3">
      
      <!-- Total Users -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="text-muted mb-0">Total Users</h6>
              <i class="fas fa-users text-primary fs-3"></i>
            </div>
            <h3 id="totalUsers" class="mt-3 mb-0">0</h3>
            <small id="totalUsersChange" class="text-muted"></small>
          </div>
        </div>
      </div>

      <!-- High Risk -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="text-muted mb-0">High Risk of Depression</h6>
              <i class="fas fa-exclamation-triangle text-danger fs-3"></i>
            </div>
            <h3 id="highRisk" class="mt-3 mb-0">0</h3>
            <small id="highRiskChange" class="text-muted"></small>
          </div>
        </div>
      </div>

      <!-- Low Risk -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="text-muted mb-0">Low Risk of Depression</h6>
              <i class="fas fa-check-circle text-success fs-3"></i>
            </div>
            <h3 id="lowRisk" class="mt-3 mb-0">0</h3>
            <small id="lowRiskChange" class="text-muted"></small>
          </div>
        </div>
      </div>

      <!-- Depression Rate -->
      <div class="col-md-6 col-lg-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="text-muted mb-0">Depression Rate</h6>
              <i class="fas fa-percent text-info fs-3"></i>
            </div>
            <h3 id="depressionRate" class="mt-3 mb-0">0%</h3>
            <small id="depressionRateChange" class="text-muted"></small>
          </div>
        </div>
      </div>
    </div>

    <!-- Graphs & tables-->
    <div class="mt-4">
      <?php include "../components/graph.php"; ?>
      <?php include "../components/response.php"; ?>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../script/statistics.js"></script>
  <script src="../script/response.js"></script>
  <script src="../script/setting.js"></script>
  <script src="../script/theme.js"></script>
</body>
</html>