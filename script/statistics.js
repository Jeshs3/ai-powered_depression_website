// fetch users
async function fetchStatistics(type) {
    try {
        const response = await fetch(`../database/fetch.php?type=${type}`);
        const data = await response.json();
        console.log(`${type} result:`, data);
        return data;
    } catch (error) {
        console.error("Error fetching statistic:", error);
        return null;
    }
}


// Update dashboard cards
function updateDashboard(data) {
  document.getElementById("totalUsers").textContent = data.totalUsers;
  document.getElementById("highRisk").textContent = data.highRisk;
  document.getElementById("lowRisk").textContent = data.lowRisk;
  document.getElementById("depressionRate").textContent = data.depressionRate + "%";

  // update changes directly
  document.getElementById("totalUsersChange").textContent = data.changes.totalUsers;
  document.getElementById("highRiskChange").textContent = data.changes.highRisk;
  document.getElementById("lowRiskChange").textContent = data.changes.lowRisk;
  document.getElementById("depressionRateChange").textContent = data.changes.depressionRate;
}


//load statistics from server
// Use fetchStatistic to get total users, then update dashboard
async function loadStats() {
  // fetch total users
  const userCountData = await fetchStatistics("user_count");
  const highRiskData = await fetchStatistics("high_risk");
  const lowRiskData = await fetchStatistics("low_risk");
  const depressionRateData = await fetchStatistics("depression_rate");

  // Build dashboard data object
  const dashboardData = {
      totalUsers: userCountData?.total_users || 0,
      highRisk: highRiskData?.high_risk || 0,
      lowRisk: lowRiskData?.low_risk || 0,
      depressionRate: depressionRateData?.depression_rate || 0,
      changes: {
          totalUsers: 0,
          highRisk: 0,
          lowRisk: 0,
          depressionRate: 0
      }
  };


   console.log("dashboardData:", dashboardData); 
  // update the dashboard
  updateDashboard(dashboardData);
}

// call when page loads
loadStats();
