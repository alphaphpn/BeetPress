<?php
// model/employee/update-profile-details-employee.php

if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

try {
    $db = new myDatabase();
    $conn = $db->getConnection();

    // Catch the data sent from the Employee Dashboard JS
    $employeeid       = isset($_POST['employeeid'])       ? trim($_POST['employeeid'])       : '';
    $address          = isset($_POST['iceAddress'])       ? trim($_POST['iceAddress'])       : '';
    $ice_name         = isset($_POST['ice_name'])         ? trim($_POST['ice_name'])         : '';
    $ice_relationship = isset($_POST['ice_relationship']) ? trim($_POST['ice_relationship']) : '';
    $ice_number       = isset($_POST['ice_number'])       ? trim($_POST['ice_number'])       : '';

    if (!empty($employeeid)) {
        
        // 100% SAFE QUERY: It ONLY touches address and ICE fields. 
        // It is physically impossible for this to empty the Name, Office, or Designation!
        $query = "UPDATE employee_tbl SET 
                    address                    = :address,
                    incaseofemergency_name     = :ice_name,
                    incaseofemergency_relation = :ice_relationship,
                    incaseofemergency_contact  = :ice_number
                  WHERE emp_idcode = :employeeid";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':address',          $address);
        $stmt->bindParam(':ice_name',         $ice_name);
        $stmt->bindParam(':ice_relationship', $ice_relationship);
        $stmt->bindParam(':ice_number',       $ice_number);
        $stmt->bindParam(':employeeid',       $employeeid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating record']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Employee ID']);
    }

} catch (PDOException $error) {
    echo json_encode(['status' => 'error', 'message' => $error->getMessage()]);
}
?>