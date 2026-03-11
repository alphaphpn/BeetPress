<?php

    // Fetch the POST data sent from the JavaScript fetch request
    $imgdata = isset($_POST['imgdata']) ? $_POST['imgdata'] : '';
    $employeeidfinale = isset($_POST['employeeidfinale']) ? $_POST['employeeidfinale'] : '';

	try {

		if ( empty(trim($imgdata)) ) {
			echo "No image!";
		} elseif ( empty(trim($employeeidfinale)) ) {
            echo "No Employee ID!";
		} else {
			$base64DataString = $imgdata;

			// extract image data from base64 data string
			$pattern = '/data:image\/(.+);base64,(.*)/';
			preg_match($pattern, $base64DataString, $matches);

			// image file extension (this will be png)
			$imageExtension = $matches[1];

			// base64-encoded image data
			$encodedImageData = $matches[2];

			// decode base64-encoded image data
			$decodedImageData = base64_decode($encodedImageData);

			// save image data as file to the new employee_sign directory
            // Uses ../ to go up one level from 'lib' to reach 'public'
			file_put_contents("../public/employee_sign/{$employeeidfinale}.{$imageExtension}", $decodedImageData);

			echo '<div class="alert alert-primary alert-dismissible fade show m-1">';
				echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
				echo 'Signature successfully saved. Image Filename: [<a href="public/employee_sign/'.$employeeidfinale.'.'.$imageExtension.'" target="_blank">'.$employeeidfinale.'.'.$imageExtension.'</a>]<br>';
				echo '@ Filepath: public/employee_sign';
			echo '</div>';
		}

	} catch(Exception $e) {

		$err = $e->getMessage();
		echo "Error: ".$err;
		die;

	}

?>