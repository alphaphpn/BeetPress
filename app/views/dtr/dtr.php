<?php require_once "getempinfo.php"; ?>

	<div>
		<div class="d-flex justify-content-between">
			<label class="font-size-10">BioTag: <?php echo trim($biolocationtu); ?></label>
			<label class="font-size-10">Employee ID: <?php echo trim($empidcodetu); ?></label>
		</div>
		<div class="d-flex justify-content-between">
			<label class="font-size-11">Office: <?php echo trim($officecodetu)." | ".trim($officetitletu); ?></label>
			<label class="font-size-11">DTR-ID: <?php echo trim($dtrcodetu); ?></label>
		</div>
		<table class="table" border="2">
			<thead>
				<tr>
					<th colspan="12" class="p-0 font-size-12">CIVIL SERVICE FORM No. 48</th>
				</tr>
				<tr align="center">
					<th colspan="10" class="p-0 font-size-14">DAILY TIME RECORD</th>
				</tr>
				<tr align="center">
					<th colspan="10" class="p-0 font-size-12"><b><?php echo trim(strtoupper($empnametu)); ?></b></th>
				</tr>
				<tr align="center">
					<th colspan="10" class="p-0 font-size-10">(NAME)</th>
				</tr>
				<tr>
					<th colspan="6" class="p-0">
						<i class="font-size-10 pe-1 border-end">For the Month of</i>
						<b class="font-size-11"><?php echo trim($monthnametu)." ".trim($yearnotu); ?></b>
					</th>
					<th colspan="4" class="p-0">
						<i class="font-size-8 text-right">Regular days _____</i>
					</th>
				</tr>
				<tr>
					<th colspan="6" class="p-0">
						<i class="font-size-10">Official hours for arrival and departure</i>
					</th>
					<th colspan="4" class="p-0">
						<i class="font-size-8">Saturdays ______</i>
					</th>
				</tr>
				<tr align="center">
					<th rowspan="2" colspan="2" class="p-0 font-size-10 align-middle border-end">Day</th>
					<th colspan="2" class="p-0 font-size-10 border-end">AM</th>
					<th colspan="2" class="p-0 font-size-10 border-end">PM</th>
					<th colspan="2" class="p-0 font-size-8 border-end">Tardy/UnderTime</th>
					<th colspan="2" class="p-0 font-size-8">Overtime</th>
				</tr>
				<tr align="center">
					<th class="p-0 font-size-8 border-end">Arrival</th>
					<th class="p-0 font-size-8 border-end">Departure</th>
					<th class="p-0 font-size-8 border-end">Arrival</th>
					<th class="p-0 font-size-8 border-end">Departure</th>
					<th class="p-0 font-size-8 border-end">Hour</th>
					<th class="p-0 font-size-8 border-end">Min</th>
					<th class="p-0 font-size-8 border-end">Hour</th>
					<th class="p-0 font-size-8">Min</th>
				</tr>
			</thead>

			<tbody>

			</tbody>

			<tfoot>
				<tr>
					<td colspan="6" class="p-0 font-size-10 text-end border-end">Total:</td>
					<td  align="center" class="p-0 font-size-10 border-end"><?php echo trim($utlatehrtu); ?></td>
					<td align="center" class="p-0 font-size-10 border-end"><?php echo trim($utlatemintu); ?></td>
					<td align="center" class="p-0 font-size-10 border-end"><?php echo trim($othrtu); ?></td>
					<td align="center" class="p-0 font-size-10"><?php echo trim($otmintu); ?></td>
				</tr>
				<tr>
					<td colspan="10" class="p-0 font-size-10 border-0 text-indent-32">I CERTIFY on my honor that the above is true and correct report of the hours, work performed record, of which was made daily at the time of arrival and departure from the office</td>
				</tr>
				<tr class="border-0">
					<td colspan="5" class="pb-0 border-0"></td>
					<td colspan="5" class="pb-0 border-bottom"></td>
				</tr>
				<tr>
					<td colspan="5"></td>
					<td colspan="5" class="p-0 font-size-10 text-center">Employee's Signature</td>
				</tr>
		<?php
			if (empty($authdescriptiontu) || empty($authheadtu) || empty($authtitletu)) {
				?>
				<tr>
					<td colspan="10" class="p-0 font-size-10 pb-4">Verified as to the prescribed office hours</td>
				</tr>
				<?php
			} else {
				?>
				<tr>
					<td colspan="10" class="p-0 font-size-10 border-bottom-width-0">Verified as to the prescribed office hours</td>
				</tr>
				<tr>
					<td class="p-0 font-size-10 text-center border-bottom-width-0"></td>
					<td colspan="8" class="p-0 font-size-10 pb-4 border-bottom-width-0 font-color-dark-blue"><?php echo trim($authdescriptiontu); ?></td>
					<td class="p-0 font-size-10 text-center border-bottom-width-0"></td>
				</tr>
				<tr>
					<td class="p-0 font-size-10 text-center"></td>
					<td colspan="8" class="p-0 font-size-10 font-color-dark-blue"><b class="border-top-dotted"><b><?php echo trim(strtoupper($authheadtu)); ?></b><br><?php echo trim($authtitletu); ?></td>
					<td class="p-0 font-size-10 text-center"></td>
				</tr>
				<?php
			}
		?>
				<tr align="center">
					<td colspan="10" class="p-0 font-size-12"><b><?php echo trim(strtoupper($headofficertu)); ?></b></td>
				</tr>
				<tr align="center">
					<td colspan="10" class="p-0 font-size-11"><i><?php echo trim($headtitletu); ?></i></td>
				</tr>
			</tfoot>
		</table>
		<div class="d-flex justify-content-end">
			<label class="font-size-11">CC: Employee / DTR In-Charge / HR / OPAcc / COA</label>
		</div>
	</div>