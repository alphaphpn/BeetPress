
	<footer class="sticky-bottom w-100 last-bg-color" style="z-index: 1">
		<?php
			if (empty($_SESSION["username"]) || empty($_SESSION["ulevel"]) || empty($_SESSION["uposition"]) || empty($_SESSION["xtown"]) || empty($_SESSION["distno"]) || empty($_SESSION["gcodezip"])) {
		?>
				
		<?php
			} else {
		?>
				<div class="w-100 d-flex flex-column flex-md-row text-right text-md-start justify-content-between">
					<div class="status-bar-font ms-auto bg-secondary text-white px-2">
						<?php 
							echo "ZIPCODE: ".trim($_SESSION["gcodezip"])." | Town: ".trim($_SESSION["xtown"])." | Current User: ".trim($_SESSION["username"])." | Position: ".trim($_SESSION["uposition"]);
						?>
					</div>
				</div>
		<?php
			}
		?>

		<div class="container w-100 d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-1">
			<!-- Copyright -->
			<div class="copyright text-white">Copyright © 2025 eSibugay PH. All rights reserved.</div>
			<!-- Copyright -->

		<?php 
			if ( empty($_SESSION["employeeactivated"]) || $_SESSION["employeeactivated"]==0 ) {

			} else {
				echo '<div class="text-white mobile-font-size-12">
					'.trim($_SESSION["empidcode"]).' | '.trim($_SESSION["empname"]).'
				</div>';
			}
		?>

			<!-- Right -->
			<div>
				<a href="//facebook.com/#!" target="_blank" class="text-white text-decoration-none me-4">
					<i class="fab fa-facebook-f"></i>
				</a>
				<a href="//twitter.com/#!" target="_blank" class="text-white text-decoration-none me-4">
					<i class="fab fa-twitter"></i>
				</a>
				<a href="mailto:info@sibugay.gov.ph" target="_blank" class="text-white text-decoration-none me-4">
					<i class="fab fa-google"></i>
				</a>
				<a href="//linkedin.com/#!" target="_blank" class="text-white text-decoration-none">
					<i class="fab fa-linkedin-in"></i>
				</a>
			</div>
			<!-- Right -->
		</div>
	</footer>

	<button onclick="topFunction()" id="scroll-to-top" title="Go to top"><i class="fas fa-chevron-up"></i></button>

	<script src="<?php echo trim($domainhome); ?>/assets/js/script.js"></script>
	<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	
</body>
</html>