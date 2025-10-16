<?php 

	require_once "lib/session-attendance.php";

?>

	<script src="<?php echo trim($domainhome); ?>/assets/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.all.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>
	
	<table style="width: 100%; width: -webkit-fill-available;">
		<tbody>
			<tr>
				<?php 
					$empidcodeNow = isset($_GET["empid1"]) ? $_GET["empid1"] : null;
					include_once "model/employee/setcurrentemployee.php"; 
					$createdatcc1 = $createdatcc;
					$empidcodecc1 = $empidcodeNow;
					$nicknamecc1 = $nicknamecc;
					$empnameforidcc1 = $empnameforidcc;
					$designationforidcc1 = $designationforidcc;
					$officenameforidcc1 = $officenameforidcc;
					$mphonecc1 = $mphonecc;
				?>
				<td style="padding-right: 6px; padding-bottom: 6px;"><?php include "app/views/employee-id/sling1.php" ?></td>
				<td style="padding-left: 6px; padding-bottom: 6px;"><?php include "app/views/employee-id/sling2.php" ?></td>
			</tr>

			<tr>
				<?php 
					$empidcodeNow = isset($_GET["empid2"]) ? $_GET["empid2"] : null;
					include "model/employee/setcurrentemployee.php"; 
					$createdatcc2 = $createdatcc;
					$empidcodecc2 = $empidcodeNow;
					$nicknamecc2 = $nicknamecc;
					$empnameforidcc2 = $empnameforidcc;
					$designationforidcc2 = $designationforidcc;
					$officenameforidcc2 = $officenameforidcc;
					$mphonecc2 = $mphonecc;
				?>
				<td style="padding-right: 6px; padding-top: 6px;"><?php include "app/views/employee-id/sling3.php" ?></td>
				<td style="padding-left: 6px; padding-top: 6px;"><?php include "app/views/employee-id/sling4.php" ?></td>
			</tr>
		</tbody>
	</table>

	<script>
		JsBarcode("#barcode1", <?php echo '"'.trim($empidcodecc1).'"'; ?>, {
			width: 1.5, 
			displayValue: true, 
			fontOptions: 'bold', 
			fontSize: 12, 
			background: 'transparent', 
			height: 12 // Set the desired height in pixels
		});

		JsBarcode("#barcode2", <?php echo '"'.trim($empidcodecc1).'"'; ?>, {
			width: 1.5, 
			displayValue: true, 
			fontOptions: 'bold', 
			fontSize: 12, 
			background: 'transparent', 
			height: 12 // Set the desired height in pixels
		});

		JsBarcode("#barcode3", <?php echo '"'.trim($empidcodecc2).'"'; ?>, {
			width: 1.5, 
			displayValue: true, 
			fontOptions: 'bold', 
			fontSize: 12, 
			background: 'transparent', 
			height: 12 // Set the desired height in pixels
		});

		JsBarcode("#barcode4", <?php echo '"'.trim($empidcodecc2).'"'; ?>, {
			width: 1.5, 
			displayValue: true, 
			fontOptions: 'bold', 
			fontSize: 12, 
			background: 'transparent', 
			height: 12 // Set the desired height in pixels
		});
	</script>

	<script>
		$(document).ready(function(){
			window.print();
		});
	</script>