<?php 

	// require_once "lib/session-attendance.php";

?>

	<script src="<?php echo trim($domainhome); ?>/assets/npm/jsbarcode@3.12.1/dist/barcodes/JsBarcode.all.min.js"></script>
	<script src="<?php echo trim($domainhome); ?>/assets/gh/davidshimjs/qrcodejs@gh-pages/qrcode.min.js"></script>

	<style>
		/* Reset margins to utilize the full page area */
		html, body {
			margin: 0;
			padding: 0;
			height: 100%;
			font-family: Arial, sans-serif;
			box-sizing: border-box;
			/* Forces background colors and images to print */
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}

		/* Table spans the full width and full height of the viewport/page */
		table {
			width: 100%;
			border-collapse: collapse;
			margin: 0 auto;
			max-width: 700px;
		}
		
		tr {
			height: 20vh; /* Divides the screen viewport exactly into 5 rows */
		}

		td {
			border: 1px dashed #999; /* Dashed for easy cutting reference */
			text-align: center;
			vertical-align: middle;
			width: 50%;
			padding: 5px;
		}

		/* THE ID CARD WRAPPER 
		   Locks the exact proportions of a standard ID so the background NEVER stretches.
		*/
		.id-card {
			position: relative;
			width: 100%;
			max-width: 400px; /* Limits size on large screens */
			aspect-ratio: 85.6 / 54; /* Standard CR80 ID aspect ratio */
			margin: 0 auto;
			background-size: 100% 100%; 
			background-position: center;
			background-repeat: no-repeat;
			overflow: hidden;
			text-align: left; 
		}

		/* Classes to assign the specific background images */
		.front-bg {
			background-image: url('assets/media/pocket-front.png');
		}

		.back-bg {
			background-image: url('assets/media/pocket-back.png');
		}

		/* =========================================
		   FRONT ID CSS STYLES
		   ========================================= */
		/* Passport Photo and Signature Containers */
		.f-photo { position: absolute; top: 31.5%; left: 4.5%; width: 23%; height: 42%; background-color: #fff; background-size: cover; }
		.f-photo img { width: 100%; height: 100%; object-fit: cover; } 
		
		.f-sig { position: absolute; bottom: 28px; left: 13px; width: 100%; height: 24px; max-width: 79px; background-color: #fff; border: 1px solid #fa8956; border-radius: 4px; }
		.f-sig img { width: 100%; height: 16px; object-fit: contain; margin-top: -4px; } 
		
		/* Ann K. Hofer Signature Container - Removed white background */
		.f-gov-sig { position: absolute; bottom: 24px; right: 40px; width: 64px; height: auto; }
		.f-gov-sig img { width: 100%; height: 42px; object-fit: contain; max-width:66px; text-align: end;}
		
		/* ADJUST HEIGHT HERE: Lower percentage = higher up on the card */
		.f-emp { position: absolute; top: 63px; right: 13px; font-family: monospace; font-size: 8px; font-weight: bold; letter-spacing: 0.5px; white-space: nowrap; }
		.f-barcode { position: absolute; top: 64px; right: 14px; width: 38%; height: 8%; }
		.f-pos { position: absolute; top: 86px; right: 12px; width: 220px; text-align: end; font-family: monospace; font-size: 8px; font-weight: bold; letter-spacing: 0.5px; white-space: nowrap; color: #000; }
		
		.f-names { position: absolute; top: 98px; left: 104px; }
		.val-main { font-size: 16px; font-weight: 900; line-height: 1; white-space: nowrap; font-family: monospace;}
		.lbl { font-size: 7px; font-weight: normal; margin-bottom: 0px; color: #333; white-space: nowrap; }

		/* =========================================
		   BACK ID CSS STYLES
		   ========================================= */
		.b-top-left { position: absolute; top: 4%; left: 4%; }
		.val-b { font-size: 10px; font-weight: bold; line-height: 1.2; margin-top: 3px; color: #000; white-space: nowrap; }
		
		/* 1x1 Square QR Code */
		.b-qr { position: absolute; top: 4%; right: 4%; width: 20%; aspect-ratio: 1 / 1; background-color: #fff; }
		.b-qr img { width: 100%; height: 100%; object-fit: contain; }
		
		.b-grid { position: absolute; top: 85px; left: 14px; width: 92%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0px 2px; }
		.b-grid .val-b { font-size: 8px; white-space: nowrap; }
		
		.b-emergency { position: absolute; bottom: 9px; right: 15px; text-align: center; font-size: 7px; font-weight: bold; line-height: 1.4; color: #000; white-space: nowrap; width: 100%; max-width: 136px; }
		.b-emergency span { font-weight: normal; }

		/* Print-specific CSS to strictly fit 5x2 on any paper size without layout compression */
		@media print {
			@page {
				margin: 10mm; /* Clean standard margin for all printers */
			}
			html, body {
				height: 100%;
				margin: 0;
				padding: 0;
				overflow: hidden; /* Prevents an extra blank page */
			}
			table {
				width: 100%;
				height: 100vh; /* Locks table to exactly one page height */
				table-layout: fixed; /* Ensures exactly even column distribution */
				page-break-inside: avoid;
			}
			tr {
				height: 20vh !important; /* Divides height evenly for exactly 5 rows */
				page-break-inside: avoid;
				page-break-after: avoid;
			}
			td {
				height: 20vh;
				border: 1px dashed #999; 
				padding: 5px;
				box-sizing: border-box;
				overflow: hidden; 
			}
			.id-card {
				/* Dynamically calculates max possible width to fill column OR height, whichever avoids squishing */
				width: min(100%, calc((20vh - 12px) * (85 / 54))) !important;
				height: auto !important;
				max-width: none !important; /* Overrides screen max-width */
				aspect-ratio: 85.6 / 54 !important; /* Strictly locks proportions */
				margin: 0 auto;
			}
		}
	</style>

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

	<table>
	<?php
		include_once "pocket-id1.php";

		include_once "pocket-id2.php";

		include_once "pocket-id3.php";

		include_once "pocket-id4.php";

		include_once "pocket-id5.php";
	?>
	</table>