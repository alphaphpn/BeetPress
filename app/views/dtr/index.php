
	<section class="position-relative primary-bg-color-light w-100 h-100 pt-5 pb-5 clearfix">
		<div class="container">
			<div class="row">

				<div class="col-lg-4">
					<div class="position-relative clearfix">
						<div class="bg-white">
						<?php 
							require_once "dtr.php";
						?>
						</div>
					</div>
				</div>
				
				<div class="col-lg-5">
					<div class="position-relative clearfix"></div>
				</div>

				<div class="col-lg-3">
					<div class="position-relative clearfix">
						<table class="table table-striped" border="2">
							<thead>
								<tr align="center">
									<th colspan="10" class="p-0 align-middle">Attendance Log</th>
								</tr>
								<tr align="center">
									<th colspan="2" class="p-0 font-size-12 align-middle border-end">Day</th>
									<th colspan="8" class="p-0 font-size-12 border-end">Time Log(s)</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php 

	include_once "app/views/index/feat-img.php";
	include_once "app/views/index/footer-info.php";