// ─── Browser-only Network Scanner ────────────────────────────────────────────
// Uses WebRTC to detect local IP, then probes HTTP ports with fetch(no-cors).
// No server / PHP required.

const BrowserScan = (() => {

  // ── 1. Get local IP via WebRTC ──────────────────────────────────────────────
  function getLocalIP() {
    return new Promise((resolve) => {
      const found = new Set();
      let done = false;

      const finish = (ip) => {
        if (done) return;
        done = true;
        resolve(ip || null);
      };

      try {
        const pc = new RTCPeerConnection({ iceServers: [] });
        pc.createDataChannel("");
        pc.createOffer().then(o => pc.setLocalDescription(o)).catch(() => finish(null));

        pc.onicecandidate = (e) => {
          if (!e.candidate) { finish([...found][0] || null); return; }
          const m = /(\d{1,3}(?:\.\d{1,3}){3})/.exec(e.candidate.candidate);
          if (m && !m[1].startsWith("127.") && !m[1].startsWith("169.254.")) {
            found.add(m[1]);
          }
        };

        // Fallback timeout
        setTimeout(() => finish([...found][0] || null), 3000);
      } catch (e) {
        finish(null);
      }
    });
  }

  // ── 2. Probe a single IP:port via fetch(no-cors) ───────────────────────────
  // Resolves true  → server responded (host is alive on that port)
  // Resolves false → timeout or connection refused
  function probeHTTP(ip, port = 80, timeoutMs = 1200) {
    return new Promise((resolve) => {
      const ctrl = new AbortController();
      const timer = setTimeout(() => { ctrl.abort(); resolve(false); }, timeoutMs);

      fetch(`http://${ip}:${port}/`, { mode: "no-cors", signal: ctrl.signal })
        .then(() => { clearTimeout(timer); resolve(true); })
        .catch((e) => {
          clearTimeout(timer);
          // TypeError = network failure (unreachable), AbortError = timeout
          // Any other error (CORS, etc.) means the server DID respond
          resolve(e.name !== "TypeError" && e.name !== "AbortError");
        });
    });
  }

  // ── 3. Probe common camera-related HTTP ports ──────────────────────────────
  async function probeAllPorts(ip) {
    const ports = [80, 8080, 8000, 443];
    const results = await Promise.all(ports.map(p => probeHTTP(ip, p, 1000).then(ok => ok ? p : null)));
    return results.filter(Boolean);
  }

  // ── 4. Guess device type from open ports ───────────────────────────────────
  function classify(openPorts) {
    if (openPorts.includes(80) || openPorts.includes(8080)) return "device";
    return "unknown";
  }

  // ── 5. Scan entire subnet in parallel batches ──────────────────────────────
  async function scanSubnet(subnet, onProgress) {
    const BATCH = 25; // concurrent requests per round
    const ips   = Array.from({ length: 254 }, (_, i) => `${subnet}.${i + 1}`);
    const alive = [];

    for (let i = 0; i < ips.length; i += BATCH) {
      const batch = ips.slice(i, i + BATCH);

      const results = await Promise.all(
        batch.map(ip =>
          probeHTTP(ip, 80, 900)
            .then(ok => ok ? ip : null)
        )
      );

      results.filter(Boolean).forEach(ip => alive.push(ip));
      if (onProgress) onProgress((i + BATCH) / ips.length, alive.length);
    }

    return alive;
  }

  // ── 6. Full scan: detect IP → scan subnet → probe ports ───────────────────
  async function run(onProgress, onHostFound) {
    // Step 1: local IP (or manual override)
    let localIP = BrowserScan._manualSubnet
      ? BrowserScan._manualSubnet + ".1"   // fake an IP from the manual subnet
      : await getLocalIP();

    // Fallback: try common subnets if WebRTC fails
    if (!localIP) {
      // Try to detect via a dummy connection
      try {
        const pc = new RTCPeerConnection({ iceServers: [{ urls: "stun:stun.l.google.com:19302" }] });
        pc.createDataChannel("");
        await new Promise((res) => {
          pc.createOffer().then(o => pc.setLocalDescription(o));
          pc.onicecandidate = e => {
            if (!e.candidate) { res(); return; }
            const m = /(\d{1,3}(?:\.\d{1,3}){3})/.exec(e.candidate.candidate);
            if (m && !m[1].startsWith("127.")) { localIP = m[1]; res(); }
          };
          setTimeout(res, 3000);
        });
        pc.close();
      } catch (e) {}
    }

    const subnet = localIP
      ? localIP.split(".").slice(0, 3).join(".")
      : null;

    if (!subnet) {
      return { error: "Could not detect local IP. Try entering your subnet manually.", subnet: null, hosts: [] };
    }

    onProgress({ phase: "scanning", subnet, pct: 0, found: 0 });

    // Step 2: scan subnet for alive HTTP hosts
    const aliveIPs = await scanSubnet(subnet, (pct, found) => {
      onProgress({ phase: "scanning", subnet, pct, found });
    });

    onProgress({ phase: "probing", subnet, pct: 1, found: aliveIPs.length });

    // Step 3: probe all ports on alive hosts
    const hosts = await Promise.all(
      aliveIPs.map(async ip => {
        const openPorts = await probeAllPorts(ip);
        const type = classify(openPorts);
        const host = { ip, mac: "—", hostname: null, ports: openPorts, type, brand: null };
        if (onHostFound) onHostFound(host);
        return host;
      })
    );

    return { subnet, hosts, error: null };
  }

  return { run, getLocalIP };
})();
