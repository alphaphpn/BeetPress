
		<tr>
			<td>
				<?php 
					$empidcodeNow = isset($_GET["empid3"]) ? $_GET["empid3"] : null;
					include "model/employee/setcurrentemployee.php";
					$createdatcc3 = $createdatcc;
					$empidcodecc3 = $empidcodeNow;
					$nicknamecc3 = $nicknamecc;
					$empnameforidcc3 = $empnameforidcc;
					$birthdaycc3 = date("F j, Y", strtotime($birthdaycc));
					$designationforidcc3 = $designationforidcc;
					$officenameforidcc3 = $officenameforidcc;
					$mphonecc3 = $mphonecc;
					$gendercc3 = $gendercc;
					$incaseofemergencynamecc3 = $incaseofemergencynamecc;
					$incaseofemergencycontactcc3 = $incaseofemergencycontactcc;
					$incaseofemergencyelationcc3 = $incaseofemergencyelationcc;
					$addresscc3 = $addresscc;
					$forqrcode3 = trim($empidcodecc3).'-'.trim(remLeaveNmbrOnly($createdatcc3));
					$profileidcc3 = $profileidcc;

					$profileidNow = $profileidcc3;
					include "model/profile/setcurrentprofile.php";
					$firstnamecc3 = $firstnamebb;
					$middlenamecc3 = $middlenamebb;
					$lastnamecc3 = $lastnamegg;
					$suffixcc3 = $suffixgg;
				?>

				<style>
					#barcode3 { border-radius: 50px; }
					#qrcode3 img { width: 100%; margin: auto; padding: 4px; }
				</style>

				<div class="id-card front-bg">
					<div class="f-photo" style="background-image: url(<?php echo $pixloc."public/employeeID/".trim($empidcodecc3).".jpeg" ?>);">
					</div>
					<div class="f-sig">
						<img src="<?php echo "public/employee_sign/".trim($empidcodecc3).".png" ?>">
					</div>
					<div class="f-emp">EMPLOYEE NUMBER: <?php echo trim($empidcodecc3); ?></div>
					<div class="f-barcode">
						<svg id="barcode3"></svg>
					</div>
					<div class="f-pos"><?php echo trim($designationforidcc3); ?></div>
					<div class="f-names">
						<div class="val-main">
							<?php 
								if ( $suffixcc3==null || empty($suffixcc3) || $suffixcc3=="" ) {
									echo trim(strtoupper($lastnamecc3));
								} else {
									echo trim(strtoupper($lastnamecc3)).', '.trim(strtoupper($suffixcc3));
								}
							?>
						</div>
						<div class="lbl">LAST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($firstnamecc3)); ?></div>
						<div class="lbl">FIRST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($middlenamecc3)); ?></div>
						<div class="lbl">MIDDLE NAME</div>
					</div>
					<div class="f-gov-sig">
						<img src="assets/media/Gov-Ann-Sign-Label.png" alt="Gov Signature">
					</div>
				</div>
			</td>

			<script>
				JsBarcode("#barcode3", <?php echo '"'.trim($empidcodecc3).'"'; ?>, {
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
						<div class="val-b"><?php echo trim(strtoupper($addresscc3)); ?></div>
						<div class="lbl">Address</div>
						<div class="val-b"><?php echo trim($birthdaycc3); ?></div>
						<div class="lbl">Date of Birth</div>
						<div class="val-b"><?php echo trim(strtoupper($gendercc3)); ?></div>
						<div class="lbl">Gender</div>
					</div>
					<div class="b-qr">
						<div id="qrcode3"></div>
					</div>

					<script>
						new QRCode(document.getElementById("qrcode3"), "<?php echo trim($forqrcode3); ?>");
					</script>

					<div class="b-grid">
						<?php
							//  Get the data of current Type of ID
							require_once "model/id_type_person_tbl/index.php";
							$personIDInfo3 = new clssPersonsIDies();
							if ( $personIDInfo3->Search_clssPersonsIDies($profileidcc3,$empidcodecc3) ) {

								$personIDInfo3->Search_clssPersonsIDies($profileidcc3,$empidcodecc3);

								for ($i = 0; $i < count($personIDInfo3->list_autoidtypekk); $i++) {
									$idtypeoo3 = $personIDInfo3->list_idtypekk[$i];
									$idnumberoo3 = $personIDInfo3->list_idnumberkk[$i];

									echo '<div><div class="val-b">'.trim($idnumberoo3).'</div><div class="lbl">'.trim($idtypeoo3).'</div></div>';
								}
							}
						?>
					</div>
					<div class="b-emergency">
						<div><?php echo trim(strtoupper($incaseofemergencynamecc3)); ?></div>
						<div><?php echo trim($incaseofemergencycontactcc3); ?></div>
						<div><?php echo trim(strtoupper($incaseofemergencyelationcc3)); ?></div>
					</div>
				</div>
			</td>
		</tr>