<?php

	require_once "env.php";

	session_start();
	// Clear session variables and destroy the session
	$_SESSION = array();
	session_destroy();

	// Perform a clean server-side redirect
	header("Location: " . $domainhome);
	exit();