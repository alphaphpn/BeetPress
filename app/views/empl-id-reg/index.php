<?php 

	$reg_typeemployee = null;
	$reg_office = null;
	$reg_biolocation = null;
	$reg_bionumber = null;
	$reg_employeeid = null;
	$reg_pincode = null;
	$reg_pincode2 = $reg_pincode;

	$reg_town = null;
	$reg_typeemployee = null;
	$reg_typeemployeeabrv = null;
	$reg_typeemployeelabel = null;
	$reg_designation = null;

	$reg_bioloclabel = null;

	$reg_officeid = $_SESSION['d2s8wu_officeid'];
	require_once "model/office-signatory/index.php";
	$officeSignatoryLst = new officeSignatory();
	$officeSignatoryLst->vwofficeSignatorySelected($reg_officeid);
	for ($i = 0; $i < count($officeSignatoryLst->list_officenamejj); $i++) {
		$reg_officecode = isset($officeSignatoryLst->list_agencycodejj[$i]) ? $officeSignatoryLst->list_agencycodejj[$i] : null;
		$reg_officename = isset($officeSignatoryLst->list_agencynamejj[$i]) ? $officeSignatoryLst->list_agencycodejj[$i] : null;
		$reg_officetitle = isset($officeSignatoryLst->list_officetitlejj[$i]) ? $officeSignatoryLst->list_officetitlejj[$i] : null;
		$reg_officeabrv = isset($officeSignatoryLst->list_officeabrvjj[$i]) ? $officeSignatoryLst->list_officeabrvjj[$i] : null;
		$reg_oldofficeabrv = isset($officeSignatoryLst->list_oldofficeabrvjj[$i]) ? $officeSignatoryLst->list_oldofficeabrvjj[$i] : null;
		$reg_headofficer = isset($officeSignatoryLst->list_headofficerjj[$i]) ? $officeSignatoryLst->list_headofficerjj[$i] : null;
		$reg_headtitle = isset($officeSignatoryLst->list_headtitlejj[$i]) ? $officeSignatoryLst->list_headtitlejj[$i] : null;
		$reg_authhead = isset($officeSignatoryLst->list_authheadjj[$i]) ? $officeSignatoryLst->list_authheadjj[$i] : null;
		$reg_authtitle = isset($officeSignatoryLst->list_authtitlejj[$i]) ? $officeSignatoryLst->list_authtitlejj[$i] : null;
		$reg_authdescription = isset($officeSignatoryLst->list_authdescriptionjj[$i]) ? $officeSignatoryLst->list_authdescriptionjj[$i] : null;
		$reg_officegpslocation = isset($officeSignatoryLst->list_officegpslocationjj[$i]) ? $officeSignatoryLst->list_officegpslocationjj[$i] : null;

		$reg_officenmbering = isset($officeSignatoryLst->list_officesignatoryautoidjj[$i]) ? $officeSignatoryLst->list_officesignatoryautoidjj[$i] : null;
	}

	$fullname_mi = null;

