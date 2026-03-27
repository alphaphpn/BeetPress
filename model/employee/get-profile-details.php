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

    if (!empty($employeeid)) {
        // Pulled pinword out of the SELECT and fixed the address alias
        $query = "SELECT emp_name_forid,
                         nickname, 
                         designationforid,
                         officename_forid,
                         address, 
                         incaseofemergency_name as ice_name, 
                         incaseofemergency_relation as ice_relationship, 
                         incaseofemergency_contact as ice_number
                  FROM employee_tbl 
                  WHERE emp_idcode = :employeeid LIMIT 1";
                  
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':employeeid', $employeeid);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode(['error' => 'No employee ID provided']);
    }
} catch (PDOException $error) {
    echo json_encode(['error' => $error->getMessage()]);
}
?>