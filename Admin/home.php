<?php include 'admin_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Depression Admin Dashboard</title>
  <link rel="stylesheet" href="../CSS/home.css">
</head>
<body>

  <div class="dashboard-container">

    <h2>User Responses</h2>
    <hr>
    <br>
    <br>
    <div class="filters">
    <input type="text" id="searchInput" placeholder="Search by User ID..." onkeyup="filterTable()">
    
    <select id="statusFilter" onchange="filterTable()">
      <option value="">All Status</option>
      <option value="1">High</option>
      <option value="0">Low</option>
    </select>
  </div>

    <div class="scrollable-table">
      <table id="likertTable">
        <thead id="tableHead"></thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
    <div id="paginationControls" class="pagination-controls"></div>
  </div>
  <script src="../script/home.js"></script>
</body>
</html>
