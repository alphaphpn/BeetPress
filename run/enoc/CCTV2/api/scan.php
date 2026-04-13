<?php
/**
 * Network Scanner — discovers devices on the local subnet via ARP table,
 * then probes camera-related ports and reads HTTP headers for brand detection.
 */
set_time_limit(60);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$IS_WIN = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

// ── 1. Detect local subnet ────────────────────────────────────────────────────
function getLocalSubnet() {
    global $IS_WIN;
    if ($IS_WIN) {
        exec('ipconfig', $lines);
        foreach ($lines as $line) {
            if (preg_match('/IPv4.*?:\s*(\d+\.\d+\.\d+)\.\d+/', $line, $m)) {
                if (!preg_match('/^(127|169)/', $m[1])) return $m[1];
            }
        }
    } else {
        exec('ip route | grep -v default', $lines);
        foreach ($lines as $line) {
            if (preg_match('/(\d+\.\d+\.\d+)\.\d+\/\d+/', $line, $m)) {
                if (!preg_match('/^127/', $m[1])) return $m[1];
            }
        }
    }
    return '192.168.1';
}

// ── 2. ARP table — instant list of recently-seen devices ─────────────────────
function getArpDevices() {
    global $IS_WIN;
    $hosts = [];
    exec('arp -a 2>&1', $lines);
    foreach ($lines as $line) {
        // Match IP + MAC
        $macPattern = $IS_WIN
            ? '/(\d{1,3}(?:\.\d{1,3}){3})\s+([\da-fA-F]{2}(?:-[\da-fA-F]{2}){5})/'
            : '/(\d{1,3}(?:\.\d{1,3}){3})\s+([\da-fA-F]{2}(?::[\da-fA-F]{2}){5})/';
        if (preg_match($macPattern, $line, $m)) {
            $ip  = $m[1];
            $mac = strtoupper(str_replace('-', ':', $m[2]));
            if (preg_match('/^(0\.0\.0\.0|255\.|169\.254\.)/', $ip)) continue;
            $hosts[$ip] = ['ip' => $ip, 'mac' => $mac];
        }
    }
    return array_values($hosts);
}

// ── 3. Fast parallel port check via stream_select ────────────────────────────
function checkPorts($ip, $ports = [80, 8080, 443, 554, 8554, 8000, 9000]) {
    $sockets   = [];
    $portMap   = [];
    $openPorts = [];

    foreach ($ports as $port) {
        $s = @stream_socket_client(
            "tcp://$ip:$port", $errno, $errstr, 0,
            STREAM_CLIENT_ASYNC_CONNECT | STREAM_CLIENT_CONNECT
        );
        if ($s) {
            stream_set_blocking($s, false);
            $id         = (int)$s;
            $sockets[]  = $s;
            $portMap[$id] = $port;
        }
    }

    if (empty($sockets)) return $openPorts;

    $read = $except = null;
    $write = $sockets;
    @stream_select($read, $write, $except, 1, 0);   // wait up to 1 s

    foreach ($write as $s) {
        $openPorts[] = $portMap[(int)$s];
    }
    foreach ($sockets as $s) { @fclose($s); }

    return $openPorts;
}

// ── 4. HTTP brand identification ──────────────────────────────────────────────
function identifyBrand($ip, $port) {
    $ctx = stream_context_create([
        'http' => ['timeout' => 2, 'ignore_errors' => true, 'follow_location' => false],
        'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
    ]);
    $proto   = ($port === 443) ? 'https' : 'http';
    $headers = @get_headers("$proto://$ip:$port/", true, $ctx);
    if (!$headers) return null;
    $haystack = strtolower(implode(' ', (array)$headers));
    $brands   = [
        'hikvision' => 'Hikvision', 'hikv'      => 'Hikvision',
        'dahua'     => 'Dahua',     'dh-'       => 'Dahua',
        'axis'      => 'Axis',      'reolink'   => 'Reolink',
        'uniview'   => 'Uniview',   'hanwha'    => 'Hanwha',
        'bosch'     => 'Bosch',     'pelco'     => 'Pelco',
        'amcrest'   => 'Amcrest',   'foscam'    => 'Foscam',
        'tp-link'   => 'TP-Link',   'synology'  => 'Synology',
    ];
    foreach ($brands as $key => $name) {
        if (strpos($haystack, $key) !== false) return $name;
    }
    return null;
}

// ── 5. Classify device ────────────────────────────────────────────────────────
function classifyDevice($openPorts, $brand) {
    if ($brand) return 'camera';
    $rtsp = array_intersect([554, 8554], $openPorts);
    if (!empty($rtsp)) return 'camera';
    $http = array_intersect([80, 8080, 443], $openPorts);
    if (!empty($http)) return 'device';
    return 'unknown';
}

// ── 6. MAC vendor prefix (first 3 bytes) ─────────────────────────────────────
function macVendorHint($mac) {
    $oui = substr(str_replace(':', '', $mac), 0, 6);
    $known = [
        'B0C55A' => 'Hikvision', 'C0E534' => 'Hikvision', 'E04F43' => 'Hikvision',
        '103D86' => 'Dahua',     '4C1183' => 'Dahua',
        '00408C' => 'Axis',      'ACCC8E' => 'Axis',
        '6C063C' => 'Reolink',
        '3C5AB4' => 'Raspberry Pi', '28CD4C' => 'Raspberry Pi',
        'B827EB' => 'Raspberry Pi',
    ];
    return $known[strtoupper($oui)] ?? null;
}

// ── 7. Main scan ──────────────────────────────────────────────────────────────
$subnet    = getLocalSubnet();
$arpHosts  = getArpDevices();
$results   = [];

foreach ($arpHosts as $host) {
    $ip        = $host['ip'];
    $mac       = $host['mac'];
    $openPorts = checkPorts($ip);

    // Try to identify brand via HTTP headers on first open web port
    $brand = macVendorHint($mac);
    $httpPorts = array_intersect([80, 8080, 443], $openPorts);
    if (!$brand && !empty($httpPorts)) {
        $brand = identifyBrand($ip, reset($httpPorts));
    }

    // Hostname
    $hostname = @gethostbyaddr($ip);
    $hostname = ($hostname !== $ip) ? $hostname : null;

    $type = classifyDevice($openPorts, $brand);

    $results[] = [
        'ip'       => $ip,
        'mac'      => $mac,
        'hostname' => $hostname,
        'ports'    => $openPorts,
        'type'     => $type,       // 'camera' | 'device' | 'unknown'
        'brand'    => $brand,
    ];
}

// Sort: cameras first
usort($results, fn($a, $b) => ($b['type'] === 'camera') - ($a['type'] === 'camera'));

echo json_encode(['ok' => true, 'subnet' => $subnet, 'hosts' => $results]);
