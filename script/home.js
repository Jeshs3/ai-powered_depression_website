document.addEventListener("DOMContentLoaded", function () {
  const questionCount = 20;
  let responses = [];

  // Fetch the submissions data
  fetch('/BANOL6/Admin/get_data.php') // adjust path if needed
    .then(res => res.json())
    .then(data => {
        responses = data;
        renderTable(responses);
    })
    .catch(error => {
        console.error("Error fetching responses:", error);
        alert("Failed to load submissions.");
    });

  const tableHead = document.getElementById('tableHead');
  const tableBody = document.getElementById('tableBody');

  // Table headers setup
  const headerRow = document.createElement('tr');
  const staticHeaders = ["User ID", "Date Submitted"];
  staticHeaders.forEach(text => {
      const th = document.createElement('th');
      th.textContent = text;
      headerRow.appendChild(th);
  });

  // Create dynamic headers for each question
  for (let i = 1; i <= questionCount; i++) {
      const th = document.createElement('th');
      th.textContent = `Q${i}`;
      headerRow.appendChild(th);
  }

  // Final headers (Total Score, Status, Action)
  const finalHeaders = ["Total Score", "Status", "Probability Score", "Action", "View Profile"];
  finalHeaders.forEach(text => {
      const th = document.createElement('th');
      th.textContent = text;
      headerRow.appendChild(th);
  });

  tableHead.appendChild(headerRow);

  // Function to render the table with filtered responses
  function renderTable(filteredResponses) {
      tableBody.innerHTML = "";  // Clear existing table rows
      filteredResponses.forEach(res => {
          const tr = document.createElement('tr');
          tr.innerHTML += `<td>${res.userid}</td>`;
          tr.innerHTML += `<td>${new Date(res.date).toLocaleDateString()}</td>`; // Format date

          // Render each answer (Q1, Q2, ..., Q20)
          const answers = res.answers?.slice(0, questionCount) ?? [];
          for (let i = 0; i < questionCount; i++) {
            const display = answers[i] ?? "-";
            tr.innerHTML += `<td>${display}</td>`;
         }
        

          tr.innerHTML += `<td>${res.score}</td>`;
          tr.innerHTML += `<td>${res.status}</td>`;
          tr.innerHTML += `<td>${res.probability ?? "Pending Analysis"}</td>`;
          tr.innerHTML += `<td><button class="delete-btn" onclick="deleteData('${res.userid}')">Delete</button></td>`;
          tr.innerHTML += `<td><button class="view-btn" onclick="window.location.href='profile.php?id=${res.userid}'">View Profile</button></td>`;
          tableBody.appendChild(tr);
      });
  }

  // Initial render of the table
  renderTable(responses);

  // Function to filter and search table data
  function filterTable() {
      const searchInput = document.getElementById("searchInput").value.toLowerCase();
      const statusFilter = document.getElementById("statusFilter").value;

      const filteredResponses = responses.filter(res => {
          const matchesUserId = res.userid.toLowerCase().includes(searchInput);
          const matchesStatus = statusFilter ? res.status === statusFilter : true;

          return matchesUserId && matchesStatus;
      });

      renderTable(filteredResponses);
  }

  // Event listener for filter input changes
  document.getElementById("searchInput").addEventListener("input", filterTable);
  document.getElementById("statusFilter").addEventListener("change", filterTable);
});

// Deleting user data
function deleteData(userId) {
    if (confirm(`Are you sure you want to delete data for user ID ${userId}?`)) {
        fetch('Admin/delete_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ userId: userId })
        })
        .then(res => res.text())
        .then(response => {
            alert(response);
            // Optionally, remove the row from the table or re-fetch data
            location.reload(); // Refresh to show updated table
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Failed to delete submission.");
        });
    }
}
