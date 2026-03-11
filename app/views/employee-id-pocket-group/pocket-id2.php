
		<tr>
			<td>
				<?php 
					$empidcodeNow = isset($_GET["empid2"]) ? $_GET["empid2"] : null;
					include "model/employee/setcurrentemployee.php";
					$createdatcc2 = $createdatcc;
					$empidcodecc2 = $empidcodeNow;
					$nicknamecc2 = $nicknamecc;
					$empnameforidcc2 = $empnameforidcc;
					$birthdaycc2 = date("F j, Y", strtotime($birthdaycc));
					$designationforidcc2 = $designationforidcc;
					$officenameforidcc2 = $officenameforidcc;
					$mphonecc2 = $mphonecc;
					$gendercc2 = $gendercc;
					$incaseofemergencynamecc2 = $incaseofemergencynamecc;
					$incaseofemergencycontactcc2 = $incaseofemergencycontactcc;
					$incaseofemergencyelationcc2 = $incaseofemergencyelationcc;
					$addresscc2 = $addresscc;
					$forqrcode2 = trim($empidcodecc2).'-'.trim(remLeaveNmbrOnly($createdatcc2));
					$profileidcc2 = $profileidcc;

					$profileidNow = $profileidcc2;
					include "model/profile/setcurrentprofile.php";
					$firstnamecc2 = $firstnamebb;
					$middlenamecc2 = $middlenamebb;
					$lastnamecc2 = $lastnamegg;
					$suffixcc2 = $suffixgg;
				?>

				<style>
					#barcode2 { border-radius: 50px; }
					#qrcode2 img { width: 100%; margin: auto; padding: 4px; }
				</style>

				<div class="id-card front-bg">
					<div class="f-photo" style="background-image: url(<?php echo $pixloc."public/employeeID/".trim($empidcodecc2).".jpeg" ?>);">
					</div>
					<div class="f-sig">
						<img src="<?php echo "public/employee_sign/".trim($empidcodecc2).".png" ?>">
					</div>
					<div class="f-emp">EMPLOYEE NUMBER: <?php echo trim($empidcodecc2); ?></div>
					<div class="f-barcode">
						<svg id="barcode2"></svg>
					</div>
					<div class="f-pos"><?php echo trim($designationforidcc2); ?></div>
					<div class="f-names">
						<div class="val-main">
							<?php 
								if ( $suffixcc2==null || empty($suffixcc2) || $suffixcc2=="" ) {
									echo trim(strtoupper($lastnamecc2));
								} else {
									echo trim(strtoupper($lastnamecc2)).', '.trim(strtoupper($suffixcc2));
								}
							?>
						</div>
						<div class="lbl">LAST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($firstnamecc2)); ?></div>
						<div class="lbl">FIRST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($middlenamecc2)); ?></div>
						<div class="lbl">MIDDLE NAME</div>
					</div>
					<div class="f-gov-sig">
						<img src="assets/media/Gov-Ann-Sign-Label.png" alt="Gov Signature">
					</div>
				</div>
			</td>

			<script>
				JsBarcode("#barcode2", <?php echo '"'.trim($empidcodecc2).'"'; ?>, {
					width: 1.5, 
					displayValue: false, 
					fontOptions: 'bold', 
					fontSize: 12, 
					background: 'transparent', 
					height: 10 // Set the desired height in pixels
				});
			</script>
			<td>
				<div class="id-card back-bg">
					<div class="b-top-left">
						<div class="val-b"><?php echo trim(strtoupper($addresscc2)); ?></div>
						<div class="lbl">Address</div>
						<div class="val-b"><?php echo trim($birthdaycc2); ?></div>
						<div class="lbl">Date of Birth</div>
						<div class="val-b"><?php echo trim(strtoupper($gendercc2)); ?></div>
						<div class="lbl">Gender</div>
					</div>
					<div class="b-qr">
						<div id="qrcode2"></div>
					</div>

					<script>
						new QRCode(document.getElementById("qrcode2"), "<?php echo trim($forqrcode2); ?>");
					</script>

					<div class="b-grid">
						<?php
							//  Get the data of current Type of ID
							require_once "model/id_type_person_tbl/index.php";
							$personIDInfo2 = new clssPersonsIDies();
							if ( $personIDInfo2->Search_clssPersonsIDies($profileidcc2,$empidcodecc2) ) {

								$personIDInfo2->Search_clssPersonsIDies($profileidcc2,$empidcodecc2);

								for ($i = 0; $i < count($personIDInfo2->list_autoidtypekk); $i++) {
									$idtypeoo2 = $personIDInfo2->list_idtypekk[$i];
									$idnumberoo2 = $personIDInfo2->list_idnumberkk[$i];

									echo '<div><div class="val-b">'.trim($idnumberoo2).'</div><div class="lbl">'.trim($idtypeoo2).'</div></div>';
								}
							}
						?>
					</div>
					<div class="b-emergency">
						<div><?php echo trim(strtoupper($incaseofemergencynamecc2)); ?></div>
						<div><?php echo trim($incaseofemergencycontactcc2); ?></div>
						<div><?php echo trim(strtoupper($incaseofemergencyelationcc2)); ?></div>
					</div>
				</div>
			</td>
		</tr>