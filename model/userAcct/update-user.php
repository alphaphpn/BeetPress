<?php
// model/userAcct/update-user.php

if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

try {
    $db = new myDatabase();
    $conn = $db->getConnection();

    $authid   = isset($_POST['authid']) ? trim($_POST['authid']) : '';
    $position = isset($_POST['position']) ? trim($_POST['position']) : '';
    $level    = isset($_POST['level']) ? trim($_POST['level']) : '';
    $verified = isset($_POST['verified']) ? trim($_POST['verified']) : 0;
    $status   = isset($_POST['status']) ? trim($_POST['status']) : 0;

    if (!empty($authid)) {
        
        // 100% Safe Query: Only updates the specific user account columns
        $query = "UPDATE user_tbl SET 
                    uposition = :position,
                    ulevel = :level,
                    verified = :verified,
                    ustat = :status
                  WHERE authid = :authid";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':verified', $verified);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':authid', $authid);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error updating user account.";
        }
    } else {
        echo "Invalid Authentication ID.";
    }

} catch (PDOException $error) {
    echo "Database Error: " . $error->getMessage();
}
?>