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

    if (empty($employeeid)) {
        echo json_encode(['forid' => 0]);
        exit;
    }

    $stmt = $conn->prepare("SELECT for_id FROM employee_tbl WHERE emp_idcode = :employeeid LIMIT 1");
    $stmt->bindParam(':employeeid', $employeeid, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['forid' => isset($row['for_id']) ? intval($row['for_id']) : 0]);

} catch (PDOException $error) {
    echo json_encode(['forid' => 0, 'error' => $error->getMessage()]);
}
?>