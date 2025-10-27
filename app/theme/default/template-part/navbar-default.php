
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" title="User"><i class="fas fa-user fa-fw"></i></a>
		<ul class="dropdown-menu">
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>login"><i class='fas fa-unlock-alt'></i> Login</a></li>
			<li><a class="dropdown-item" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>attendance-auth"><i class='fas fa-user-clock'></i> Work Attendance</a></li>
		</ul>
	</li>

	<li class="nav-item">
		<a class="nav-link" href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>signup" title="Register"><i class='fas fa-user-plus'></i></a>
	</li>