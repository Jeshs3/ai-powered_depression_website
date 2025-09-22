<div class="container-fluid mt-4">
  <div class="row g-4">
    <!-- Chart 1 -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold">Depression Risk Over Time</span>
          <select id="chartMetric" class="form-select form-select-sm w-auto">
            <option value="count">User Count</option>
            <option value="percentage">Percentage</option>
          </select>
        </div>
        <div class="card-body">
          <canvas id="trendChart" height="250"></canvas>
        </div>
      </div>
    </div>

    <!-- Chart 2 -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-header">
          <span class="fw-semibold">Risk Distribution</span>
        </div>
        <div class="card-body">
          <canvas id="distributionChart" height="250"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../script/graph.js"></script>
