
		<tr>
			<td>
				<?php 
					$empidcodeNow = isset($_GET["empid5"]) ? $_GET["empid5"] : null;
					include "model/employee/setcurrentemployee.php";
					$createdatcc5 = $createdatcc;
					$empidcodecc5 = $empidcodeNow;
					$nicknamecc5 = $nicknamecc;
					$empnameforidcc5 = $empnameforidcc;
					$birthdaycc5 = date("F j, Y", strtotime($birthdaycc));
					$designationforidcc5 = $designationforidcc;
					$officenameforidcc5 = $officenameforidcc;
					$mphonecc5 = $mphonecc;
					$gendercc5 = $gendercc;
					$incaseofemergencynamecc5 = $incaseofemergencynamecc;
					$incaseofemergencycontactcc5 = $incaseofemergencycontactcc;
					$incaseofemergencyelationcc5 = $incaseofemergencyelationcc;
					$addresscc5 = $addresscc;
					$forqrcode5 = trim($empidcodecc5).'-'.trim(remLeaveNmbrOnly($createdatcc5));
					$profileidcc5 = $profileidcc;

					$profileidNow = $profileidcc5;
					include "model/profile/setcurrentprofile.php";
					$firstnamecc5 = $firstnamebb;
					$middlenamecc5 = $middlenamebb;
					$lastnamecc5 = $lastnamegg;
					$suffixcc5 = $suffixgg;
				?>

				<style>
					#barcode5 { border-radius: 50px; }
					#qrcode5 img { width: 100%; margin: auto; padding: 4px; }
				</style>

				<div class="id-card front-bg">
					<div class="f-photo" style="background-image: url(<?php echo $pixloc."public/employeeID/".trim($empidcodecc5).".jpeg" ?>);">
					</div>
					<div class="f-sig">
						<img src="<?php echo "public/employee_sign/".trim($empidcodecc5).".png" ?>">
					</div>
					<div class="f-emp">EMPLOYEE NUMBER: <?php echo trim($empidcodecc5); ?></div>
					<div class="f-barcode">
						<svg id="barcode5"></svg>
					</div>
					<div class="f-pos"><?php echo trim($designationforidcc5); ?></div>
					<div class="f-names">
						<div class="val-main">
							<?php 
								if ( $suffixcc5==null || empty($suffixcc5) || $suffixcc5=="" ) {
									echo trim(strtoupper($lastnamecc5));
								} else {
									echo trim(strtoupper($lastnamecc5)).', '.trim(strtoupper($suffixcc5));
								}
							?>
						</div>
						<div class="lbl">LAST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($firstnamecc5)); ?></div>
						<div class="lbl">FIRST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($middlenamecc5)); ?></div>
						<div class="lbl">MIDDLE NAME</div>
					</div>
					<div class="f-gov-sig">
						<img src="assets/media/Gov-Ann-Sign-Label.png" alt="Gov Signature">
					</div>
				</div>
			</td>

			<script>
				JsBarcode("#barcode5", <?php echo '"'.trim($empidcodecc5).'"'; ?>, {
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
						<div class="val-b"><?php echo trim(strtoupper($addresscc5)); ?></div>
						<div class="lbl">Address</div>
						<div class="val-b"><?php echo trim($birthdaycc5); ?></div>
						<div class="lbl">Date of Birth</div>
						<div class="val-b"><?php echo trim(strtoupper($gendercc5)); ?></div>
						<div class="lbl">Gender</div>
					</div>
					<div class="b-qr">
						<div id="qrcode5"></div>
					</div>

					<script>
						new QRCode(document.getElementById("qrcode5"), "<?php echo trim($forqrcode5); ?>");
					</script>

					<div class="b-grid">
						<?php
							//  Get the data of current Type of ID
							require_once "model/id_type_person_tbl/index.php";
							$personIDInfo5 = new clssPersonsIDies();
							if ( $personIDInfo5->Search_clssPersonsIDies($profileidcc5,$empidcodecc5) ) {

								$personIDInfo5->Search_clssPersonsIDies($profileidcc5,$empidcodecc5);

								for ($i = 0; $i < count($personIDInfo5->list_autoidtypekk); $i++) {
									$idtypeoo5 = $personIDInfo5->list_idtypekk[$i];
									$idnumberoo5 = $personIDInfo5->list_idnumberkk[$i];

									echo '<div><div class="val-b">'.trim($idnumberoo5).'</div><div class="lbl">'.trim($idtypeoo5).'</div></div>';
								}
							}
						?>
					</div>
					<div class="b-emergency">
						<div><?php echo trim(strtoupper($incaseofemergencynamecc5)); ?></div>
						<div><?php echo trim($incaseofemergencycontactcc5); ?></div>
						<div><?php echo trim(strtoupper($incaseofemergencyelationcc5)); ?></div>
					</div>
				</div>
			</td>
		</tr>