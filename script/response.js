(function () {
  let responseData = []; 
  let currentFiltered = [];
  let currentPage = 1;
  let rowsPerPage = 10;

  const table = document.getElementById("likertTable");
  const tableHead = table.querySelector("thead");
  const tableBody = table.querySelector("tbody");
  const paginationContainer = document.getElementById("paginationControls");
  let mode = table.dataset.mode || "short";


  async function loadSubmission(selectedMode = "short") {
    mode = selectedMode;
    try {
      const response = await fetch("../database/get_data.php");
      const data = await response.json();

      // normalize
      responseData = data.map(item => ({
        userid: item.userid,
        id: "USR-" + String(item.userid).padStart(3, "0"),
        name: item.username || "Unknown",
        age: item.age || "-",
        gender: item.gender || "-",
        date: item.date,
        total: item.total,
        probability: item.probability,
        status: parseFloat(item.probability) >= 0.5 ? "high" : "low",
        answers: item.answers || []
      }));

      currentFiltered = [...responseData];
      renderTable();
    } catch (err) {
      console.error("Error loading submissions:", err);
    }
  }

  // helpers for the designing of the table
  // 🔹 Helpers
  function createCell(content, className = "") {
    const td = document.createElement("td");
    if (className) td.className = className;
    if (content instanceof Node) td.appendChild(content);
    else td.innerHTML = content ?? "";
    return td;
  }

  //design for the status cell
  function buildStatusCell(status) {
    const span = document.createElement("span");
    span.className = `badge rounded-pill status-badge status-${status}`;
    span.textContent = status === "high" ? "High Risk" : "Low Risk";
    return span;
  }

  //design for the short action buttons
  function buildShortActions(res) {
    const div = document.createElement("div");
    div.className = "d-flex gap-2";

    return div;
  }

  //design for the long action tables
  function buildLongActions(res) {
    const div = document.createElement("div");
    div.className = "long-actions d-flex justify-content-center gap-2"

    // 🔹 View Answers Button
    const answersBtn = document.createElement("button");
    answersBtn.className = "btn btn-sm btn-outline-primary";
    answersBtn.innerHTML = `<i class="fa fa-list"></i> Answers`;
    answersBtn.onclick = () => {
    const answersHtml = res.answers.map((ans, i) => 
      `<div style="
          display: flex;
          justify-content: space-between;
          padding: 8px 12px;
          border-bottom: 1px solid #e0e0e0;
          background-color: ${i % 2 === 0 ? '#f9f9f9' : '#ffffff'};
          border-radius: 4px;
          margin-bottom: 4px;
        ">
          <span style="font-weight:600; color:#333;">Q${i + 1}:</span>
          <span style="color:#555;">${ans ?? "-"}</span>
      </div>`
    ).join("");

    Swal.fire({
      title: `<strong>Answers for User ${res.userid}</strong>`,
      html: `
        <div style="
          max-height: 350px;
          overflow-y: auto;
          padding: 5px;
        ">
          ${answersHtml}
        </div>
      `,
      width: 650,
      showCloseButton: true,
      confirmButtonText: "Close",
      confirmButtonColor: "#3085d6",
      background: "#ffffff",
      backdrop: "rgba(0,0,0,0.3)"
    });
  };

      // 🔹 Delete Button
      const deleteBtn = document.createElement("button");
      deleteBtn.className = "btn btn-sm btn-outline-danger";
      deleteBtn.innerHTML = `<i class="fa fa-trash"></i> Delete`;

      deleteBtn.onclick = () => {
        Swal.fire({
          title: 'Are you sure?',
          text: `This will delete all submissions for User ID ${res.userid}. This action cannot be undone!`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
        }).then(async (result) => {
          if (result.isConfirmed) {
            try {
              const response = await fetch('../database/action.php?action=delete_submission', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ userId: res.userid })
              });

              if (!response.ok) throw new Error('Network response was not ok');

              const text = await response.text();

              Swal.fire({
                title: 'Deleted!',
                text: text,
                icon: 'success',
                confirmButtonText: 'Close'
              });

              // Optionally, refresh your table or UI here
              // e.g., reloadData();
            } catch (error) {
              console.error(error);
              Swal.fire({
                title: 'Error!',
                text: 'Failed to delete submission. Please try again.',
                icon: 'error',
                confirmButtonText: 'Close'
              });
            }
          }
        });
      };


    // 🔹 Profile Button
      const profileBtn = document.createElement("button"); // use button for JS event
      profileBtn.className = "btn btn-sm btn-outline-secondary";
      profileBtn.innerHTML = `<i class="fa fa-user"></i> Profile`;

      profileBtn.addEventListener("click", async () => {
          try {
              // Fetch all submissions
              const response = await fetch('../database/get_data.php');
              const submissions = await response.json();

              // Filter for the current user
              const userSubmissions = submissions.filter(s => s.userid === res.userid);

              if (!userSubmissions.length) {
                  Swal.fire({
                      icon: 'info',
                      title: 'No submissions found',
                      text: 'This user has not submitted any responses yet.'
                  });
                  return;
              }

              // Latest submission
              const latest = userSubmissions[0];

              // Format answers nicely
              const answersFormatted = latest.answers.length
                  ? latest.answers.map((a, i) => `<strong>Q${i+1}:</strong> ${a}`).join('<br>')
                  : 'No answers submitted';

              // Show SweetAlert
              Swal.fire({
                  title: `<strong>${latest.username}</strong>`,
                  html: `
                      <div style="text-align:left; line-height:1.6;">
                          <p><strong>Age:</strong> ${latest.age ?? 'N/A'}</p>
                          <p><strong>Gender:</strong> ${latest.gender ?? 'N/A'}</p>
                          <p><strong>Status:</strong> <span style="color:${latest.status === 'completed' ? 'green' : 'orange'}">${latest.status}</span></p>
                          <p><strong>Score:</strong> <span style="font-weight:bold; color:#4a90e2;">${latest.total}</span></p>
                          <p><strong>Probability:</strong> <span style="color:#f39c12;">${(latest.probability*100).toFixed(2)}%</span></p>
                          <hr>
                          <p><strong>Latest Answers:</strong></p>
                          <div style="background:#f8f9fa; padding:10px; border-radius:6px; max-height:200px; overflow-y:auto;">
                              ${answersFormatted}
                          </div>
                          <hr>
                          <p><strong>Submitted on:</strong> <em>${new Date(latest.date).toLocaleString()}</em></p>
                      </div>
                  `,
                  width: 650,
                  showCloseButton: true,
                  focusConfirm: false,
                  confirmButtonText: 'Close',
                  confirmButtonColor: '#3085d6',
                  background: '#ffffff'
              });


          } catch (error) {
              console.error('Error fetching user data:', error);
              Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Failed to fetch user data!'
              });
          }
      });

    div.append(answersBtn, profileBtn, deleteBtn);
    return div;
  }


  // 🔹 Main table render
  function renderTable() {
    tableHead.innerHTML = "";
    tableBody.innerHTML = "";

    // Build headers depending on mode
    const headerRow = document.createElement("tr");

    if (mode === "short") {
      const headers = [
        { label: "User ID", thClass: "text-center" },
        { label: "Name", thClass: "text-start" },
        { label: "Age", thClass: "text-center" },
        { label: "Gender", thClass: "text-center" },
        { label: "Test Date", thClass: "text-center" },
        { label: "Score", thClass: "text-end" },
        { label: "Status", thClass: "text-center" },
      ];
      headers.forEach(h => {
        const th = document.createElement("th");
        th.className = h.thClass + " align-middle";
        th.textContent = h.label;
        headerRow.appendChild(th);
      });
    } else { // long
      const headers = [
        { label: "User ID", thClass: "text-center" },
        { label: "Date Submitted", thClass: "text-center" },
        { label: "Total Score", thClass: "text-center" },
        { label: "Status", thClass: "text-center" },
        { label: "Probability Score", thClass: "text-center" },
        { label: "Actions", thClass: "text-center" }
      ];
      headers.forEach(h => {
        const th = document.createElement("th");
        th.className = h.thClass + " align-middle";
        th.textContent = h.label;
        headerRow.appendChild(th);
      });
    }
    tableHead.appendChild(headerRow);

    // Paginate
    const start = (currentPage - 1) * rowsPerPage;
    const paginated = currentFiltered.slice(start, start + rowsPerPage);

    if (paginated.length === 0) {
      const tr = document.createElement("tr");
      const td = createCell("No records found", "text-center text-muted py-4");
      td.colSpan = headerRow.children.length;
      tr.appendChild(td);
      tableBody.appendChild(tr);
    } else {
      paginated.forEach(res => {
        const tr = document.createElement("tr");

        if (mode === "short") {
          tr.appendChild(createCell(res.id, "text-center fw-bold"));
          tr.appendChild(createCell(res.name, "text-start"));
          tr.appendChild(createCell(res.age, "text-center"));
          tr.appendChild(createCell(res.gender, "text-center"));
          tr.appendChild(createCell(res.date, "text-center"));
          tr.appendChild(createCell(res.total, "text-end"));
          // status (node)
          const statusNode = buildStatusCell(res.status); // should return a Node
          tr.appendChild(createCell(statusNode, "text-center"));
          // actions
          tr.appendChild(createCell(buildShortActions(res), "text-center"));
        } else {
          tr.appendChild(createCell(res.userid, "text-center"));
          tr.appendChild(createCell(new Date(res.date).toLocaleDateString(), "text-center"));
          tr.appendChild(createCell(res.total, "text-center"));
          // status as badge node
          tr.appendChild(createCell(buildStatusCell(res.status), "text-center"));
          tr.appendChild(createCell((res.probability ?? "Pending"), "text-center"));
          // actions cell
          tr.appendChild(createCell(buildLongActions(res), "text-center"));
        }

        tableBody.appendChild(tr);
      });
    }

    renderPagination();
  }

  function filterTable(inputId = 'searchInput') {
    const searchValue = document.getElementById(inputId)?.value.toLowerCase() || "";
    const statusValue = document.getElementById("statusFilter")?.value || "";

    currentFiltered = responseData.filter(res => {
      let matchesSearch = (mode === "short")
        ? res.id.toLowerCase().includes(searchValue) || res.name.toLowerCase().includes(searchValue)
        : res.id.toLowerCase().includes(searchValue);

      let matchesStatus = statusValue ? res.status === statusValue : true;

      return matchesSearch && matchesStatus;
    });

    currentPage = 1;
    renderTable();
  }

  function renderPagination() {
    paginationContainer.innerHTML = "";
    const totalPages = Math.ceil(currentFiltered.length / rowsPerPage);
    if (totalPages <= 1) return;

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.disabled = i === currentPage;
      btn.onclick = () => { currentPage = i; renderTable(); };
      paginationContainer.appendChild(btn);
    }
  }

  window.filterTable = filterTable;
  window.loadSubmission = loadSubmission;
  window.viewDetails = (id) => alert("View details for " + id);
  window.deleteData = (id) => alert("Delete " + id);

  document.addEventListener("DOMContentLoaded", () => {
    loadSubmission(mode);
  });

})();
