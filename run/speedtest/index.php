<?php
// Handle AJAX requests from the frontend to keep the UI seamless
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    // 1. Get Server Public IP
    if ($_GET['action'] === 'get_ip') {
        $ip = @file_get_contents('https://api64.ipify.org'); 
        if (!$ip) {
            $ip = @file_get_contents('https://ifconfig.me/ip');
        }
        echo json_encode(['ip' => $ip ? trim($ip) : 'Unknown IP']);
        exit;
    }

    // 2. Run Server Download Speed Test
    if ($_GET['action'] === 'test_speed') {
        // A highly reliable, lightweight 5MB test file
        $test_file_url = 'https://speed.hetzner.de/5MB.bin'; 
        
        $start_time = microtime(true);
        $file_data = null;
        $size_bytes = 0;

        // METHOD A: Try cURL if available
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $test_file_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20); 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignore local SSL issues if any
            
            $file_data = curl_exec($ch);
            $size_bytes = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($file_data === false) {
                echo json_encode(['error' => 'cURL Error: ' . $error]);
                exit;
            }
        } 
        // METHOD B: Fallback to file_get_contents if cURL is missing
        else if (ini_get('allow_url_fopen')) {
            $context = stream_context_create([
                'http' => ['timeout' => 20, 'follow_location' => 1],
                'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
            ]);
            $file_data = @file_get_contents($test_file_url, false, $context);
            if ($file_data !== false) {
                $size_bytes = strlen($file_data);
            } else {
                echo json_encode(['error' => 'Fallback download failed. allow_url_fopen restrictions may apply.']);
                exit;
            }
        } else {
            echo json_encode(['error' => 'Both cURL and allow_url_fopen are disabled on this server.']);
            exit;
        }
        
        $end_time = microtime(true);

        if ($size_bytes === 0) {
            echo json_encode(['error' => 'Downloaded file size is 0 bytes.']);
            exit;
        }

        // Calculate Speeds
        $duration_seconds = max(($end_time - $start_time), 0.001); // Avoid division by zero
        $size_megabits = ($size_bytes * 8) / 1000000;
        $speed_mbps = $size_megabits / $duration_seconds;

        echo json_encode([
            'speed_mbps' => round($speed_mbps, 2),
            'duration' => round($duration_seconds, 2),
            'size_mb' => round($size_bytes / 1024 / 1024, 2)
        ]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Server Network Diagnostics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .speed-card { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .metric-value { font-size: 3rem; font-weight: 800; color: #198754; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            
            <div class="text-center mb-4">
                <i class="fa-solid fa-server fa-3x text-dark mb-2"></i>
                <h3 class="fw-bold">Server Network Speed</h3>
                <p class="text-muted small">Testing directly from the backend hosting server</p>
            </div>

            <div class="card speed-card p-4 text-center mb-3">
                <div id="status-text" class="text-muted small mb-3">Click below to start diagnostics</div>
                
                <div class="my-3">
                    <div class="metric-value" id="speed-display">- -</div>
                    <div class="text-uppercase tracking-wider text-muted small fw-bold">Download Speed (Mbps)</div>
                </div>

                <div class="progress mb-4 d-none" id="progress-container" style="height: 6px;">
                    <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 0%"></div>
                </div>

                <button id="run-btn" class="btn btn-dark btn-lg w-100 fw-bold py-2">
                    <i class="fa-solid fa-play me-2"></i> Run Server Test
                </button>
            </div>

            <div class="card speed-card p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <span class="text-muted small"><i class="fa-solid fa-globe me-2"></i>Server Public IP:</span>
                    <span id="ip-display" class="fw-bold text-secondary small">Not Loaded</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small"><i class="fa-solid fa-database me-2"></i>Test Sample Size:</span>
                    <span id="meta-display" class="text-secondary small">-</span>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('run-btn').addEventListener('click', async function() {
    const btn = this;
    const speedDisplay = document.getElementById('speed-display');
    const ipDisplay = document.getElementById('ip-display');
    const metaDisplay = document.getElementById('meta-display');
    const statusText = document.getElementById('status-text');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');

    // UI Reset State
    btn.disabled = true;
    progressContainer.classList.remove('d-none');
    progressBar.style.width = '15%';
    statusText.innerHTML = "Fetching Server Public IP...";
    speedDisplay.textContent = "- -";

    try {
        // Step 1: Fetch Server IP
        const ipResponse = await fetch('?action=get_ip');
        const ipData = await ipResponse.json();
        ipDisplay.textContent = ipData.ip;
        
        // Step 2: Fetch Speed Test
        progressBar.style.width = '50%';
        statusText.innerHTML = "Downloading test payload to server memory...";
        
        const speedResponse = await fetch('?action=test_speed');
        const speedData = await speedResponse.json();

        if (speedData.error) {
            throw new Error(speedData.error);
        }

        // Step 3: Display results
        progressBar.style.width = '100%';
        statusText.innerHTML = `<span class="text-success"><i class="fa-solid fa-check-circle"></i> Complete!</span>`;
        speedDisplay.textContent = speedData.speed_mbps;
        metaDisplay.textContent = `${speedData.size_mb} MB fetched in ${speedData.duration}s`;

    } catch (error) {
        statusText.innerHTML = `<span class="text-danger"><i class="fa-solid fa-triangle-exclamation"></i> ${error.message}</span>`;
        console.error(error);
    } finally {
        btn.disabled = false;
    }
});
</script>
</body>
</html>