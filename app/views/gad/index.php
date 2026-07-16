
	<style>
		.chart-container {
			position: relative;
			margin: auto;
			height: 500px;
			width: 100%;
		}
	</style>
	
	<section class="position-relative bg-light w-100 vh-100 pt-3 pb-5 clearfix">
		<div class="container">
			<div class="w-100 text-center d-flex justify-content-center">
				<div class="text-center mb-3" style="width: fit-content;">
					<h4 class="mb-1 text-dark fw-bold">
						Gender and Development (GAD)
					</h4>
					<hr class="y-axis-margin-0-nobile">
				</div>
			</div>

			<div class="w-100 text-center">
				<div class="text-center mb-3">
					<h5 class="mb-1 text-dark fw-bold">
						<i class="fas fa-hospital-alt text-danger me-2"></i>Health Data
					</h5>

					<p class="text-muted small mb-0">Calendar Year 2024 Comparative Breakdown (Male vs. Female)</p>

					<div class="d-flex align-items-center gap-2">
						<label for="datasetSelector" class="form-label mb-0 fw-semibold text-secondary text-nowrap small">Select Department:</label>
						<select id="datasetSelector" class="form-select form-select-sm border-primary" style="min-width: 250px;">
							<option value="discharged">Top 10 Discharged Diagnosis</option>
							<option value="emergency">Top 10 Emergency Cases (ER)</option>
							<option value="opd">Top 10 Consultations (OPD)</option>
						</select>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<div class="chart-container">
								<canvas id="gadHospitalChart"></canvas>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-12">
							<i class="bi bi-info-circle-fill me-1"></i> Data values represent total patient admissions and encounters for CY 2024.
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

<script>
	document.addEventListener("DOMContentLoaded", function () {
		const ctx = document.getElementById('gadHospitalChart').getContext('2d');
		let currentChart;

		// Structured Datasets mapped directly from your image (Excluding "Others")
		const datasets = {
			discharged: {
				labels: ['Pneumonia', 'Acute Gastroenteritis', 'UTI', 'Acute Gastritis', 'Dengue Fever', 'Infectious Diarrhea', 'Peptic Ulcer', 'Hypertension (1,2,3)', 'Acute Bronchitis', 'Allergic Bronchitis'],
				male: [618, 290, 234, 148, 160, 89, 45, 39, 24, 9],
				female: [531, 304, 342, 213, 156, 90, 70, 57, 32, 9]
			},
			emergency: {
				labels: ['Pneumonia', 'Acute Gastroenteritis', 'UTI', 'Acute Gastritis', 'Work Related Injury', 'Vehicular Accident', 'Dengue Fever', 'Hypertension (1,2,3)', 'Acute Bronchitis', 'Circumcision'],
				male: [453, 375, 330, 295, 300, 205, 138, 85, 83, 62],
				female: [460, 374, 333, 281, 149, 169, 134, 80, 82, 0]
			},
			opd: {
				labels: ['Pneumonia', 'Animal Bite', 'UTI', 'Work Related Injury', 'Hypertension (1,2,3)', 'Acute Gastritis', 'Acute Bronchitis', 'Acute Gastroenteritis', 'Cellulitis', 'Diabetes Mellitus'],
				male: [480, 460, 410, 305, 135, 130, 73, 68, 73, 25],
				female: [474, 453, 398, 188, 118, 108, 71, 65, 71, 24]
			}
		};

		// Function to build/update the bar chart
		function renderChart(type) {
			const chartData = datasets[type];

			// If a chart instance already exists, destroy it before making a new one
			if (currentChart) {
				currentChart.destroy();
			}

			currentChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: chartData.labels,
					datasets: [
						{
							label: 'Male Patients',
							data: chartData.male,
							backgroundColor: '#ff8266', // Clean Medical Blue
							borderColor: '#ff8266',
							borderWidth: 1,
							borderRadius: 4
						},
						{
							label: 'Female Patients',
							data: chartData.female,
							backgroundColor: '#ffa28d', // Clean Soft Magenta/Pink
							borderColor: '#ffa28d',
							borderWidth: 1,
							borderRadius: 4
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					indexAxis: 'y', // Horizontal bars make long medical category names much easier to read!
					plugins: {
						legend: {
							position: 'top',
							labels: {
								usePointStyle: true,
								pointStyle: 'circle',
								padding: 15
							}
						},
						tooltip: {
							padding: 12,
							cornerRadius: 6
						}
					},
					scales: {
						x: {
							beginAtZero: true,
							grid: { color: 'rgba(0, 0, 0, 0.05)' },
							title: {
								display: true,
								text: 'Number of Cases'
							}
						},
						y: {
							grid: { display: false },
							ticks: {
								font: { weight: '500' }
							}
						}
					}
				}
			});
		}

		// Initialize with 'Discharged Diagnosis' on first page load
		renderChart('discharged');

		// Dropdown switch event listener
		document.getElementById('datasetSelector').addEventListener('change', function(e) {
			renderChart(e.target.value);
		});
	});
</script>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";

?>