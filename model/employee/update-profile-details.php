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
    $fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
    $nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
    $designation = isset($_POST['designation']) ? trim($_POST['designation']) : '';
    $officename = isset($_POST['officename']) ? trim($_POST['officename']) : '';
    
    // FIXED: Changed 'iceAddress' to 'address' to match the Javascript FormData
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    
    $ice_name = isset($_POST['ice_name']) ? trim($_POST['ice_name']) : '';
    $ice_relationship = isset($_POST['ice_relationship']) ? trim($_POST['ice_relationship']) : '';
    $ice_number = isset($_POST['ice_number']) ? trim($_POST['ice_number']) : '';

    if (!empty($employeeid)) {
        $query = "UPDATE employee_tbl SET 
                    emp_name_forid = :fullname,
                    nickname = :nickname,
                    designationforid = :designation,
                    officename_forid = :officename,
                    address = :address,
                    incaseofemergency_name = :ice_name,
                    incaseofemergency_relation = :ice_relationship,
                    incaseofemergency_contact = :ice_number
                  WHERE emp_idcode = :employeeid";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':designation', $designation);
        $stmt->bindParam(':officename', $officename);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':ice_name', $ice_name);
        $stmt->bindParam(':ice_relationship', $ice_relationship);
        $stmt->bindParam(':ice_number', $ice_number);
        $stmt->bindParam(':employeeid', $employeeid);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error updating record";
        }
    } else {
        echo "Invalid Employee ID";
    }
} catch (PDOException $error) {
    echo "Database Error: " . $error->getMessage();
}
?>