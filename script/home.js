document.addEventListener("DOMContentLoaded", function () {
    const questionCount = 20;
    let responses = [];
    let currentFilteredResponses = [];
    let currentPage = 1;
    const rowsPerPage = 5;

    const tableHead = document.getElementById('tableHead');
    const tableBody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('paginationControls');

    // Set up event listeners for filtering
    document.getElementById("searchInput").addEventListener("input", filterTable);
    document.getElementById("statusFilter").addEventListener("change", filterTable);

    // Fetch the data
    fetch('/BANOL6/database/get_data.php')
        .then(res => res.json())
        .then(data => {
            responses = data;
            currentFilteredResponses = [...responses];
            renderTable(currentFilteredResponses);
        })
        .catch(error => {
            console.error("Error fetching responses:", error);
            alert("Failed to load submissions.");
        });

    // Set up headers once
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

    const finalHeaders = ["Total Score", "Status", "Probability Score", "Action", "View Profile"];
    finalHeaders.forEach(text => {
        const th = document.createElement('th');
        th.textContent = text;
        headerRow.appendChild(th);
    });

    tableHead.appendChild(headerRow);

    function renderTable(filteredResponses) {
        tableBody.innerHTML = "";
        currentFilteredResponses = filteredResponses;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const paginatedData = filteredResponses.slice(start, end);

        paginatedData.forEach(res => {
            const tr = document.createElement('tr');
            tr.innerHTML += `<td>${res.userid}</td>`;
            tr.innerHTML += `<td>${new Date(res.date).toLocaleDateString()}</td>`;

            const answers = res.answers?.slice(0, questionCount) ?? [];
            for (let i = 0; i < questionCount; i++) {
                const display = answers[i] ?? "-";
                tr.innerHTML += `<td>${display}</td>`;
            }

            tr.innerHTML += `<td>${res.total}</td>`;
            tr.innerHTML += `<td>${res.status}</td>`;
            tr.innerHTML += `<td>${res.probability ?? "Pending Analysis"}</td>`;
            tr.innerHTML += `<td><button class="delete-btn" onclick="deleteData('${res.userid}')">Delete</button></td>`;
            tr.innerHTML += `<td><button class="view-btn" onclick="window.location.href='profile.php?id=${res.userid}'">View Profile</button></td>`;
            tableBody.appendChild(tr);
        });

        pagination();
    }

    function filterTable() {
        currentPage = 1;
        const searchInput = document.getElementById("searchInput")?.value.toLowerCase() ?? "";
        const statusFilter = document.getElementById("statusFilter")?.value;

        const filteredResponses = responses.filter(res => {
            const userId = res.userid?.toLowerCase() ?? "";
            const status = String(res.status).trim(); // Ensure it's a string

            const matchesUserId = userId.includes(searchInput);
            const matchesStatus = statusFilter !== "" ? (status === statusFilter) : true;

            return matchesUserId && matchesStatus;
        });

        renderTable(filteredResponses);
    }



    // Deleting user data
    window.deleteData = function (userId) {
        if (confirm(`Are you sure you want to delete data for user ID ${userId}?`)) {
            fetch('../database/action.php?action=delete_submission', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ userId })
            })
                .then(res => res.text())
                .then(response => {
                    alert(response);
                    // Remove from original and filtered arrays
                    responses = responses.filter(res => res.userid !== userId);
                    currentFilteredResponses = currentFilteredResponses.filter(res => res.userid !== userId);
                    renderTable(currentFilteredResponses);
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert("Failed to delete submission.");
                });
        }
    }

    function pagination() {
        const totalItems = currentFilteredResponses.length;
        const totalPages = Math.ceil(totalItems / rowsPerPage);
        paginationContainer.innerHTML = "";

        if (totalPages <= 1) return;

        const prevBtn = document.createElement("button");
        prevBtn.textContent = "Previous";
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => {
            currentPage--;
            renderTable(currentFilteredResponses);
        };
        paginationContainer.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
            const pageBtn = document.createElement("button");
            pageBtn.textContent = i;
            pageBtn.disabled = i === currentPage;
            pageBtn.onclick = () => {
                currentPage = i;
                renderTable(currentFilteredResponses);
            };
            paginationContainer.appendChild(pageBtn);
        }

        const nextBtn = document.createElement("button");
        nextBtn.textContent = "Next";
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => {
            currentPage++;
            renderTable(currentFilteredResponses);
        };
        paginationContainer.appendChild(nextBtn);
    }
});
