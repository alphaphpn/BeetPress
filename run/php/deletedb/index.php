<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Delete DB</title>
</head>
<body>
	<?php
		$conn = new mysqli("localhost", "root", "");
		$sql = "DROP DATABASE beetpress_db";
		if ($conn->query($sql) === TRUE) {
		    echo "Database deleted successfully";
		} else {
		    echo "Error: " . $conn->error;
		}
		$conn->close();
	?>
</body>
</html>