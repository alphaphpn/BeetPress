
				</div>
			</main>
			<footer class="position-fixed w-webkit-fill-available bottom-0 py-3 bg-light mt-auto">
				<div class="container-fluid px-4">
					<div id="trnsfrPaginate" class="dataTables_wrapper d-flex justify-content-between"></div>
				</div>
			</footer>
		</div>
	</div>

	<script>
		$(document).ready( function () {
			var appointmentStatusFilterOnly = $('#listRecView').data('status-filter-only') === true;
			var noTableFilters = $('#listRecView').data('no-filters') === true;
			$('#listRecView').DataTable( {
				searching: !appointmentStatusFilterOnly && !noTableFilters,
				initComplete: function () {
					var tableApi = this.api();
					var defaultDutyStatus = tableApi.table().node().dataset.defaultDutyStatus;
					var statusFilterOnly = tableApi.table().node().dataset.statusFilterOnly === 'true';
					var noFilters = tableApi.table().node().dataset.noFilters === 'true';
					tableApi.columns().every( function () {

						/** Filter Group for each column Start **/
						var column = this;
						if (noFilters) return;
						if (statusFilterOnly && column.index() !== 5) return;
						var select = $('<select><option value=""></option></select>')
						.appendTo( $(column.header()).empty() )
						.on( 'change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
							$(this).val()
						);

						column
							.search( val ? '^'+val+'$' : '', true, false )
							.draw();
						});

						if (statusFilterOnly && column.index() === 5) {
							select.append('<option value="Pending">Pending</option>');
							select.append('<option value="Approved">Approved</option>');
							select.append('<option value="Cancel">Cancel</option>');
						} else if (defaultDutyStatus && column.index() === 3) {
							select.append('<option value="On-Duty">ON-Duty</option>');
							select.append('<option value="Off-Duty">OFF-Duty</option>');
						} else {
							column.data().unique().sort().each( function ( d, j ) {
								select.append( '<option value="'+d+'">'+d+'</option>' )
							});
						}
						/** Filter Group for each column End **/
					});

					if (defaultDutyStatus) {
						var dutyColumn = tableApi.column(3);
						$(dutyColumn.header()).find('select').val(defaultDutyStatus);
						dutyColumn.search('^' + $.fn.dataTable.util.escapeRegex(defaultDutyStatus) + '$', true, false).draw();
					}
				}, 
				lengthMenu: [
					[5, 10, 25, 50, 100, -1],
					[5, 10, 25, 50, 100, "All"]
				]
			});

			$("#listRecView_info, #listRecView_paginate").detach().appendTo('#trnsfrPaginate');
			if ($('#listRecView').data('attendance-print') === true) {
				$('<button type="button" class="btn btn-outline-light btn-sm attendance-print-button" onclick="printFilteredOnDutyEmployees()"><i class="fas fa-print me-1"></i>Print</button>')
					.prependTo('#trnsfrPaginate');
			}

			$(".remove-dropdown select").remove();
			$(".remove-dropdown").removeClass('sorting');
			$(".remove-dropdown").removeClass('sorting_asc');
			$(".remove-dropdown").removeClass('sorting_desc');

			$('.table-responsive table.dataTable thead .sorting').on('click', function(event) {
				$(".remove-dropdown select").remove();
				$(".remove-dropdown").removeClass('sorting');
				$(".remove-dropdown").removeClass('sorting_asc');
				$(".remove-dropdown").removeClass('sorting_desc');
			});
		});
	</script>
	
	<script src="<?php echo $domainhome; ?>/assets/js/startup-bootstrap.js"></script>
	<script src="<?php echo $domainhome; ?>/assets/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
	<script src="<?php echo $domainhome; ?>/assets/demo/chart-area-demo.js"></script>
	<script src="<?php echo $domainhome; ?>/assets/demo/chart-bar-demo.js"></script>
	<script src="<?php echo $domainhome; ?>/assets/demo/chart-pie-demo.js"></script>
	<script src="<?php echo $domainhome; ?>/assets/js/functions.js"></script>
		
</body>
</html>
