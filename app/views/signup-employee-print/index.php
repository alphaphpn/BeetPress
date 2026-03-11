<?php 

	$photourl1 = trim($pixloc); 

	$reg_nickname = isset($_GET["nickname"]) ? $_GET["nickname"] : null;
	$reg_ntitle = isset($_GET["ptitle"]) ? $_GET["ptitle"] : null;
	$reg_fname = isset($_GET["fname"]) ? $_GET["fname"] : null;
	$reg_mname = isset($_GET["mname"]) ? $_GET["mname"] : null;
	$reg_lname = isset($_GET["lname"]) ? $_GET["lname"] : null;
	$reg_suffix = isset($_GET["nsuffix"]) ? $_GET["nsuffix"] : null;
	$reg_profession = isset($_GET["nprofession"]) ? $_GET["nprofession"] : null;
	$reg_gender = isset($_GET["genderOptions"]) ? $_GET["genderOptions"] : null;

	$reg_birthyear = isset($_GET["birth-year"]) ? $_GET["birth-year"] : null;
	$reg_birthmonth = isset($_GET["birth-month"]) ? $_GET["birth-month"] : null;
	$reg_birthday = isset($_GET["birth-day"]) ? $_GET["birth-day"] : null;

	$birthdayme = strtotime(trim($reg_birthyear)."-".trim($reg_birthmonth)."-".trim($reg_birthday));

	$reg_plbirth = isset($_GET["pbirth"]) ? $_GET["pbirth"] : null;
	$reg_phone = isset($_GET["phone"]) ? $_GET["phone"] : null;
	$reg_phone2 = isset($_GET["phone2"]) ? $_GET["phone2"] : null;
	$reg_email = isset($_GET["email"]) ? $_GET["email"] : null;
	$reg_fbid = isset($_GET["fbid"]) ? $_GET["fbid"] : null;

	$reg_username = isset($_GET["nameuser"]) ? $_GET["nameuser"] : null;
	$reg_password = isset($_GET["password"]) ? $_GET["password"] : null;
	
	$reg_town = isset($_GET["town"]) ? $_GET["town"] : null;

	$reg_typeemployeelabel = isset($_GET["type-employee-label"]) ? $_GET["type-employee-label"] : null;
	$reg_designation = isset($_GET["designation"]) ? $_GET["designation"] : null;

	$reg_bioloclabel = isset($_GET["bioloclabel"]) ? $_GET["bioloclabel"] : null;
	$reg_bionumber = isset($_GET["bionumber"]) ? $_GET["bionumber"] : null;

	$reg_pincode = isset($_GET["pincode"]) ? $_GET["pincode"] : null;
	$reg_officetitle = isset($_GET["officetitle"]) ? $_GET["officetitle"] : null;

?>

	<style>
		table, tr, td { 
			border: 1px solid black; 
		}

		table, tr, td:nth-child(1n) {
			text-wrap-mode: nowrap;
			white-space: nowrap;
			padding: 6px;
		}

		table, tr, td:nth-child(2n) {
			width: 100%;
		}
	</style>

	<div style="width: 100%; max-width: 768px; margin-left: auto; margin-right: auto;">
		<table style="width: 100%; width: -webkit-fill-available;">
			<tbody>
				<tr>
					<td colspan="2" align="center">
						<img src="<?php echo trim($photourl1)."/public/employeeID/30182205.jpeg"; ?>" style="width: 100%; max-width: 100px; margin: auto; text-align: center; filter: grayscale(1);">
					</td>
				</tr>

				<tr>
					<td>Employee ID:</td>
					<td><?php echo trim($reg_bionumber); ?></td>
				</tr>

				<tr>
					<td>PIN:</td>
					<td><?php echo trim($reg_pincode); ?></td>
				</tr>

				<tr>
					<td>Designation:</td>
					<td><?php echo trim($reg_designation); ?></td>
				</tr>

				<tr>
					<td>Username:</td>
					<td><?php echo trim($reg_username); ?></td>
				</tr>

				<tr>
					<td>Password:</td>
					<td><?php echo trim($reg_password); ?></td>
				</tr>

				<tr>
					<td>Nickname:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Title:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>First Name:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Middle Name:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Last Name:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Suffix:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Profession:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Gender:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Birthday:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Place of Birth:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Primary Phone:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>2nd Phone:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>E-mail:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Facebook:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Status:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Designated @:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Bio Location:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>

				<tr>
					<td>Reg. Voter @:</td>
					<td><?php echo trim($xxxxx); ?></td>
				</tr>
			</tbody>
		</table>
	</div>