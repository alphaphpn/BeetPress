<?php
    include_once "model/gad/index.php";
    $gadHealthData = new gadHealthData();
    $gadYears = $gadHealthData->getYears();
    $selectedGadYear = isset($_GET['cy']) && in_array((int)$_GET['cy'], array_map('intval', $gadYears), true) ? (int)$_GET['cy'] : (int)($gadYears[0] ?? date('Y'));
    $gadChartData = $gadHealthData->getChartData($selectedGadYear);
?>

<style>
    .chart-container { position: relative; margin: auto; height: 500px; width: 100%; }
</style>

<section class="position-relative bg-light w-100 vh-100 pt-3 pb-5 clearfix">
    <div class="container">
        <div class="w-100 text-center d-flex justify-content-center">
            <div class="text-center mb-3" style="width: fit-content;">
                <h4 class="mb-1 text-dark fw-bold">Gender and Development (GAD)</h4>
                <hr class="y-axis-margin-0-nobile">
            </div>
        </div>

        <div class="w-100 text-center">
            <div class="text-center mb-3">
                <h5 class="mb-1 text-dark fw-bold"><i class="fas fa-hospital-alt text-danger me-2"></i>Health Data</h5>
                <p id="gadYearTitle" class="text-muted small mb-3">Calendar Year <?php echo htmlspecialchars($selectedGadYear); ?> Comparative Breakdown (Male vs. Female)</p>

                <div class="d-flex flex-wrap justify-content-center align-items-center gap-2 mb-3">
                    <label for="gadYearSelector" class="form-label mb-0 fw-semibold text-secondary text-nowrap small">Calendar Year:</label>
                    <select id="gadYearSelector" class="form-select form-select-sm border-primary" style="min-width: 120px;">
                        <?php foreach ($gadYears as $year) { ?>
                            <option value="<?php echo (int)$year; ?>"<?php echo ((int)$year === $selectedGadYear) ? ' selected' : ''; ?>>CY <?php echo htmlspecialchars($year); ?></option>
                        <?php } ?>
                    </select>
                    <label for="datasetSelector" class="form-label mb-0 fw-semibold text-secondary text-nowrap small">Data Set:</label>
                    <select id="datasetSelector" class="form-select form-select-sm border-primary" style="min-width: 250px;">
                        <option value="discharged">Top 10 Discharged Diagnosis</option>
                        <option value="emergency">Top 10 Emergency Cases (ER)</option>
                        <option value="opd">Top 10 Consultations (OPD)</option>
                    </select>
                </div>

                <div class="row"><div class="col-lg-12"><div class="chart-container"><canvas id="gadHospitalChart"></canvas></div></div></div>
                <div class="row"><div class="col-lg-12"><i class="bi bi-info-circle-fill me-1"></i><span id="gadNote">Data values represent total patient admissions and encounters for CY <?php echo htmlspecialchars($selectedGadYear); ?>.</span></div></div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('gadHospitalChart').getContext('2d');
        const datasets = <?php echo json_encode($gadChartData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
        let currentChart;

        function renderChart(type) {
            const chartData = datasets[type];
            if (currentChart) currentChart.destroy();
            currentChart = new Chart(ctx, {
                type: 'bar',
                data: { labels: chartData.labels, datasets: [
                    { label: 'Male Patients', data: chartData.male, backgroundColor: '#ff8266', borderColor: '#ff8266', borderWidth: 1, borderRadius: 4 },
                    { label: 'Female Patients', data: chartData.female, backgroundColor: '#ffa28d', borderColor: '#ffa28d', borderWidth: 1, borderRadius: 4 }
                ] },
                options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'circle', padding: 15 } }, tooltip: { padding: 12, cornerRadius: 6 } }, scales: { x: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' }, title: { display: true, text: 'Number of Cases' } }, y: { grid: { display: false }, ticks: { font: { weight: '500' } } } } }
            });
        }

        renderChart('discharged');
        document.getElementById('datasetSelector').addEventListener('change', function (event) { renderChart(event.target.value); });
        document.getElementById('gadYearSelector').addEventListener('change', function (event) {
            window.location.search = 'cy=' + encodeURIComponent(event.target.value);
        });
    });
</script>

<?php
    include_once "app/views/index/feat-img.php";
    include_once "app/views/index/footer-info.php";
?>
