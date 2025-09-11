<?php 

	// Load the routes
	require 'lib/routes.php';

	// Get the request URI
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

	// Remove the project folder name from the URI (if in subfolder)
	$base = '/beetpress'; // change this to your folder name
	$route = str_replace($base, '', $uri);
	$route = trim($route, '/');

	// Check if the route exists
	if (array_key_exists($route, $routes)) {
		require $routes[$route];
	} else {
		// Show 404 page
		http_response_code(404);
		require '404.html';
	}