<?php

	echo trim(date("d"));
	
	echo trim("<br>");
	echo trim(date("D"));

	echo trim("<br>");
	echo trim(date("F"));

	echo trim("<br>");
	echo trim(date("l"));

	echo trim("<br>");
	echo trim(date("L"));

	echo trim("<br>");
	echo trim(number_format(date("d")));

	echo trim("<br>");
	date_default_timezone_set('Asia/Manila');
	echo trim(date("h:i"));

	echo trim("<br>");
	echo trim(number_format(date("hi")));

	echo trim("<br>");
	echo trim(date("h:i"));

	echo trim("<br>");
	echo trim(date("m"));

	echo trim("<br>");
	echo trim(number_format(date("m")));