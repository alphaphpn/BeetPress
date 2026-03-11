
		<tr>
			<td>
				<?php 
					$empidcodeNow = isset($_GET["empid1"]) ? $_GET["empid1"] : null;
					include_once "model/employee/setcurrentemployee.php";
					$createdatcc1 = $createdatcc;
					$empidcodecc1 = $empidcodeNow;
					$nicknamecc1 = $nicknamecc;
					$empnameforidcc1 = $empnameforidcc;
					$birthdaycc1 = date("F j, Y", strtotime($birthdaycc));
					$designationforidcc1 = $designationforidcc;
					$officenameforidcc1 = $officenameforidcc;
					$mphonecc1 = $mphonecc;
					$gendercc1 = $gendercc;
					$incaseofemergencynamecc1 = $incaseofemergencynamecc;
					$incaseofemergencycontactcc1 = $incaseofemergencycontactcc;
					$incaseofemergencyelationcc1 = $incaseofemergencyelationcc;
					$addresscc1 = $addresscc;
					$forqrcode1 = trim($empidcodecc1).'-'.trim(remLeaveNmbrOnly($createdatcc1));
					$profileidcc1 = $profileidcc;

					$profileidNow = $profileidcc1;
					include_once "model/profile/setcurrentprofile.php";
					$firstnamecc1 = $firstnamebb;
					$middlenamecc1 = $middlenamebb;
					$lastnamecc1 = $lastnamegg;
					$suffixcc1 = $suffixgg;
				?>

				<style>
					#barcode1 { border-radius: 50px; }
					#qrcode1 img { width: 100%; margin: auto; padding: 4px; }
				</style>

				<div class="id-card front-bg">
					<div class="f-photo" style="background-image: url(<?php echo $pixloc."public/employeeID/".trim($empidcodecc1).".jpeg" ?>);">
					</div>
					<div class="f-sig">
						<img src="<?php echo "public/employee_sign/".trim($empidcodecc1).".png" ?>">
					</div>
					<div class="f-emp">EMPLOYEE NUMBER: <?php echo trim($empidcodecc1); ?></div>
					<div class="f-barcode">
						<svg id="barcode1"></svg>
					</div>
					<div class="f-pos"><?php echo trim($designationforidcc1); ?></div>
					<div class="f-names">
						<div class="val-main">
							<?php 
								if ( $suffixcc1==null || empty($suffixcc1) || $suffixcc1=="" ) {
									echo trim(strtoupper($lastnamecc1));
								} else {
									echo trim(strtoupper($lastnamecc1)).', '.trim(strtoupper($suffixcc1));
								}
							?>
						</div>
						<div class="lbl">LAST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($firstnamecc1)); ?></div>
						<div class="lbl">FIRST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($middlenamecc1)); ?></div>
						<div class="lbl">MIDDLE NAME</div>
					</div>
					<div class="f-gov-sig">
						<img src="assets/media/Gov-Ann-Sign-Label.png" alt="Gov Signature">
					</div>
				</div>
			</td>

			<script>
				JsBarcode("#barcode1", <?php echo '"'.trim($empidcodecc1).'"'; ?>, {
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
						<div class="val-b"><?php echo trim(strtoupper($addresscc1)); ?></div>
						<div class="lbl">Address</div>
						<div class="val-b"><?php echo trim($birthdaycc1); ?></div>
						<div class="lbl">Date of Birth</div>
						<div class="val-b"><?php echo trim(strtoupper($gendercc1)); ?></div>
						<div class="lbl">Gender</div>
					</div>
					<div class="b-qr">
						<div id="qrcode1"></div>
					</div>

					<script>
						new QRCode(document.getElementById("qrcode1"), "<?php echo trim($forqrcode1); ?>");
					</script>

					<div class="b-grid">
						<?php
							//  Get the data of current Type of ID
							require_once "model/id_type_person_tbl/index.php";
							$personIDInfo1 = new clssPersonsIDies();
							if ( $personIDInfo1->Search_clssPersonsIDies($profileidcc1,$empidcodecc1) ) {

								$personIDInfo1->Search_clssPersonsIDies($profileidcc1,$empidcodecc1);

								for ($i = 0; $i < count($personIDInfo1->list_autoidtypekk); $i++) {
									$idtypeoo1 = $personIDInfo1->list_idtypekk[$i];
									$idnumberoo1 = $personIDInfo1->list_idnumberkk[$i];

									echo '<div><div class="val-b">'.trim($idnumberoo1).'</div><div class="lbl">'.trim($idtypeoo1).'</div></div>';
								}
							}
						?>
					</div>
					<div class="b-emergency">
						<div><?php echo trim(strtoupper($incaseofemergencynamecc1)); ?></div>
						<div><?php echo trim($incaseofemergencycontactcc1); ?></div>
						<div><?php echo trim(strtoupper($incaseofemergencyelationcc1)); ?></div>
					</div>
				</div>
			</td>
		</tr>