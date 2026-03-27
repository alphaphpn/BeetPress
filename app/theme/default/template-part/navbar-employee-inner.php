
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>employee-info">Employee Info</a></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>employee-change-pin">Change PIN</a></li>
			<?php 
				if ( !isset($_SESSION["d2s8wu_ustat"]) && !isset($_SESSION["d2s8wu_verified"]) && !isset($_SESSION['d2s8wu_xdel']) && !isset($_SESSION['d2s8wu_ulevel']) ) {
					?>
						<li><hr class="dropdown-divider"></li>
						<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>login"><i class='fas fa-unlock-alt'></i> Profile Login</a></li>
					<?php
				}
			?>
			<li><hr class="dropdown-divider"></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>attendance">Work Attendance</a></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>dtr">DTR</a></li>
			<li><hr class="dropdown-divider"></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#" title="End of the Day Report">EOD Report</a></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#" title="Daily Accomplishment Report">Accomplishment</a></li>
			<li><hr class="dropdown-divider"></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#">Personal Info. (PDS)</a></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>employee-id">Employee ID</a></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#">Work History</a></li>