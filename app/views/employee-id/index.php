<?php 

	require_once "lib/session-attendance.php";
	$empidcodeNow = isset($_SESSION["empidcode"]) ? $_SESSION["empidcode"] : null;

	require_once "model/employee/setcurrentemployee.php";

?>

	<script src="<?php echo trim($domainhome); ?>/assets/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.all.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>

	<section class="position-relative primary-bg-color-light w-100 h-100 pt-5 pb-5 clearfix">
		<div class="container">
			<div class="position-relative w-100 m-auto clearfix">
				<div class="row">
					<div class="col-lg-6">
						<div class="card m-auto" style="width: 100%; max-width: 320px;">
							<div class="card-body p-2">
								<?php require_once "sling.php" ?>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card m-auto" style="width: 100%; max-width: 425px;">
							<div class="card-body p-2">
								<?php require_once "pocket-id-front-blank.php" ?>
							</div>
						</div>

						<hr>

						<div class="card m-auto" style="width: 100%; max-width: 425px;">
							<div class="card-body p-2">
								<?php include "pocket-id-front-blank.php" ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script>
		JsBarcode("#barcode", <?php echo '"'.trim($empidcodecc).'"'; ?>, {
			width: 1.5, 
			displayValue: true, 
			fontOptions: 'bold', 
			fontSize: 12, 
			background: 'transparent', 
			height: 12 // Set the desired height in pixels
		});
	</script>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";