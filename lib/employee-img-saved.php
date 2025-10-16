<?php

	try {

		if ( empty(trim($imgdata)) ) {
			echo "No image!";
		} elseif ( empty(trim($employeeidfinale)) ) {

		} else {
			$base64DataString = $imgdata;

			// extract image data from base64 data string
			$pattern = '/data:image\/(.+);base64,(.*)/';
			preg_match($pattern, $base64DataString, $matches);

			// image file extension
			$imageExtension = $matches[1];

			// base64-encoded image data
			$encodedImageData = $matches[2];

			// decode base64-encoded image data
			$decodedImageData = base64_decode($encodedImageData);

			// save image data as file
			file_put_contents("public/employeeID/{$employeeidfinale}.{$imageExtension}", $decodedImageData);

			echo '<div class="alert alert-primary alert-dismissible fade show m-1">';
				echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
				echo 'Image successfully saved. Image Filename: [<a href="public/employeeID/'.$employeeidfinale.'.'.$imageExtension.'" target="_blank">'.$employeeidfinale.'.'.$imageExtension.'</a>]<br>';
				echo '@ Filepath: public/employeeID';
			echo '</div>';
		}

	} catch(Exception $e) {

		$err = $e->getMessage();
		echo "Error: ".$err;
		die;

	}

?>