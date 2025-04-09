<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Depression Admin Dashboard</title>
  <link rel="stylesheet" href="../CSS/home.css">
</head>
<body>

<header class="topnav">
    <h1>Admin Dashboard</h1>
    <nav>
      <ul>
        <li><a href="#">🏠 Home</a></li>
        <li><a href="view_user.php">📁 File</a></li>
        <li><a href="#">✂️ Delete Record</a></li>
        <li><a href="#">⚙️ Settings</a></li>
        <li><a href="#">🚪 Logout</a></li>
      </ul>
    </nav>
  </header>

  <div class="dashboard-container">

    <h2>User Responses</h2>
    <hr>
    <br>
    <br>
    <div class="filters">
    <input type="text" id="searchInput" placeholder="Search by User ID..." onkeyup="filterTable()">
    
    <select id="statusFilter" onchange="filterTable()">
      <option value="">All Status</option>
      <option value="1">Depressed</option>
      <option value="0">Not Depressed</option>
    </select>
  </div>

    <div class="scrollable-table">
      <table id="likertTable">
        <thead id="tableHead"></thead>
        <tbody id="tableBody"></tbody>
      </table>
    </div>
  </div>
  <script src="../home.js"></script>
</body>
</html>
