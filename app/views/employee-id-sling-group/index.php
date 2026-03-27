<?php 

	require_once "lib/session-attendance.php";

?>

	<script src="<?php echo trim($domainhome); ?>/assets/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.all.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>

	<style>
		/* --- Default Screen View (175% Zoom) --- */
		/* Apply this ONLY when viewed on a screen */
		@media screen {
			html {
				/* Standard CSS Scaling (more modern and preferred) */
				transform: scale(.95);
				transform-origin: 0 0; /* Scale from the top-left corner */
				
				/* Non-standard zoom property for browser compatibility (e.g., IE/Edge) */
				zoom: 175%; 
			}
		}

		/* --- Print View (100% Zoom) --- */
		/* Apply this ONLY when printing */
		@media print {
			html {
				/* Reset any scaling or zoom to ensure it prints at actual size */
				transform: scale(1.0);
				zoom: 100%;
			}

			.hidebuttonprint { display: none; }
		}

		@media screen and (max-width: 767px) {
			table#theidempleyy tbody tr td.myselectd {
				display: block;
			}
		}
	</style>
	
	<table id="theidempleyy" style="width: 100%; width: -webkit-fill-available;">
		<tbody>
			<tr class="hidebuttonprint">
				<td colspan="2" align="center"><button id="openModalBtn-empA" type="button" style="font-size: xx-small;">Adjust</button></td>
			</tr>
			
			<tr>
				<?php 
					$empidcodeNow = isset($_GET["empid1"]) ? $_GET["empid1"] : null;
					include_once "model/employee/setcurrentemployee.php"; 
					$createdatcc1 = $createdatcc;
					$empidcodecc1 = $empidcodeNow;
					$nicknamecc1 = $nicknamecc;
					$empnameforidcc1 = $empnameforidcc;
					$birthdaycc1 = $birthdaycc;
					$designationforidcc1 = $designationforidcc;
					$officenameforidcc1 = $officenameforidcc;
					$mphonecc1 = $mphonecc;
				?>
				<td class="myselectd" style="padding-right: 6px; padding-bottom: 30px;"><?php include "app/views/employee-id/sling1.php" ?></td>
				<td class="myselectd" style="padding-left: 6px; padding-bottom: 30px; border-left: 1px solid black;"><?php include "app/views/employee-id/sling2.php" ?></td>
			</tr>

			<tr class="hidebuttonprint">
				<td colspan="2" align="center"><button id="openModalBtn-empB" type="button" style="font-size: xx-small;">Adjust</button></td>
			</tr>

			<tr>
				<?php 
					$empidcodeNow = isset($_GET["empid2"]) ? $_GET["empid2"] : null;
					include "model/employee/setcurrentemployee.php"; 
					$createdatcc2 = $createdatcc;
					$empidcodecc2 = $empidcodeNow;
					$nicknamecc2 = $nicknamecc;
					$empnameforidcc2 = $empnameforidcc;
					$birthdaycc2 = $birthdaycc;
					$designationforidcc2 = $designationforidcc;
					$officenameforidcc2 = $officenameforidcc;
					$mphonecc2 = $mphonecc;
				?>
				<td class="myselectd" style="padding-right: 6px; padding-top: 6px; border-top: 1px solid black;"><?php include "app/views/employee-id/sling3.php" ?></td>
				<td class="myselectd" style="padding-left: 6px; padding-top: 6px; border-top: 1px solid black; border-left: 1px solid black;"><?php include "app/views/employee-id/sling4.php" ?></td>
			</tr>
		</tbody>
	</table>

	<dialog id="myModal-empA" style="text-align: center;">
		<h3>Employee <?php echo trim($nicknamecc1); ?></h3>
		<p>Adjust Photo/image.</p>
		<form>
			<div>
				<label for="filter-empA">Brightness</label>
				<input type="range" id="filter-empA" name="points" min="0" max="200" onchange="ChangeFilterEmpA()" />
			</div>

			<div>
				<label for="contrast-empA">Contrast</label>
				<input type="range" id="contrast-empA" name="points" min="0" max="200" onchange="ChangeContrastEmpA()" />
			</div>

			<div>
				<label for="blur-empA">Blur</label>
				<input type="number" id="blur-empA" name="points" min="0.01" max="10" value="0.5" onchange="ChangeBlurEmpA()" />
			</div>

			<div>
				<label for="saturate-empA">Saturate</label>
				<input type="range" id="saturate-empA" name="points" min="0" max="200" onchange="ChangeSaturateEmpA()" />
			</div>
		</form>
		<button id="closeModalBtn-empA">Close Modal</button>
	</dialog>

	<dialog id="myModal-empB" style="text-align: center;">
		<h3>Employee <?php echo trim($nicknamecc2); ?></h3>
		<p>Adjust Photo/image.</p>
		<form>
			<div>
				<label for="filter-empB">Brightness</label>
				<input type="range" id="filter-empB" name="points" min="0" max="200" onchange="ChangeFilterEmpB()" />
			</div>

			<div>
				<label for="contrast-empB">Contrast</label>
				<input type="range" id="contrast-empB" name="points" min="0" max="200" onchange="ChangeContrastEmpB()" />
			</div>

			<div>
				<label for="blur-empB">Blur</label>
				<input type="number" id="blur-empB" name="points" min="0.01" max="10" value="0.5" onchange="ChangeBlurEmpB()" />
			</div>

			<div>
				<label for="saturate-empB">Saturate</label>
				<input type="range" id="saturate-empB" name="points" min="0" max="200" onchange="ChangeSaturateEmpB()" />
			</div>
		</form>
		<button id="closeModalBtn-empB">Close Modal</button>
	</dialog>

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

		const openModalBtn_empA = document.getElementById('openModalBtn-empA');
		const closeModalBtn_empA = document.getElementById('closeModalBtn-empA');
		const myModal_empA = document.getElementById('myModal-empA');

		openModalBtn_empA.addEventListener('click', () => {
			myModal_empA.showModal(); // Displays the modal as a modal dialog
		});

		closeModalBtn_empA.addEventListener('click', () => {
			myModal_empA.close(); // Closes the modal
		});

		function ChangeFilterEmpA(event) {
			ImageA = document.getElementById("idempl-a");
			ImageB = document.getElementById("idempl-b");
			FilterA = document.getElementById("filter-empA").value;
			ContrastA = document.getElementById("contrast-empA").value;
			BlurA = document.getElementById("blur-empA").value;
			SaturateA = document.getElementById("saturate-empA").value;

			const combinedFilter = "brightness(" + FilterA + "%) " + "contrast(" + ContrastA + "%) " + "blur(" + BlurA + "px) " + "saturate(" + SaturateA + "%)";

			ImageA.style.WebkitFilter = combinedFilter;
			ImageB.style.WebkitFilter = combinedFilter;
		}

		function ChangeContrastEmpA(event) {
			ImageA = document.getElementById("idempl-a");
			ImageB = document.getElementById("idempl-b");
			FilterA = document.getElementById("filter-empA").value;
			ContrastA = document.getElementById("contrast-empA").value;
			BlurA = document.getElementById("blur-empA").value;
			SaturateA = document.getElementById("saturate-empA").value;

			const combinedFilter = "brightness(" + FilterA + "%) " + "contrast(" + ContrastA + "%) " + "blur(" + BlurA + "px) " + "saturate(" + SaturateA + "%)";

			ImageA.style.WebkitFilter = combinedFilter;
			ImageB.style.WebkitFilter = combinedFilter;
		}

		function ChangeBlurEmpA(event) {
			ImageA = document.getElementById("idempl-a");
			ImageB = document.getElementById("idempl-b");
			FilterA = document.getElementById("filter-empA").value;
			ContrastA = document.getElementById("contrast-empA").value;
			BlurA = document.getElementById("blur-empA").value;
			SaturateA = document.getElementById("saturate-empA").value;

			const combinedFilter = "brightness(" + FilterA + "%) " + "contrast(" + ContrastA + "%) " + "blur(" + BlurA + "px) " + "saturate(" + SaturateA + "%)";

			ImageA.style.WebkitFilter = combinedFilter;
			ImageB.style.WebkitFilter = combinedFilter;
		}

		function ChangeSaturateEmpA(event) {
			ImageA = document.getElementById("idempl-a");
			ImageB = document.getElementById("idempl-b");
			FilterA = document.getElementById("filter-empA").value;
			ContrastA = document.getElementById("contrast-empA").value;
			BlurA = document.getElementById("blur-empA").value;
			SaturateA = document.getElementById("saturate-empA").value;

			const combinedFilter = "brightness(" + FilterA + "%) " + "contrast(" + ContrastA + "%) " + "blur(" + BlurA + "px) " + "saturate(" + SaturateA + "%)";

			ImageA.style.WebkitFilter = combinedFilter;
			ImageB.style.WebkitFilter = combinedFilter;
		}

		const openModalBtn_empB = document.getElementById('openModalBtn-empB');
		const closeModalBtn_empB = document.getElementById('closeModalBtn-empB');
		const myModal_empB = document.getElementById('myModal-empB');

		openModalBtn_empB.addEventListener('click', () => {
			myModal_empB.showModal(); // Displays the modal as a modal dialog
		});

		closeModalBtn_empB.addEventListener('click', () => {
			myModal_empB.close(); // Closes the modal
		});

		function ChangeFilterEmpB(event) {
			ImageC = document.getElementById("idempl-c");
			ImageD = document.getElementById("idempl-d");
			FilterB = document.getElementById("filter-empB").value;
			ContrastB = document.getElementById("contrast-empB").value;
			BlurB = document.getElementById("blur-empB").value;
			SaturateB = document.getElementById("saturate-empB").value;

			const combinedFilterb = "brightness(" + FilterB + "%) " + "contrast(" + ContrastB + "%) " + "blur(" + BlurB + "px) " + "saturate(" + SaturateB + "%)";

			ImageC.style.WebkitFilter = combinedFilterb;
			ImageD.style.WebkitFilter = combinedFilterb;
		}

		function ChangeContrastEmpB(event) {
			ImageC = document.getElementById("idempl-c");
			ImageD = document.getElementById("idempl-d");
			FilterB = document.getElementById("filter-empB").value;
			ContrastB = document.getElementById("contrast-empB").value;
			BlurB = document.getElementById("blur-empB").value;
			SaturateB = document.getElementById("saturate-empB").value;

			const combinedFilterb = "brightness(" + FilterB + "%) " + "contrast(" + ContrastB + "%) " + "blur(" + BlurB + "px) " + "saturate(" + SaturateB + "%)";

			ImageC.style.WebkitFilter = combinedFilterb;
			ImageD.style.WebkitFilter = combinedFilterb;
		}

		function ChangeBlurEmpB(event) {
			ImageC = document.getElementById("idempl-c");
			ImageD = document.getElementById("idempl-d");
			FilterB = document.getElementById("filter-empB").value;
			ContrastB = document.getElementById("contrast-empB").value;
			BlurB = document.getElementById("blur-empB").value;
			SaturateB = document.getElementById("saturate-empB").value;

			const combinedFilterb = "brightness(" + FilterB + "%) " + "contrast(" + ContrastB + "%) " + "blur(" + BlurB + "px) " + "saturate(" + SaturateB + "%)";

			ImageC.style.WebkitFilter = combinedFilterb;
			ImageD.style.WebkitFilter = combinedFilterb;
		}

		function ChangeSaturateEmpB(event) {
			ImageC = document.getElementById("idempl-c");
			ImageD = document.getElementById("idempl-d");
			FilterB = document.getElementById("filter-empB").value;
			ContrastB = document.getElementById("contrast-empB").value;
			BlurB = document.getElementById("blur-empB").value;
			SaturateB = document.getElementById("saturate-empB").value;

			const combinedFilterb = "brightness(" + FilterB + "%) " + "contrast(" + ContrastB + "%) " + "blur(" + BlurB + "px) " + "saturate(" + SaturateB + "%)";

			ImageC.style.WebkitFilter = combinedFilterb;
			ImageD.style.WebkitFilter = combinedFilterb;
		}
	</script>