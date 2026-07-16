<?php
// Initialize variables for results
$ip1 = isset($_POST['ip1']) ? trim($_POST['ip1']) : '';
$ip2 = isset($_POST['ip2']) ? trim($_POST['ip2']) : '';
$output1 = '';
$output2 = '';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Determine OS to use correct ping syntax (-c for Linux/Mac, -n for Windows)
    $is_windows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
    $flag = $is_windows ? '-n 4' : '-c 4';

    // Validate and sanitize IP 1
    if (!empty($ip1) && (filter_var($ip1, FILTER_VALIDATE_IP) || filter_var($ip1, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME))) {
        // escapeshellarg prevents command injection vulnerabilities
        $cmd1 = "ping $flag " . escapeshellarg($ip1);
        $output1 = shell_exec($cmd1);
    } else {
        $output1 = "Invalid IP Address or Hostname for Target 1.";
    }

    // Validate and sanitize IP 2
    if (!empty($ip2) && (filter_var($ip2, FILTER_VALIDATE_IP) || filter_var($ip2, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME))) {
        $cmd2 = "ping $flag " . escapeshellarg($ip2);
        $output2 = shell_exec($cmd2);
    } else {
        $output2 = "Invalid IP Address or Hostname for Target 2.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-IP Ping Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        pre { background-color: #212529; color: #0dfd05; padding: 15px; border-radius: 5px; font-family: monospace; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="mb-0">Dual IP Ping Utility</h2>
                </div>
                <div class="card-body p-4">
                    
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="ip1" class="form-label fw-bold">Target IP / Hostname 1</label>
                                <input type="text" class="form-control" id="ip1" name="ip1" value="<?php echo htmlspecialchars($ip1); ?>" placeholder="e.g., 8.8.8.8" required>
                            </div>
                            <div class="col-md-6">
                                <label for="ip2" class="form-label fw-bold">Target IP / Hostname 2</label>
                                <input type="text" class="form-control" id="ip2" name="ip2" value="<?php echo htmlspecialchars($ip2); ?>" placeholder="e.g., 1.1.1.1" required>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">Ping Both Targets</button>
                        </div>
                    </form>

                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-secondary">Results for: <?php echo htmlspecialchars($ip1); ?></h4>
                                <pre><?php echo htmlspecialchars($output1); ?></pre>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-secondary">Results for: <?php echo htmlspecialchars($ip2); ?></h4>
                                <pre><?php echo htmlspecialchars($output2); ?></pre>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>