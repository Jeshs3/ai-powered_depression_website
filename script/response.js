(function () {
  let responseData = []; 
let currentPage = 1;
let rowsPerPage = 25;

// Fetch data from PHP API
async function loadSubmission() {
  try {
    const response = await fetch("../database/get_data.php");
    const data = await response.json();

    console.log("Fetched data:", data); 

    // Map PHP JSON → table-friendly structure
    responseData = data.map(item => ({
      id: "USR-" + String(item.userid).padStart(3, "0"), // generate user code
      name: item.username || "Unknown",  // from joined users table
      age: item.age || "-",              // only if you add age in your SQL
      gender: item.gender || "-",        // only if you add gender in your SQL
      date: item.date,
      score: item.total,
      status: parseFloat(item.probability) >= 0.5 ? "high" : "low" // example
    }));

    console.log("Mapped responseData:", responseData);
    renderTable();
  } catch (error) {
    console.error("Error loading data:", error);
    document.getElementById("tableBody").innerHTML =
      `<tr><td colspan="8">Error loading data</td></tr>`;
  }
}

function renderTable() {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = "";

  const start = (currentPage - 1) * rowsPerPage;
  const paginatedData = responseData.slice(start, start + rowsPerPage);

  paginatedData.forEach(row => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${row.id}</td>
      <td>${row.name}</td>
      <td>${row.age}</td>
      <td>${row.gender}</td>
      <td>${row.date}</td>
      <td>${row.score}</td>
      <td><span class="status-${row.status}">${row.status === "high" ? "High Risk" : "Low Risk"}</span></td>
      <td><button class="view-details" onclick="viewDetails('${row.id}')">View</button></td>
    `;
    tbody.appendChild(tr);
  });

  renderPagination();
}

function renderPagination() {
  const totalPages = Math.ceil(responseData.length / rowsPerPage);
  const controls = document.getElementById("paginationControls");
  controls.innerHTML = "";

  const prevBtn = document.createElement("button");
  prevBtn.textContent = "« Previous";
  prevBtn.onclick = () => changePage("prev");
  controls.appendChild(prevBtn);

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    if (i === currentPage) btn.classList.add("active");
    btn.onclick = () => { currentPage = i; renderTable(); };
    controls.appendChild(btn);
  }

  const nextBtn = document.createElement("button");
  nextBtn.textContent = "Next »";
  nextBtn.onclick = () => changePage("next");
  controls.appendChild(nextBtn);
}

function changePage(direction) {
  const totalPages = Math.ceil(responseData.length / rowsPerPage);
  if (direction === "prev" && currentPage > 1) currentPage--;
  if (direction === "next" && currentPage < totalPages) currentPage++;
  renderTable();
}

function filterTable() {
  const searchValue = document.getElementById("searchInput").value.toLowerCase();
  const statusValue = document.getElementById("statusFilter").value;

  const filtered = responseData.filter(row =>
    (row.id.toLowerCase().includes(searchValue) || row.name.toLowerCase().includes(searchValue)) &&
    (statusValue ? row.status === statusValue : true)
  );

  currentPage = 1;
  renderFilteredTable(filtered);
}

function renderFilteredTable(data) {
  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = "";

  const start = (currentPage - 1) * rowsPerPage;
  const paginatedData = data.slice(start, start + rowsPerPage);

  paginatedData.forEach(row => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${row.id}</td>
      <td>${row.name}</td>
      <td>${row.age}</td>
      <td>${row.gender}</td>
      <td>${row.date}</td>
      <td>${row.score}</td>
      <td><span class="status-${row.status}">${row.status === "high" ? "High Risk" : "Low Risk"}</span></td>
      <td><button class="view-details" onclick="viewDetails('${row.id}')">View</button></td>
    `;
    tbody.appendChild(tr);
  });
}

function changeRowsPerPage() {
  rowsPerPage = parseInt(document.getElementById("rowsPerPage").value, 10);
  currentPage = 1;
  renderTable();
}

function viewDetails(userId) {
  alert("View details for: " + userId);
}

// Initial load
document.addEventListener("DOMContentLoaded", loadSubmission);

})();