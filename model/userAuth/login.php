<?php 

	try {
		if (isset($_POST["btnLogin"])) {
			if (empty($_POST["zipcode"]) || empty($_POST["username"]) || empty($_POST["password"])) {
				echo '<div class="alert alert-danger alert-dismissible fade show">';
					echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
					echo 'Please enter User and Password.';
				echo '</div>';
			} else {
				require_once "../../model/userAuth/index.php";

				$uAthe=new authUser();

				$xzipcode = $_POST['zipcode'];
				$xusername = $_POST['username'];
				$xpword = $_POST['password'];

				$uAthe->authUserTown($xusername,$xpword,$xzipcode);
			}
		}
	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}

?>