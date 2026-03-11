<?php $photourl3 = trim($pixloc); ?>

	<style>
		#barcode { border-radius: 50px; }
		#qrcode4 img { width: 100%; max-width: 58px; margin: auto; padding: 8px; }
	</style>

	<table style="width: 100%; width: -webkit-fill-available; max-width: 308px; text-align: center; margin: auto; background-image: url('<?php echo trim($domainhome); ?>/assets/media/sling-blank.jpg'); background-repeat: no-repeat; background-position: center; background-size: 100%; border-radius: 10px;">
		<tbody style="height: 495px;">

			<tr style="height: 28px;">
				<td colspan="3" style="vertical-align: bottom;">
					<p style="font-size: xx-small; font-weight: 400; margin-top: auto; margin-bottom: 0; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; max-width: 290px; margin-right: 12px; margin-left: auto; line-height: 1; text-align: right; color: #ffffff;"><?php echo trim(remLeaveNmbrOnly($createdatcc2)); ?></p>
				</td>
			</tr>

			<tr style="height: 100%;">
				<td colspan="3"></td>
			</tr>

			<tr>
				<td colspan="3">
					<div id="idempl-d" style="width: 100%; width: -webkit-fill-available; max-width: 110px; height: 140px; background-image: url('<?php echo trim($photourl3)."/public/employeeID/".trim($empidcodecc2).".jpeg"; ?>'); background-repeat: no-repeat; background-position: center; background-size: cover; margin-top: auto; margin-left: auto; margin-right: auto; margin-bottom: -6px; box-shadow: rgba(0, 0, 0, 0.16) 0px 2px 5px 0px, rgba(0, 0, 0, 0.12) 0px 2px 10px 0px; border: 2px solid #ffffff; border-radius: 5px;"></div>
				</td>
			</tr>

			<tr>
				<td colspan="3" style="margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; width: 100%;">
					<div style="margin-top: 0; margin-bottom: -16px; padding-top: 0; padding-bottom: 0; width: 100%;"><svg id="barcode4"></svg></div>
					<p style="font-weight: bolder; font-size: 46px; margin-top: 0; margin-bottom: 0px; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; max-width: 290px; margin-right: auto; margin-left: auto; line-height: 1;"><?php echo trim(mb_strtoupper($nicknamecc2)); ?></p>
					<p style="font-weight: bolder; font-size: large; margin-top: 0; margin-bottom: 0px; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; max-width: 290px; margin-right: auto; margin-left: auto; line-height: 1;"><?php echo trim(mb_strtoupper($empnameforidcc2)); ?></p>
					<!-- Fullname max-size 28char only -->
					<p style="font-weight: bold; font-size: medium; margin-top: 0; margin-bottom: 2px; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; max-width: 290px; margin-right: auto; margin-left: auto; line-height: 1;"><?php echo trim($designationforidcc2); ?></p>
					<p style="font-size: smaller; font-weight: 400; margin-top: 0; margin-bottom: 2px; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; max-width: 290px; margin-right: auto; margin-left: auto; line-height: 1;"><?php echo trim($officenameforidcc2); ?></p>
					<p style="font-size: 2px; font-weight: bolder; margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; max-width: 290px; margin-right: auto; margin-left: auto; line-height: 1;">PLGU-ZSP PLGU-ZSP PLGU-ZSP <?php echo trim($createdatcc2); ?> <?php echo trim($createdatcc2); ?> PLGU-ZSP <?php echo trim($createdatcc2); ?> PLGU-ZSP PLGU-ZSP PLGU-ZSP PLGU-ZSP <?php echo trim($createdatcc2); ?> PLGU-ZSP PLGU-ZSP PLGU-ZSP <?php echo trim($createdatcc2); ?> PLGU-ZSP PLGU-ZSP <?php echo trim($createdatcc2); ?> PLGU-ZSP PLGU-ZSP PLGU-ZSP</p>
				</td>
			</tr>

			<tr>
				<td style="width: 33.3333%; vertical-align: bottom;">
					<p style="font-size: 8px; font-weight: 400; margin-top: auto; margin-bottom: 8px; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; max-width: 290px; margin-right: auto; margin-left: 12px; line-height: 1; text-align: left; color: #ffffff;">Phone: <br><?php echo trim($mphonecc2); ?></p>
					<div style="font-size: 7px; font-weight: 400; margin-top: auto; margin-bottom: 8px; padding-top: 0; padding-bottom: 0; word-wrap: break-word; width: 100%; margin-right: auto; margin-left: 12px; line-height: 1; text-align: left; color: #ffffff;">Verify: <br>sibugay.gov.ph/employee</div>
				</td>

				<td style="width: 33.3333%;">
					<img src="<?php echo trim($domainhome); ?>/assets/media/Gov-Ann-Sign-Label.png" style="width: 100%; width: -webkit-fill-available; max-width: 80px;">
				</td>

				<td style="width: 33.3333%;">
					<div id="qrcode4"></div>
				</td>
			</tr>

			<tr style="height: 8px;">
				<td colspan="3"></td>
			</tr>
		</tbody>
	</table>

	<script>
		new QRCode(document.getElementById("qrcode4"), "<?php echo trim($empidcodecc2); ?>");
	</script>