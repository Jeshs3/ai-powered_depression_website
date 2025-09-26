<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Depression Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
  <link rel="stylesheet" href="../CSS/table.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/darkMode.css">
</head>
<body>
  <div class="container-fluid py-4">
    <div class="dashboard-container card shadow-sm p-4">

       <!-- Header with title and export button -->
      <div class="container-fluid my-4">
      <!-- Header with Title & Export -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-primary m-0">
          <i class="fas fa-users me-2"></i> User Submissions
        </h1>
        <button class="btn btn-outline-primary" id="exportBtn">
          <i class="fas fa-download me-1"></i> Export Data
        </button>
      </div>

      <!-- Stats Card -->
      <div class="row">
        <div class="col-md-4 col-lg-3">
          <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body d-flex justify-content-between align-items-center">
              <div>
                <p class="text-uppercase text-muted small mb-1">Total Submissions</p>
                <h2 class="fw-bold text-dark mb-0" id="totalResponses">0</h2>
              </div>
              <div class="text-primary">
                <i class="fas fa-chart-bar fa-2x"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr class="mb-4">

      <!-- Filters -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div class="flex-grow-1 me-3"">
        <?php 
          $inputId = 'longSearch'; 
          $placeholder = 'Search by User ID...'; 
          include '../components/filter.php'; 
        ?>
      </div>
      <div>
        <select id="statusFilter" class="form-select form-select-sm" onchange="filterTable()">
          <option value="">All Status</option>
          <option value="high">High</option>
          <option value="low">Low</option>
        </select>
      </div>
    </div>

      <!-- Table -->
      <!-- Table Section -->
      <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold text-primary">
            <i class="fas fa-table me-2"></i> User Responses Table
          </h5>
          <small class="text-muted">Sortable & Filterable</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="likertTable" class="table table-striped table-hover align-middle mb-0" data-mode="long">
              <thead class="table-light" id="tableHead"></thead>
              <tbody id="tableBody"></tbody>
            </table>
          </div>
        </div>
      </div>


      <!-- Pagination -->
      <div id="paginationControls" class="d-flex justify-content-center mt-3"></div>
    </div>
  </div>
  <script src=../script/statistics.js></script>
  <script src="../script/response.js"></script>
  <script src="../script/export.js"></script>
  <script src="../script/setting.js"></script>
  <script src="../script/theme.js"></script>
</body>
</html>
