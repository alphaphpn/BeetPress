<?php
// model/employee/toggle-status.php

if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

try {
    $db = new myDatabase();
    $conn = $db->getConnection();

    $employeeid = isset($_POST['employeeid']) ? trim($_POST['employeeid']) : '';
    $actived    = isset($_POST['actived']) ? trim($_POST['actived']) : 0;

    if (!empty($employeeid)) {
        // Updates the "actived" column with 1 or 0
        $query = "UPDATE employee_tbl SET activated = :actived WHERE emp_idcode = :employeeid";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':actived', $actived);
        $stmt->bindParam(':employeeid', $employeeid);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error updating status";
        }
    } else {
        echo "Invalid Employee ID";
    }

} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
}
?>