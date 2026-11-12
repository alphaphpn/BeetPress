<?php

	$id = isset($_GET["pid"]) ? trim((string) $_GET["pid"]) : '';
	$safeId = basename($id);
	$filePath = 'public/ore/transport/permit/' . $safeId . '.pdf';
	$fileExists = $safeId !== '' && is_file(dirname(__DIR__, 3) . '/' . $filePath);
	
?>

	<section class="position-relative bg-light w-100 vh-100 pt-3 pb-5 clearfix">
		<div class="container my-4">
			<?php if ($fileExists): ?>
				<div class="ratio ratio-4x3 border rounded shadow-sm">
					<iframe 
						src="<?php echo htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8'); ?>" 
						title="PDF Document" 
						allowfullscreen>
					</iframe>
				</div>
			<?php else: ?>
				<div class="alert alert-warning">Permit file not found.</div>
			<?php endif; ?>
		</div>
	</section>

<?php
    include_once "app/views/index/feat-img.php";
    include_once "app/views/index/footer-info.php";
?>