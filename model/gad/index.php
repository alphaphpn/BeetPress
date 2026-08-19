<?php

require_once "lib/cnn.php";

class gadHealthData extends myDatabase {
    private $seedRows = [
        ['discharged', 'Pneumonia', 618, 531], ['discharged', 'Acute Gastroenteritis', 290, 304], ['discharged', 'UTI', 234, 342], ['discharged', 'Acute Gastritis', 148, 213], ['discharged', 'Dengue Fever', 160, 156], ['discharged', 'Infectious Diarrhea', 89, 90], ['discharged', 'Peptic Ulcer', 45, 70], ['discharged', 'Hypertension (1,2,3)', 39, 57], ['discharged', 'Acute Bronchitis', 24, 32], ['discharged', 'Allergic Bronchitis', 9, 9],
        ['emergency', 'Pneumonia', 453, 460], ['emergency', 'Acute Gastroenteritis', 375, 374], ['emergency', 'UTI', 330, 333], ['emergency', 'Acute Gastritis', 295, 281], ['emergency', 'Work Related Injury', 300, 149], ['emergency', 'Vehicular Accident', 205, 169], ['emergency', 'Dengue Fever', 138, 134], ['emergency', 'Hypertension (1,2,3)', 85, 80], ['emergency', 'Acute Bronchitis', 83, 82], ['emergency', 'Circumcision', 62, 0],
        ['opd', 'Pneumonia', 480, 474], ['opd', 'Animal Bite', 460, 453], ['opd', 'UTI', 410, 398], ['opd', 'Work Related Injury', 305, 188], ['opd', 'Hypertension (1,2,3)', 135, 118], ['opd', 'Acute Gastritis', 130, 108], ['opd', 'Acute Bronchitis', 73, 71], ['opd', 'Acute Gastroenteritis', 68, 65], ['opd', 'Cellulitis', 73, 71], ['opd', 'Diabetes Mellitus', 25, 24]
    ];

    public function ensureTableAndSeed() {
        $cnn = $this->getConnection();
        $cnn->exec("CREATE TABLE IF NOT EXISTS gad_health_data_tbl (
            gad_health_data_autoid INT AUTO_INCREMENT PRIMARY KEY,
            calendar_year SMALLINT NOT NULL,
            data_category VARCHAR(20) NOT NULL,
            diagnosis VARCHAR(255) NOT NULL,
            male_count INT UNSIGNED NOT NULL DEFAULT 0,
            female_count INT UNSIGNED NOT NULL DEFAULT 0,
            xdel TINYINT(1) NOT NULL DEFAULT 0,
            createdby VARCHAR(100) NOT NULL DEFAULT 'system',
            modifiedby VARCHAR(100) NOT NULL DEFAULT 'system',
            modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY uq_gad_health_data (calendar_year, data_category, diagnosis)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        $count = (int)$cnn->query("SELECT COUNT(*) FROM gad_health_data_tbl WHERE calendar_year = 2024")->fetchColumn();
        if ($count === 0) {
            $insert = $cnn->prepare("INSERT INTO gad_health_data_tbl (calendar_year, data_category, diagnosis, male_count, female_count, createdby, modifiedby) VALUES (2024, ?, ?, ?, ?, 'system', 'system')");
            foreach ($this->seedRows as $row) {
                $insert->execute($row);
            }
        }
    }

    public function getYears() {
        $this->ensureTableAndSeed();
        $stmt = $this->cnn->query("SELECT DISTINCT calendar_year FROM gad_health_data_tbl WHERE xdel = 0 ORDER BY calendar_year DESC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getChartData($year) {
        $this->ensureTableAndSeed();
        $stmt = $this->cnn->prepare("SELECT data_category, diagnosis, male_count, female_count FROM gad_health_data_tbl WHERE calendar_year = ? AND xdel = 0 ORDER BY data_category, gad_health_data_autoid");
        $stmt->execute([(int)$year]);
        $data = ['discharged' => ['labels' => [], 'male' => [], 'female' => []], 'emergency' => ['labels' => [], 'male' => [], 'female' => []], 'opd' => ['labels' => [], 'male' => [], 'female' => []]];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (isset($data[$row['data_category']])) {
                $data[$row['data_category']]['labels'][] = $row['diagnosis'];
                $data[$row['data_category']]['male'][] = (int)$row['male_count'];
                $data[$row['data_category']]['female'][] = (int)$row['female_count'];
            }
        }
        return $data;
    }

    public function getRecords() {
        $this->ensureTableAndSeed();
        return $this->cnn->query("SELECT * FROM gad_health_data_tbl WHERE xdel = 0 ORDER BY calendar_year DESC, data_category, gad_health_data_autoid")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($id, $year, $category, $diagnosis, $male, $female, $user) {
        $this->ensureTableAndSeed();
        if ($id) {
            $stmt = $this->cnn->prepare("UPDATE gad_health_data_tbl SET calendar_year=?, data_category=?, diagnosis=?, male_count=?, female_count=?, modifiedby=? WHERE gad_health_data_autoid=? AND xdel=0");
            return $stmt->execute([(int)$year, $category, $diagnosis, (int)$male, (int)$female, $user, (int)$id]);
        }
        $stmt = $this->cnn->prepare("INSERT INTO gad_health_data_tbl (calendar_year, data_category, diagnosis, male_count, female_count, createdby, modifiedby) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([(int)$year, $category, $diagnosis, (int)$male, (int)$female, $user, $user]);
    }

    public function delete($id, $user) {
        $this->ensureTableAndSeed();
        $stmt = $this->cnn->prepare("UPDATE gad_health_data_tbl SET xdel=1, modifiedby=? WHERE gad_health_data_autoid=?");
        return $stmt->execute([$user, (int)$id]);
    }
}

?>
