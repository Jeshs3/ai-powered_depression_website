// ====================
// Fetch statistics from server
// ====================
async function fetchStatistics(type) {
    try {
        const response = await fetch(`../database/fetch.php?type=${type}`);
        const data = await response.json();
        console.log(`${type} result:`, data);
        return data;
    } catch (error) {
        console.error(`Error fetching statistic for ${type}:`, error);
        return null;
    }
}

// ====================
// Update dashboard cards safely
// ====================
function updateDashboard(data) {
    const totalUsers = document.getElementById("totalUsers");
    const highRisk = document.getElementById("highRisk");
    const lowRisk = document.getElementById("lowRisk");
    const depressionRate = document.getElementById("depressionRate");

    if (totalUsers) totalUsers.textContent = data.totalUsers || 0;
    if (highRisk) highRisk.textContent = data.highRisk || 0;
    if (lowRisk) lowRisk.textContent = data.lowRisk || 0;
    if (depressionRate) depressionRate.textContent = (data.depressionRate ?? 0) + "%";

    const changes = data.changes || {};
    const totalUsersChange = document.getElementById("totalUsersChange");
    const highRiskChange = document.getElementById("highRiskChange");
    const lowRiskChange= document.getElementById("lowRiskChange");
    const depressionRateChange = document.getElementById("depressionRateChange");

    if (totalUsersChange) totalUsersChange.textContent = changes.totalUsers || 0;
    if (highRiskChange) highRiskChange.textContent = changes.highRisk || 0;
    if (lowRiskChange) lowRiskChange.textContent = changes.lowRisk || 0;
    if (depressionRateChange) depressionRateChange.textContent = changes.depressionRate || 0;
}

// ====================
// Update total submissions safely
// ====================
function updateTotalSubmissions(totalResponses) {
    const totalResponsesEl = document.getElementById("totalResponses");
    if (totalResponsesEl) totalResponsesEl.textContent = totalResponses || 0;
}

// ====================
// Load all statistics in parallel
// ====================
async function loadAllStats() {
    // Fetch all statistics at once
    const [
        userCountData,
        highRiskData,
        lowRiskData,
        depressionRateData,
        totalResponsesData
    ] = await Promise.all([
        fetchStatistics("user_count"),
        fetchStatistics("high_risk"),
        fetchStatistics("low_risk"),
        fetchStatistics("depression_rate"),
        fetchStatistics("total_responses")
    ]);

    // Build dashboard object
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

    // Update dashboard and total responses safely
    updateDashboard(dashboardData);
    updateTotalSubmissions(totalResponsesData?.total_responses || 0);
}

// ====================
// Initialize on page load
// ====================
document.addEventListener("DOMContentLoaded", () => {
    loadAllStats();
});
