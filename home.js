document.addEventListener("DOMContentLoaded", function () {
  const questionCount = 20;

  let responses = [];

    fetch('get_data.php') // adjust path if needed
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

  for (let i = 1; i <= questionCount; i++) {
      const th = document.createElement('th');
      th.textContent = `Q${i}`;
      headerRow.appendChild(th);
  }

  const finalHeaders = ["Total Score", "Status", "Action"];
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
          tr.innerHTML += `<td>${res.userId}</td>`;
          tr.innerHTML += `<td>${res.date}</td>`;

          res.answers.forEach(ans => {
              tr.innerHTML += `<td>${ans}</td>`;
          });

          tr.innerHTML += `<td>${res.total}</td>`;
          tr.innerHTML += `<td><span class="status ${res.status === 1 ? 'alert' : 'ok'}">
              ${res.status === 1 ? 'Depressed' : 'Not Depressed'}
          </span></td>`;
          tr.innerHTML += `<td><button class="delete-btn" onclick="deleteData('${res.userId}')">Delete</button></td>`;

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
          const matchesUserId = res.userId.toLowerCase().includes(searchInput);
          const matchesStatus = statusFilter ? res.status.toString() === statusFilter : true;

          return matchesUserId && matchesStatus;
      });

      renderTable(filteredResponses);
  }
});

//DELETING USER DATA ONLY
function deleteData(userId) {
    if (confirm(`Are you sure you want to delete data for ${userId}?`)) {
      fetch('../Admin/delete_data.php', {
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
        location.reload(); // Simple way to update table
      })
      .catch(err => {
        console.error("Error:", err);
        alert("Failed to delete submission.");
      });
    }
  }

