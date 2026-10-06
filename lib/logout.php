<?php

	require_once "env.php";

	if ( empty($domainhome) ) {
		$domainhome = "/";
	}

	session_start();

	// 1. Unset all session variables
	$_SESSION = array();

	// 2. Delete the session cookie from the client browser
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(
			session_name(),
			'',
			time() - 42000,
			$params["path"],
			$params["domain"],
			$params["secure"],
			$params["httponly"]
		);
	}

	// 3. Destroy the server-side session data
	session_destroy();

	// 4. Clean server-side redirect
	header("Location: " . $domainhome);
	exit();