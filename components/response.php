<div class="container-fluid mt-4">
  <div class="card shadow-sm">
    <!-- Header with filters -->
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h5 class="mb-0">User Responses</h5>
      <div class="row g-2">
        <!-- Search -->
        <div class="col-md-4">
          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" 
                  placeholder="Search by User ID or Name..." 
                  onkeyup="filterTable()">
          </div>
        </div>

        <!-- Status Filter -->
        <div class="col-md-4">
          <select id="statusFilter" class="form-select form-select-sm" onchange="filterTable()">
            <option value="">All Status</option>
            <option value="high">High Risk</option>
            <option value="low">Low Risk</option>
          </select>
        </div>

        <!-- Rows per page -->
        <div class="col-md-4">
          <select id="rowsPerPage" class="form-select form-select-sm" onchange="changeRowsPerPage()">
            <option value="10">10 rows</option>
            <option value="25" selected>25 rows</option>
            <option value="50">50 rows</option>
            <option value="100">100 rows</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table id="likertTable" class="table table-striped table-hover align-middle mb-0">
        <thead class="table-light" id="tableHead">
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Test Date</th>
            <th>Score</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <!-- Rows injected dynamically by response.js -->
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="card-footer d-flex justify-content-center">
      <nav id="paginationControls"></nav>
    </div>
  </div>
</div>

<!-- Bootstrap & FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<script src="../script/response.js"></script>
