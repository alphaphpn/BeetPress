<?php
if (empty($_SESSION['attendance_csrf'])) $_SESSION['attendance_csrf'] = bin2hex(random_bytes(32));
?>
<div class="d-flex justify-content-center align-items-center py-4">
    <div class="card text-center shadow-sm" style="width: 26rem; max-width: 100%;" id="attendance-kiosk"
         data-api="<?= htmlspecialchars($domainhome, ENT_QUOTES) ?>/api/employeesubdtr/"
         data-models="<?= htmlspecialchars($domainhome, ENT_QUOTES) ?>/run/html/face-detect/models"
         data-csrf="<?= htmlspecialchars($_SESSION['attendance_csrf'], ENT_QUOTES) ?>"
         data-now="<?= time() * 1000 ?>">
        <div class="card-body">
            <h5 id="current-date" class="text-secondary"></h5>
            <h2 id="current-time" class="text-primary mb-4"></h2>
            <form id="attendance-credentials">
                <div class="mb-3 text-start">
                    <label for="attendance-id" class="form-label">Employee ID Number</label>
                    <input id="attendance-id" class="form-control" type="text" inputmode="numeric" pattern="[0-9]+" maxlength="32" autocomplete="off" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="attendance-pin" class="form-label">PIN</label>
                    <input id="attendance-pin" class="form-control" type="password" inputmode="numeric" pattern="[0-9]+" maxlength="64" autocomplete="off" required>
                </div>
            </form>
            <div id="attendance-profile" class="mb-3" hidden>
                <img id="attendance-photo" alt="Employee photo" width="120" height="120" class="rounded mb-2" style="object-fit: cover;" hidden>
                <p id="attendance-no-photo" hidden>No employee photo on file.</p>
                <h5 id="attendance-name"></h5>
                <p id="attendance-office" class="text-secondary"></p>
                <p class="small mb-1"><strong>Office landmark:</strong> <span id="attendance-landmark"></span></p>
                <p id="attendance-distance" class="small text-secondary" aria-live="polite"></p>
                <div id="attendance-timetable" class="table-responsive" hidden>
                    <table class="table table-bordered table-sm">
                        <caption id="attendance-day" class="caption-top text-center"></caption>
                        <thead><tr><th scope="col">AM-In</th><th scope="col">AM-Out</th><th scope="col">PM-In</th><th scope="col">PM-Out</th></tr></thead>
                        <tbody><tr><td id="attendance-amtimein"></td><td id="attendance-amtimeout"></td><td id="attendance-pmtimein"></td><td id="attendance-pmtimeout"></td></tr></tbody>
                    </table>
                    <p class="small text-secondary">— = Not recorded</p>
                </div>
            </div>
            <p class="small text-secondary">Allow location and camera access, then face the unit’s camera. Your location and photo are saved with each attendance entry.</p>
            <div id="attendance-am" class="mb-2" hidden>
                <button type="button" id="btn-am-in" data-action="amtimein" class="btn btn-outline-success" disabled>AM-In</button>
                <button type="button" id="btn-am-out" data-action="amtimeout" class="btn btn-outline-danger" disabled>AM-Out</button>
            </div>
            <div id="attendance-pm" class="mb-2" hidden>
                <button type="button" id="btn-pm-in" data-action="pmtimein" class="btn btn-outline-success" disabled>PM-In</button>
                <button type="button" id="btn-pm-out" data-action="pmtimeout" class="btn btn-outline-danger" disabled>PM-Out</button>
            </div>
            <div id="attendance-message" role="status" aria-live="polite"></div>
            <video id="attendance-camera" autoplay muted playsinline aria-hidden="true" style="position:fixed;left:-10000px;width:640px;height:480px;"></video>
        </div>
    </div>
</div>
<script src="<?= htmlspecialchars($domainhome, ENT_QUOTES) ?>/assets/js/face-api.min.js"></script>
<script src="<?= htmlspecialchars($domainhome, ENT_QUOTES) ?>/assets/js/attendance-kiosk.js"></script>
