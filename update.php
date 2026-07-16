<?php
	// update.php

	// 1. Change directory to the project root
	// chdir('/var/www/html/my-project');

	// 2. Execute the pull command
	$output = shell_exec('git pull');

	// 3. Display the result
	echo "<pre>$output</pre>";
?>