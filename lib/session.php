<?php 

	if ( empty($_SESSION["uid"]) || empty($_SESSION["uname"]) || empty($_SESSION["verified"]) || empty($_SESSION["ustat"]) || empty($_SESSION["ulevel"]) || empty($_SESSION["uposition"]) ) {
		echo '<script>alert("Access denied! Only Authorized account is allowed.");window.open("'.$domainhome.'/home","_self");</script>';
		exit;
	}
	
?>