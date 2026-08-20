<?php

	$androidappz = $domainhome."/public/emp-att.apk";
	$ipphonecamapp = $domainhome."/public/ip-phone-cam.apk"

?>

	<div class="container mt-3">
		<h2>Downloads</h2>
		<p>List of downloadable file(s):</p>

		<table class="table table-striped">
			<thead>
				<tr>
				<th>Title</th>
				<th>Description</th>
				<th>Link</th>
				</tr>
			</thead>

			<tbody>
				<tr>
					<td><a href="<?php echo $androidappz ?>" target="_blank">Employee Attendance</a></td>
					<td>Attendance Record for PLGU-ZSP Employee use only! Android App.</td>
					<td><a href="<?php echo $androidappz ?>" target="_blank" class="btn third-bg-color text-white">Download</a></td>
				</tr>

				<tr>
					<td><a href="<?php echo $ipphonecamapp ?>" target="_blank">IP Phone Camera</a></td>
					<td>IP Phone Camera for PLGU-ZSP New Employee Registration use only! Android App.</td>
					<td><a href="<?php echo $ipphonecamapp ?>" target="_blank" class="btn third-bg-color text-white">Download</a></td>
				</tr>
			</tbody>
		</table>
	</div>