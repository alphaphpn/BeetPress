
	<div class="container-fluid">
		<div class="pt-3">
			<div class="table-responsive">
				<table id="listRecView" class="table table-dark table-striped table-hover">
					<thead id="remSortH">
						<tr>
							<th class="remove-dropdown"></th> <!-- No. -->
							<th class="remove-dropdown"></th> <!-- EmployeeID -->
							<th class="remove-dropdown"></th> <!-- Nickname -->
							<th class="remove-dropdown"></th> <!-- FullName -->
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
							<th>EmployeeID</th>
							<th>Nickname</th>
							<th>FullName</th>
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

							include_once "model/employee/index.php";
							$employeeAcctx = new employeeAcct();

							$settedofficeid = $_SESSION['d2s8wu_officeid'];

							if ( $employeeAcctx->fn_ListEmployee($settedofficeid) ) {
								$employeeAcctx->fn_ListEmployee($settedofficeid);

								$xno_oo = 0;
								for ($i = 0; $i < count($employeeAcctx->list_empidcodeee); $i++) {
									$xno_oo = $xno_oo + 1;
									$profileautoid_oo = isset($employeeAcctx->list_profileidee[$i]) ? $employeeAcctx->list_profileidee[$i] : null;
									$birthday_oo = isset($employeeAcctx->list_birthdayee[$i]) ? $employeeAcctx->list_birthdayee[$i] : null;
									$email_oo = isset($employeeAcctx->list_empemailee[$i]) ? $employeeAcctx->list_empemailee[$i] : null;
									$mobile_oo = isset($employeeAcctx->list_mphoneee[$i]) ? $employeeAcctx->list_mphoneee[$i] : null;
									$employeeid_oo = isset($employeeAcctx->list_empidcodeee[$i]) ? $employeeAcctx->list_empidcodeee[$i] : null;

									echo '<tr>';
										echo '<td>'.$xno_oo.'</td>';
										echo '<td>'.$employeeid_oo.'</td>';
										echo '<td>'.$employeeAcctx->list_nicknameee[$i].'</td>';
										echo '<td>'.$employeeAcctx->list_empnameforidee[$i].'</td>';
										echo '<td>'.trim(date($birthday_oo)).'</td>';
										echo '<td>'.trim($email_oo).'</td>';
										echo '<td><a href="tel:+63'.trim($mobile_oo).'">'.trim($mobile_oo).'</a></td>';
										echo '<td>'.$profileautoid_oo.'</td>';
										echo '<td>'.$employeeAcctx->list_uidee[$i].'</td>';
										echo '<td class="text-center"><img src="'.$pixloc.'public/employeeID/'.$employeeid_oo.'.jpeg" style="width: auto; max-width: 50px; height: 50px;"></td>';
										echo '<td></td>';
									echo '</tr>';
								}
							} else {
								echo '<tr>';
									echo '<td colspan="10">No Record Found.</td>';
								echo '</tr>';
							}

						?>
					</tbody>

					<tfoot>
						<tr>
							<td class="remove-dropdown"></td> <!-- No. -->
							<td class="remove-dropdown"></td> <!-- EmployeeID -->
							<td class="remove-dropdown"></td> <!-- Nickname -->
							<td class="remove-dropdown"></td> <!-- FullName -->
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
	</div>