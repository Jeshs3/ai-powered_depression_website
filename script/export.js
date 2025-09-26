document.getElementById("exportBtn").addEventListener("click", async () => {
    try {
        // Fetch the data from your backend
        const response = await fetch('/BANOL6/database/get_data.php');
        const submissions = await response.json();

        if (!submissions.length) {
            alert("No data available to export.");
            return;
        }

        // Format data for jsPDF autoTable
        const columns = [
            { header: "User ID", dataKey: "userid" },
            { header: "Name", dataKey: "username" },
            { header: "Age", dataKey: "age" },
            { header: "Gender", dataKey: "gender" },
            { header: "Score", dataKey: "total" },
            { header: "Status", dataKey: "status" },
            { header: "Probability", dataKey: "probability" },
            { header: "Submission Date", dataKey: "date" }
        ];

        // Initialize jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.setFontSize(18);
        doc.text("User Submissions Report", 14, 22);

        doc.autoTable({
            startY: 30,
            head: [columns.map(col => col.header)],
            body: submissions.map(sub => columns.map(col => sub[col.dataKey] ?? "-")),
            styles: { fontSize: 10 },
            headStyles: { fillColor: [41, 128, 185], textColor: 255 },
            alternateRowStyles: { fillColor: [245, 245, 245] },
            margin: { top: 10 }
        });

        // Save as PDF
        doc.save("user_submissions_report.pdf");

    } catch (error) {
        console.error("Error exporting data:", error);
        alert("Failed to export data. Please try again.");
    }
});
