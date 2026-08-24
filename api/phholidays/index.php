<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

if (!isset($_GET['token']) || trim((string) $_GET['token']) === '') {
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "Missing or empty 'token' parameter."));
    exit;
}

if (trim((string) $_GET['token']) !== '3f8a92c1b5e047d6af29103e7c654d82') {
    http_response_code(401);
    echo json_encode(array("status" => "error", "message" => "API Key is not valid."));
    exit;
}

try {
    require_once '../../lib/env.php';

    $cnn = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $uname, $pw);
    $cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conditions = array();
    $params     = array();

    $year = trim($_GET['year'] ?? '');
    if ($year !== '') {
        if (!preg_match('/^\d{4}$/', $year)) {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Invalid 'year' parameter. Must be a 4-digit year."));
            exit;
        }
        $conditions[] = "holiday_year = :year";
        $params[':year'] = $year;
    }

    $month = trim($_GET['month'] ?? '');
    if ($month !== '') {
        if (!preg_match('/^\d{1,2}$/', $month) || (int)$month < 1 || (int)$month > 12) {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Invalid 'month' parameter. Must be a number between 1 and 12."));
            exit;
        }
        $conditions[] = "holiday_mno = :month";
        $params[':month'] = (int)$month;
    }

    $agencyCode = trim($_GET['agency_code'] ?? '');
    if ($agencyCode !== '') {
        $conditions[] = "agency_code = :agency_code";
        $params[':agency_code'] = $agencyCode;
    }

    $sql = "SELECT agency_code, agency_name, holiday_name,
                   holiday_month, holiday_mthree, holiday_mno, holiday_mnumbr,
                   holiday_day, holiday_dayno, holiday_year
            FROM holidays_tbl";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY holiday_year ASC, holiday_mno ASC, holiday_dayno ASC";

    $stmt = $cnn->prepare($sql);
    $stmt->execute($params);
    $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(array(
        "status" => "success",
        "total"  => count($holidays),
        "data"   => $holidays
    ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array("status" => "error", "message" => $e->getMessage()));
}
