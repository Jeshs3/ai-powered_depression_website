document.addEventListener("DOMContentLoaded", async () => {
  const trendCtx = document.getElementById("trendChart").getContext("2d");
  const distributionCtx = document.getElementById("distributionChart").getContext("2d");

  // Fetch counts for distribution
  const highRiskData = await fetchStatistics("high_risk");
  const lowRiskData = await fetchStatistics("low_risk");

  const highCount = highRiskData?.high_risk || 0;
  const lowCount = lowRiskData?.low_risk || 0;

  // Fetch monthly trend
  const monthlyTrend = await fetchStatistics("monthly_trend") || {};

  // Current year
  const year = new Date().getFullYear();

  // Month labels
  const labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", 
                  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

  // Map months to YYYY-MM keys
  const highRiskPoints = [];
  const lowRiskPoints = [];

  for (let m = 1; m <= 12; m++) {
    const monthKey = `${year}-${String(m).padStart(2, "0")}`;
    if (monthlyTrend[monthKey]) {
      highRiskPoints.push(monthlyTrend[monthKey].high_risk);
      lowRiskPoints.push(monthlyTrend[monthKey].low_risk);
    } else {
      highRiskPoints.push(0);
      lowRiskPoints.push(0);
    }
  }

  // Trend chart
  const trendChart = new Chart(trendCtx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [
        {
          label: "High Risk",
          data: highRiskPoints,
          borderColor: "#ef4444",
          fill: false
        },
        {
          label: "Low Risk",
          data: lowRiskPoints,
          borderColor: "#10b981",
          fill: false
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 1000,
          ticks: {
            stepSize: 50
          }
        }
      }
    }
  });

  // Distribution chart
  const distributionChart = new Chart(distributionCtx, {
    type: 'doughnut',
    data: {
      labels: ["High Risk", "Low Risk"],
      datasets: [{
        data: [highCount, lowCount],
        backgroundColor: ["#ef4444", "#10b981"]
      }]
    },
    options: { responsive: true }
  });
});
