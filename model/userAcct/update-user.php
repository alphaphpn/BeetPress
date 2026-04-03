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
    $authid   = isset($_POST['authid'])   ? trim($_POST['authid'])   : '';
    $position = isset($_POST['position']) ? trim($_POST['position']) : '';
    $level    = isset($_POST['level'])    ? trim($_POST['level'])    : '';
    $verified = isset($_POST['verified']) ? trim($_POST['verified']) : 0;
    $status   = isset($_POST['status'])   ? trim($_POST['status'])   : 0;
    $newpass  = isset($_POST['newpass'])  ? trim($_POST['newpass'])  : '';

    if (!empty($authid)) {

        if (!empty($newpass)) {
            $hashedPass = md5($newpass);
            $query = "UPDATE user_tbl SET 
                        uposition = :position,
                        ulevel    = :level,
                        verified  = :verified,
                        ustat     = :status,
                        pword     = :pword
                      WHERE authid = :authid";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':pword', $hashedPass);
        } else {
            $query = "UPDATE user_tbl SET 
                        uposition = :position,
                        ulevel    = :level,
                        verified  = :verified,
                        ustat     = :status
                      WHERE authid = :authid";
            $stmt = $conn->prepare($query);
        }

        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':level',    $level);
        $stmt->bindParam(':verified', $verified);
        $stmt->bindParam(':status',   $status);
        $stmt->bindParam(':authid',   $authid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating user account.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Authentication ID.']);
    }
} catch (PDOException $error) {
    echo json_encode(['status' => 'error', 'message' => $error->getMessage()]);
}
?>