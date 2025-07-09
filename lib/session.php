<?php

	if (empty($_SESSION["username"]) || empty($_SESSION["ulevel"]) || empty($_SESSION["uposition"]) || empty($_SESSION["xtown"]) || empty($_SESSION["distno"]) || empty($_SESSION["gcodezip"])) {
		header("location:../auth");
	} else {
		$curr_username			= $_SESSION['username'];
		$curr_ulevel		= $_SESSION["ulevel"];
		$curr_uposition		= $_SESSION["uposition"];

		$curr_town			= $_SESSION['xtown'];
		$curr_distno		= $_SESSION["distno"];
		$curr_gcodezip		= $_SESSION["gcodezip"];
	}

?>