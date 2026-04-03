<?php
ob_start();
if (file_exists("../../lib/cnn.php")) {
    require_once "../../lib/cnn.php";
} elseif (file_exists("../../../lib/cnn.php")) {
    require_once "../../../lib/cnn.php";
}

header('Content-Type: application/json');
ob_clean();

try {
    $db   = new myDatabase();
    $conn = $db->getConnection();

    $employeeid = isset($_POST['employeeid']) ? trim($_POST['employeeid']) : '';
    $forid      = isset($_POST['forid'])      ? intval($_POST['forid'])    : 0;

    if (empty($employeeid)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing employee ID']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE employee_tbl SET for_id = :forid WHERE emp_idcode = :employeeid");
    $stmt->bindParam(':forid',      $forid,      PDO::PARAM_INT);
    $stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $affected = $stmt->rowCount();
        echo json_encode(['status' => 'success', 'forid' => $forid, 'rows_affected' => $affected]);
    } else {
        $err = $stmt->errorInfo();
        echo json_encode(['status' => 'error', 'message' => $err[2]]);
    }

} catch (PDOException $error) {
    echo json_encode(['status' => 'error', 'message' => $error->getMessage()]);
}
?>