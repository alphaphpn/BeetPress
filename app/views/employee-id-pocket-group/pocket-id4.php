
		<tr>
			<td>
				<?php 
					$empidcodeNow = isset($_GET["empid4"]) ? $_GET["empid4"] : null;
					include "model/employee/setcurrentemployee.php";
					$createdatcc4 = $createdatcc;
					$empidcodecc4 = $empidcodeNow;
					$nicknamecc4 = $nicknamecc;
					$empnameforidcc4 = $empnameforidcc;
					$birthdaycc4 = date("F j, Y", strtotime($birthdaycc));
					$designationforidcc4 = $designationforidcc;
					$officenameforidcc4 = $officenameforidcc;
					$mphonecc4 = $mphonecc;
					$gendercc4 = $gendercc;
					$incaseofemergencynamecc4 = $incaseofemergencynamecc;
					$incaseofemergencycontactcc4 = $incaseofemergencycontactcc;
					$incaseofemergencyelationcc4 = $incaseofemergencyelationcc;
					$addresscc4 = $addresscc;
					$forqrcode4 = trim($empidcodecc4).'-'.trim(remLeaveNmbrOnly($createdatcc4));
					$profileidcc4 = $profileidcc;

					$profileidNow = $profileidcc4;
					include "model/profile/setcurrentprofile.php";
					$firstnamecc4 = $firstnamebb;
					$middlenamecc4 = $middlenamebb;
					$lastnamecc4 = $lastnamegg;
					$suffixcc4 = $suffixgg;
				?>

				<style>
					#barcode4 { border-radius: 50px; }
					#qrcode4 img { width: 100%; margin: auto; padding: 4px; }
				</style>

				<div class="id-card front-bg">
					<div class="f-photo" style="background-image: url(<?php echo $pixloc."public/employeeID/".trim($empidcodecc4).".jpeg" ?>);">
					</div>
					<div class="f-sig">
						<img src="<?php echo "public/employee_sign/".trim($empidcodecc4).".png" ?>">
					</div>
					<div class="f-emp">EMPLOYEE NUMBER: <?php echo trim($empidcodecc4); ?></div>
					<div class="f-barcode">
						<svg id="barcode4"></svg>
					</div>
					<div class="f-pos"><?php echo trim($designationforidcc4); ?></div>
					<div class="f-names">
						<div class="val-main">
							<?php 
								if ( $suffixcc4==null || empty($suffixcc4) || $suffixcc4=="" ) {
									echo trim(strtoupper($lastnamecc4));
								} else {
									echo trim(strtoupper($lastnamecc4)).', '.trim(strtoupper($suffixcc4));
								}
							?>
						</div>
						<div class="lbl">LAST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($firstnamecc4)); ?></div>
						<div class="lbl">FIRST NAME</div>
						<div class="val-main"><?php echo trim(strtoupper($middlenamecc4)); ?></div>
						<div class="lbl">MIDDLE NAME</div>
					</div>
					<div class="f-gov-sig">
						<img src="assets/media/Gov-Ann-Sign-Label.png" alt="Gov Signature">
					</div>
				</div>
			</td>

			<script>
				JsBarcode("#barcode4", <?php echo '"'.trim($empidcodecc4).'"'; ?>, {
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
						<div class="val-b"><?php echo trim(strtoupper($addresscc4)); ?></div>
						<div class="lbl">Address</div>
						<div class="val-b"><?php echo trim($birthdaycc4); ?></div>
						<div class="lbl">Date of Birth</div>
						<div class="val-b"><?php echo trim(strtoupper($gendercc4)); ?></div>
						<div class="lbl">Gender</div>
					</div>
					<div class="b-qr">
						<div id="qrcode4"></div>
					</div>

					<script>
						new QRCode(document.getElementById("qrcode4"), "<?php echo trim($forqrcode4); ?>");
					</script>

					<div class="b-grid">
						<?php
							//  Get the data of current Type of ID
							require_once "model/id_type_person_tbl/index.php";
							$personIDInfo4 = new clssPersonsIDies();
							if ( $personIDInfo4->Search_clssPersonsIDies($profileidcc4,$empidcodecc4) ) {

								$personIDInfo4->Search_clssPersonsIDies($profileidcc4,$empidcodecc4);

								for ($i = 0; $i < count($personIDInfo4->list_autoidtypekk); $i++) {
									$idtypeoo4 = $personIDInfo4->list_idtypekk[$i];
									$idnumberoo4 = $personIDInfo4->list_idnumberkk[$i];

									echo '<div><div class="val-b">'.trim($idnumberoo4).'</div><div class="lbl">'.trim($idtypeoo4).'</div></div>';
								}
							}
						?>
					</div>
					<div class="b-emergency">
						<div><?php echo trim(strtoupper($incaseofemergencynamecc4)); ?></div>
						<div><?php echo trim($incaseofemergencycontactcc4); ?></div>
						<div><?php echo trim(strtoupper($incaseofemergencyelationcc4)); ?></div>
					</div>
				</div>
			</td>
		</tr>