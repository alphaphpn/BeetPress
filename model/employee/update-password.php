<?php
// Include your database connection (adjust path if necessary)
if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

try {
    $db = new myDatabase();
    $conn = $db->getConnection();

    $employeeid = isset($_POST['employeeid']) ? trim($_POST['employeeid']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

    if (!empty($employeeid) && !empty($new_password)) {
        // Hash the new password using MD5 to match your existing login system
        $hashed_password = md5($new_password);
        
        $query = "UPDATE employee_tbl SET pinword = :pinword WHERE emp_idcode = :employeeid";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':pinword', $hashed_password);
        $stmt->bindParam(':employeeid', $employeeid);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error updating password";
        }
    } else {
        echo "Invalid input data";
    }
} catch (PDOException $error) {
    echo "Database Error: " . $error->getMessage();
}
?>