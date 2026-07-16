
	<?php
	// Logic to handle the file upload
	$message = "";
	$messageClass = "";

	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['attendance_file'])) {
		$targetDir = "uploads/";
		
		// Create directory if it doesn't exist
		if (!file_exists($targetDir)) {
			mkdir($targetDir, 0777, true);
		}

		$fileName = basename($_FILES["attendance_file"]["name"]);
		$targetFilePath = $targetDir . $fileName;
		$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

		// Allow certain file formats (e.g., csv, txt, xlsx)
		$allowTypes = array('dat', 'csv', 'txt', 'xlsx', 'xls');
		
		if (in_array($fileType, $allowTypes)) {
			if (move_uploaded_file($_FILES["attendance_file"]["tmp_name"], $targetFilePath)) {
				$message = "The file <b>" . $fileName . "</b> has been uploaded successfully.";
				$messageClass = "alert-success";
			} else {
				$message = "Sorry, there was an error uploading your file.";
				$messageClass = "alert-danger";
			}
		} else {
			$message = "Invalid file type. Please upload CSV, TXT, or XLSX.";
			$messageClass = "alert-warning";
		}
	}
	?>

	<style>
		/* Ensures the container takes up the full height of the viewport */
		body, html {
			height: 100%;
		}
	</style>
	
	<div class="container-fluid d-flex justify-content-center align-items-center vh-75">
		<div class="pt-3">
			<div class="card shadow-sm" style="width: 100%; max-width: 450px;">
				<div class="card-header bg-primary text-white text-center py-3">
					<h5 class="mb-0">Attendance Log Upload</h5>
				</div>
				<div class="card-body p-4">
					
					<?php if (!empty($message)): ?>
						<div class="alert <?php echo $messageClass; ?> alert-dismissible fade show" role="alert">
							<?php echo $message; ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					<?php endif; ?>

					<form action="" method="post" enctype="multipart/form-data">
						<div class="mb-4">
							<label for="attendance_file" class="form-label text-muted">Select Attendance File (CSV, TXT)</label>
							<input class="form-control" type="file" name="attendance_file" id="attendance_file" required>
						</div>
						<div class="d-grid">
							<button type="submit" name="submit" class="btn btn-primary btn-lg">
								Upload File
							</button>
						</div>
					</form>
				</div>
				<div class="card-footer text-center text-muted small py-3">
					Accepted formats: .csv, .txt, .xlsx
				</div>
			</div>
		</div>
	</div>