?>

	<style>
		#disp-vid { max-width: 290px; height: 356px!important; overflow: hidden; }
		#disp-vid video#video { max-height: 350px; object-fit: cover; margin-left: calc(100% / -2.5); }

		.vidframez {
			position: absolute;
			border-width: 12px 56px 88px 56px;
			border-style: solid;
			border-color: rgba(0, 0, 0, 0.2);
			box-sizing: border-box;
			inset: 0px;
			height: 350px;
		}

		@media only screen and (max-width: 425px) {
			#disp-vid video#video { margin-left: auto; margin-right: auto; }
		}
	</style>

	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-6 m-auto py-4">
				<div class="card">
					<div class="card-header">
						<h4 class="text-center">List of Accounts</h4>
					</div>

					<div class="card-content p-3 scrollable vh-70">
						
						<div class="table-responsive">
							<table id="listRecView" class="table table-dark table-striped table-hover">
								<thead id="remSortH">
									<tr>
										<th class="remove-dropdown"></th> <!-- No. -->
										<th class="remove-dropdown"></th> <!-- Nickname -->
										<th class="remove-dropdown"></th> <!-- FullName -->
										<th></th> <!-- Gender -->
										<th class="remove-dropdown"></th> <!-- Birthday -->
										<th class="remove-dropdown"></th> <!-- Email -->
										<th class="remove-dropdown"></th> <!-- Phone -->
										<th class="remove-dropdown"></th> <!-- Profile ID -->
										<th class="remove-dropdown"></th> <!-- UserID -->
										<th class="remove-dropdown"></th> <!-- ImgData -->
										<th class="remove-dropdown"></th> <!-- Action -->
									</tr>
								</thead>

								<thead id="theadtitle">
									<tr>
										<th>No.</th>
										<th>Nickname</th>
										<th>FullName</th>
										<th>Gender</th>
										<th>Birthday</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Profile ID</th>
										<th>UserID</th>
										<th>ImgData</th>
										<th>Action</th>
									</tr>
								</thead>

								<tbody>
									<?php 

										include_once "model/profile/index.php";
										$profileAcctx = new clssProfile();

										if ( $profileAcctx->list_forEmployeeReg() ) {
											$profileAcctx->list_forEmployeeReg();

											$xno_oo = 0;
											for ($i = 0; $i < count($profileAcctx->list_profileidii); $i++) {
												$xno_oo = $xno_oo + 1;
												$profileautoid_oo = isset($profileAcctx->list_profileautoidii[$i]) ? $profileAcctx->list_profileautoidii[$i] : null;
												$gender_oo = isset($profileAcctx->list_genderii[$i]) ? $profileAcctx->list_genderii[$i] : null;
												$birthday_oo = isset($profileAcctx->list_birthdateii[$i]) ? $profileAcctx->list_birthdateii[$i] : null;
												$email_oo = isset($profileAcctx->list_emailii[$i]) ? $profileAcctx->list_emailii[$i] : null;
												$mobile_oo = isset($profileAcctx->list_mobileii[$i]) ? $profileAcctx->list_mobileii[$i] : null;

												echo '<tr>';
													echo '<td>'.$xno_oo.'</td>';
													echo '<td>'.$profileAcctx->list_nicknameii[$i].'</td>';
													echo '<td>'.$profileAcctx->list_fullnameii[$i].'</td>';
													echo '<td>'.trim($gender_oo).'</td>';
													echo '<td>'.trim(date($birthday_oo)).'</td>';
													echo '<td>'.trim($email_oo).'</td>';
													echo '<td>'.trim($mobile_oo).'</td>';
													echo '<td>'.$profileAcctx->list_profileidii[$i].'</td>';
													echo '<td>'.$profileAcctx->list_useridii[$i].'</td>';
													echo '<td class="text-center"><img src="'.$profileAcctx->list_photoii[$i].'" style="width: auto; max-width: 50px; height: 50px;"></td>';
													echo '<td><button id="user-'.$profileautoid_oo.'" class="btn btn-danger userprofiledata" title="Register as Employee" data-fullname="'.$profileAcctx->list_fullnameii[$i].'" onclick="fnUserData(id);"><i class="fas fa-share"></i></button></td>';
												echo '</tr>';
											}
										} else {
											echo '<tr>';
												echo '<td colspan="10">No Record Found.</td>';
											echo '</tr>';
										}

									?>
								</tbody>

								<script>
									function fnUserData(id) {
										const userprofiledata = document.getElementById(id);
										let fullname = userprofiledata.dataset.fullname;
										let fullnamemi = document.getElementById("fullname-mi");
										fullnamemi.value = fullname;
									}
								</script>

								<tfoot>
									<tr>
										<td class="remove-dropdown"></td> <!-- No. -->
										<td class="remove-dropdown"></td> <!-- Nickname -->
										<td class="remove-dropdown"></td> <!-- FullName -->
										<td></td> <!-- Gender -->
										<td class="remove-dropdown"></td> <!-- Birthday -->
										<td class="remove-dropdown"></td> <!-- Email -->
										<td class="remove-dropdown"></td> <!-- Phone -->
										<td class="remove-dropdown"></td> <!-- Profile ID -->
										<td class="remove-dropdown"></td> <!-- UserID -->
										<td class="remove-dropdown"></td> <!-- ImgData -->
										<td class="remove-dropdown"></td> <!-- Action -->
									</tr>
								</tfoot>
							</table>
						</div>

					</div>

					<div class="card-footer">
						<div id="trnsfrPaginate" class="dataTables_wrapper d-flex justify-content-between"></div>
					</div>
				</div>

				<script>
					$(document).ready( function () {
						$('#listRecView').DataTable( {
							initComplete: function () {
								this.api().columns().every( function () {

									/** Filter Group for each column Start **/
									var column = this;
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

									column.data().unique().sort().each( function ( d, j ) {
										select.append( '<option value="'+d+'">'+d+'</option>' )
									});
									/** Filter Group for each column End **/
								});
							}, 
							lengthMenu: [
								[5, 10, 25, 50, 100, -1],
								[5, 10, 25, 50, 100, "All"]
							]
						});

						$("#listRecView_info, #listRecView_paginate").detach().appendTo('#trnsfrPaginate');

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
			</div>

			<div class="col-xl-6 m-auto py-4">
				<div class="card">
					<form id="empsignup" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
						<div class="card-header">
							<h4 class="text-center">Employee ID Registration</h4>
						</div>

						<div class="card-content p-3 scrollable vh-65">
							<div class="form-floating my-2">
								<input id="fullname-mi" type="text" value="<?php echo trim($fullname_mi); ?>" onfocus="this.select();" class="form-control" placeholder="Enter Fullname" name="fullname-mi" pattern="[A-Z,Ñ,' ',',','.']{6,28}" required>
								<label for="employeeid">Enter Fullname</label>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid Format! All Caps and upto 28 character only.</div>

								<div class="d-flex flex-row gap-2 m-1">
									<div id="fullname-result"><div class="text-danger"><i class='fas fa-ban'></i> Invalid or Employee already exist.</div></div>
								</div>
							</div>

							<div class="form-floating my-2">
								<select id="type-employee" class="form-select form-control" name="type-employee" required>
									<option value disabled <?php if ( empty(trim($reg_typeemployee)) ) { echo "selected"; } ?>> -- select an option -- </option>
									<option id="emptypex-1" value="1" data-value="REG" label="Regular/Permanent" <?php if ( trim($reg_typeemployee) == 1 ) { echo "selected"; } ?>></option>
									<option id="emptypex-2" value="2" data-value="TMP" label="Temporary" <?php if ( trim($reg_typeemployee) == 2 ) { echo "selected"; } ?>></option>
									<option id="emptypex-3" value="3" data-value="COS" label="Contractual" <?php if ( trim($reg_typeemployee) == 3 ) { echo "selected"; } ?>></option>
									<option id="emptypex-4" value="4" data-value="JO" label="Casual" <?php if ( trim($reg_typeemployee) == 4 ) { echo "selected"; } ?>></option>
									<option id="emptypex-5" value="5" data-value="PRB" label="Probationary" <?php if ( trim($reg_typeemployee) == 5 ) { echo "selected"; } ?>></option>
									<option id="emptypex-6" value="6" data-value="COT" label="Coterminous" <?php if ( trim($reg_typeemployee) == 6 ) { echo "selected"; } ?>></option>
								</select>
								<label for="type-employee">Enter Employee Status</label>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Please fill out this field.</div>
							</div>

							<div class="my-2">
								<input id="type-employee-abrv" value="<?php echo trim(htmlspecialchars($reg_typeemployeeabrv)); ?>" type="text" placeholder="Abrv Employee Type" name="type-employee-abrv" readonly required>
								<input id="type-employee-label" value="<?php echo trim(htmlspecialchars($reg_typeemployeelabel)); ?>" type="text" placeholder="Title Employee Type" name="type-employee-label" readonly required>
							</div>

							<div class="my-2">
								<input id="officeid" type="text" name="officeid" value="<?php echo trim(htmlspecialchars($reg_officeid)); ?>" placeholder="Office ID" readonly required>
								<input id="officecode" type="text" name="officecode" value="<?php echo trim(htmlspecialchars($reg_officecode)); ?>" placeholder="Office Code" readonly required>
								<input id="officename" type="text" name="officename" value="<?php echo trim(htmlspecialchars($reg_officename)); ?>" placeholder="Office Name" readonly required>
								<input id="officetitle" type="text" name="officetitle" value="<?php echo trim(htmlspecialchars($reg_officetitle)); ?>" placeholder="Office Title" readonly required>
								<input id="officeabrv" type="text" name="officeabrv" value="<?php echo trim(htmlspecialchars($reg_officeabrv)); ?>" placeholder="Office Abrv." readonly required>
								<input id="oldofficeabrv" type="text" name="oldofficeabrv" value="<?php echo trim(htmlspecialchars($reg_oldofficeabrv)); ?>" placeholder="Office Old Abrv." readonly>
								<input id="headofficer" type="text" name="headofficer" value="<?php echo trim(htmlspecialchars($reg_headofficer)); ?>" placeholder="Head Officer" readonly required>
								<input id="headtitle" type="text" name="headtitle" value="<?php echo trim(htmlspecialchars($reg_headtitle)); ?>" placeholder="Head Title" readonly required>
								<input id="authhead" type="text" name="authhead" value="<?php echo trim(htmlspecialchars($reg_authhead)); ?>" placeholder="Authorize Head" readonly>
								<input id="authtitle" type="text" name="authtitle" value="<?php echo trim(htmlspecialchars($reg_authtitle)); ?>" placeholder="Authorize Title" readonly>
								<input id="authdescription" type="text" name="authdescription" value="<?php echo trim(htmlspecialchars($reg_authdescription)); ?>" placeholder="Authorize Description" readonly>
								<input id="officegpslocation" type="text" name="officegpslocation" value="<?php echo trim(htmlspecialchars($reg_officegpslocation)); ?>" placeholder="GPS Office" readonly required>

								<input id="officenmbering" type="text" name="officenmbering" value="<?php echo trim(htmlspecialchars($reg_officenmbering)); ?>" placeholder="Office Number" readonly required>
							</div>

							<div class="form-floating my-2">
								<input id="bionumber" type="number" value="<?php echo trim(htmlspecialchars($reg_bionumber)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Enter Biometric Number" name="bionumber" required>
								<label for="bionumber">Enter Biometric Number</label>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid Biometric Number</div>

								<div class="d-flex flex-row gap-2 m-1">
									<div id="bionmbr-result"><div class="text-danger"><i class='fas fa-ban'></i> Biometric Number NOT Available</div></div>
									<button type="button" class="btn btn-sm btn-success" onclick="suggestBioNmbrz();">Generate ID#</button>
								</div>
							</div>

							<div class="form-floating my-2">
								<input id="employeeid" type="number" value="<?php echo trim(htmlspecialchars($reg_employeeid)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Enter Employee ID" name="employeeid" required>
								<label for="employeeid">Enter Employee ID</label>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid Employee ID</div>

								<div class="d-flex flex-row gap-2 m-1">
									<div id="emplidnmbr-result"><div class="text-danger"><i class='fas fa-ban'></i> Employee ID Number NOT Available</div></div>
								</div>
							</div>

							<div class="form-floating my-2">
								<input id="pincode" type="number" value="<?php echo trim(htmlspecialchars($reg_pincode)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Enter PIN Code" name="pincode" onpaste="return false;" required>
								<label for="pincode">Enter PIN Code</label>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid PIN Code. Up to 8 digit only.</div>
							</div>

							<div class="my-2">
								<button type="button" class="btn btn-sm btn-dark" onclick="genetPincodex();">Generate PIN Code</button>
							</div>

							<div class="form-floating my-2">
								<input id="pincode2" type="number" value="<?php echo trim(htmlspecialchars($reg_pincode2)); ?>" onfocus="this.select();" min="100000" max="99999999" class="form-control" placeholder="Re-Type PIN Code" name="pincode2" onpaste="return false;" required>
								<label for="pincode2">Re-Type PIN Code</label>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Invalid PIN Code. Up to 8 digit only.</div>
							</div>

							<div class="form-floating my-2">
								<input id="designation" type="text" value="<?php echo trim(htmlspecialchars($reg_designation)); ?>" onfocus="this.select();" class="form-control" placeholder="Enter your Designation" name="designation" list="list_designation" required>
								<label for="designation">Enter your Designation</label>
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Your Designation is Required!</div>

								<datalist id="list_designation">
									<?php 
										require_once "model/employee/index.php";
										$designationLst = new employeeAcct();
										$designationLst->fn_ListDesignation();

										for ($i = 0; $i < count($designationLst->list_designationforidee); $i++) {
											$thedesignationhaha = $designationLst->list_designationforidee[$i];

											echo '<option value="'.$thedesignationhaha.'">'.$thedesignationhaha.'</option>';
										}
									?>
								</datalist>
							</div>

							<div>
								<input type="text" id="gender" name="gender" placeholder="Gender" readonly>
								<input type="date" id="birthdate" name="birthdate" placeholder="Birthday" readonly>
								<input type="text" id="nickname" name="nickname" placeholder="Nickname" readonly>
								<input type="text" id="userid" name="userid" placeholder="UserID" readonly>
								<input type="text" id="profileid" name="profileid" placeholder="Profile ID" readonly>
							</div>
						</div>

						<div class="card-footer text-center">
							<button id="regemployee" type="submit" class="btn btn-primary" name="btnSubmit">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>
		const typeemployee = document.getElementById("type-employee");
		const typeemployeeabrv = document.getElementById("type-employee-abrv");
		const typeemployeelabel = document.getElementById("type-employee-label");
		typeemployee.addEventListener('change', async function() {
			var typeemployeeval = typeemployee.value;
			typeemployeeabrv.value = document.querySelector('option[id="emptypex-' + typeemployeeval + '"]').dataset.value;
			typeemployeelabel.value = document.querySelector('option[id="emptypex-' + typeemployeeval + '"]').label;
		});

		const bionmbrresult = document.getElementById("bionmbr-result");
		const emplidnmbrresult = document.getElementById("emplidnmbr-result");
		const bionumberget = document.getElementById("bionumber");
		const employeeidget = document.getElementById("employeeid");
		const officenmbering = document.getElementById("officenmbering");
		function suggestBioNmbrz() {
			if ( typeemployee.value === "" ) {
				alert("Please Select Employee Status!");
				typeemployee.focus();
			} else if ( officenmbering.value === "" ) {
				alert("Please indicate Office.");
			} else {
				let theofficenmbering = String(officenmbering.value).padStart(2, '0');
				bionumberget.value = typeemployee.value + theofficenmbering + randNmbrfive();
				employeeidget.value = bionumberget.value;
				bionumberget.focus();
			}
		}

		const fullnamemi = document.getElementById('fullname-mi');
		const fullnameresult = document.getElementById('fullname-result');
		function showFullNameExist(fullname) {
			if (fullname.length == 0) {
				fullname.innerHTML = "<div class='text-danger'><i class='fas fa-ban'></i> Invalid or Fullname already exist.</div>";
				return;
			} else {
				const xmlhttptt = new XMLHttpRequest();
				xmlhttptt.onload = function() {
					fullnameresult.innerHTML = this.responseText;
				}
				xmlhttptt.open("GET", "model/employee/ifemplyfullnameexist.php?fullname-mi=" + fullname);
				xmlhttptt.send();
			}
		}
		fullnamemi.addEventListener('focus', function(event) {
			showFullNameExist(fullnamemi.value);
		});
		fullnamemi.addEventListener('input', function(event) {
			showFullNameExist(fullnamemi.value);
		});
		fullnamemi.addEventListener('blur', function(event) {
			showFullNameExist(fullnamemi.value);
		});

		function showBioIDNumbrzExist(bioempidnmbr) {
			if (bioempidnmbr.length == 0) {
				bionmbrresult.innerHTML = "<div class='text-danger'><i class='fas fa-ban'></i> Biometric Number NOT Available</div>";
				return;
			} else {
				const xmlhttptt = new XMLHttpRequest();
				xmlhttptt.onload = function() {
					bionmbrresult.innerHTML = this.responseText;
				}
				xmlhttptt.open("GET", "model/employee/ifbionmbrexist.php?bioempidnmbr=" + bioempidnmbr);
				xmlhttptt.send();
			}
		}

		bionumberget.addEventListener('focus', function(event) {
			showBioIDNumbrzExist(bionumberget.value);
		});
		bionumberget.addEventListener('input', function(event) {
			showBioIDNumbrzExist(bionumberget.value);
		});
		bionumberget.addEventListener('blur', function(event) {
			showBioIDNumbrzExist(bionumberget.value);
		});

		function showEmpIDNumbrzExist(bioempidnmbr) {
			if (bioempidnmbr.length == 0) {
				emplidnmbrresult.innerHTML = "<div class='text-danger'><i class='fas fa-ban'></i> Employee ID Number NOT Available</div>";
				return;
			} else {
				const xmlhttptt = new XMLHttpRequest();
				xmlhttptt.onload = function() {
					emplidnmbrresult.innerHTML = this.responseText;
				}
				xmlhttptt.open("GET", "model/employee/ifemplymbrexist.php?bioempidnmbr=" + bioempidnmbr);
				xmlhttptt.send();
			}
		}

		employeeidget.addEventListener('focus', function(event) {
			showEmpIDNumbrzExist(employeeidget.value);
		});
		employeeidget.addEventListener('input', function(event) {
			showEmpIDNumbrzExist(employeeidget.value);
		});
		employeeidget.addEventListener('blur', function(event) {
			showEmpIDNumbrzExist(employeeidget.value);
		});

		const kpincode = document.getElementById("pincode");
		const kpincode2 = document.getElementById("pincode2");
		function genetPincodex() {
			kpincode.value = suggestPincodexx();
			kpincode2.value = kpincode.value;
			kpincode.focus();
		}
	</script>