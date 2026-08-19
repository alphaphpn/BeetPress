<?php
// Load offices for dropdowns
try {
    $offCnn  = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
    $offCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $offStmt = $offCnn->query(
        "SELECT officetitle,
                MAX(office_gps_location) AS office_gps_location,
                MAX(meter) AS meter
         FROM office_signatory_tbl
         WHERE xdel=0
         GROUP BY officetitle
         ORDER BY officetitle ASC"
    );
    $officesArr = $offStmt->fetchAll(PDO::FETCH_ASSOC);
    $offCnn = null;
} catch (Exception $e) {
    $officesArr = [];
}
?>

<style>
    .role-badge  { font-size: 0.75rem; padding: 3px 8px; border-radius: 12px; white-space: nowrap; }
    .online-dot  { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 5px; }
    .dot-online  { background: #28a745; }
    .dot-offline { background: #6c757d; }
    .sticky-col  { position: -webkit-sticky; position: sticky; left: 0; }
    #listRecView th,
    #listRecView td { text-align: center; vertical-align: middle; white-space: nowrap; }
    #listRecView_filter { text-align: center; }
</style>

<div class="container-fluid">
    <div class="pt-3">
        <h5 class="mb-3 fw-bold text-light">Employee Tracker</h5>
        <div class="table-responsive">
            <table id="listRecView" class="table table-dark table-striped table-hover">
                <thead id="remSortH">
                    <tr>
                        <th class="remove-dropdown"></th>
                        <th class="remove-dropdown"></th>
                        <th class="remove-dropdown"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="remove-dropdown"></th>
                        <th class="remove-dropdown"></th>
                        <th class="remove-dropdown"></th>
                        <th class="remove-dropdown"></th>
                        <th class="remove-dropdown"></th>
                        <th class="remove-dropdown"></th>
                        <th></th>
                        <th class="remove-dropdown"></th>
                    </tr>
                </thead>
                <thead id="theadtitle">
                    <tr>
                        <th>No.</th>
                        <th class="sticky-col">Employee ID</th>
                        <th>Employee Name</th>
                        <th>Duty Status</th>
                        <th>Office Title</th>
                        <th>Designation</th>
                        <th>Role</th>
                        <th>Work Location</th>
                        <th>Landmark</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Meter</th>
                        <th>Device ID</th>
                        <th>Device Name</th>
                        <th>Online Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        include_once "model/employee-tracker/index.php";
                        $tracker = new employeeTracker();
                        $settedofficeid = $_SESSION['d2s8wu_officeid'];

                        $roleBadgeClass = [
                            0 => 'bg-secondary',
                            1 => 'bg-primary',
                            2 => 'bg-warning text-dark',
                            3 => 'bg-danger',
                            4 => 'bg-info text-dark',
                            5 => 'bg-success',
                        ];

                        if ($tracker->fn_ListEmployeeTracker($settedofficeid)) {
                            $xno_oo = 0;
                            for ($i = 0; $i < count($tracker->list_empidcode); $i++) {
                                $xno_oo++;
                                $empid      = $tracker->list_empidcode[$i];
                                $ename      = htmlspecialchars($tracker->list_employee_name[$i] ?? '', ENT_QUOTES);
                                $otitle     = $tracker->list_officetitle[$i] ?? '';
                                $desig      = $tracker->list_designation[$i] ?? '';
                                $roleVal    = (int)($tracker->list_employee_role[$i] ?? 0);
                                $roleLabel  = employeeTracker::roleLabel($roleVal);
                                $roleCls    = $roleBadgeClass[$roleVal] ?? 'bg-secondary';
                                $wloc       = (int)($tracker->list_work_location[$i] ?? 0);
                                $landmark   = $tracker->list_office_landmark[$i] ?? '';
                                $lng        = $tracker->list_office_longitude[$i] ?? '';
                                $lat        = $tracker->list_office_latitude[$i] ?? '';
                                $meter      = $tracker->list_office_meter[$i] ?? '';
                                $ostatus    = (int)($tracker->list_online_status[$i] ?? 0);
                                $dotClass   = $ostatus == 1 ? 'dot-online' : 'dot-offline';
                                $oLabel     = $ostatus == 1 ? 'Online' : 'Offline';
                                $oTextColor = $ostatus == 1 ? 'text-success' : 'text-secondary';
                                $deviceid   = htmlspecialchars($tracker->list_device_id[$i] ?? '', ENT_QUOTES);
                                $devicename = htmlspecialchars($tracker->list_device_name[$i] ?? '', ENT_QUOTES);
                                $dutyVal    = (int)($tracker->list_duty_status[$i] ?? 0);
                                $dutyLabel  = $dutyVal == 1 ? 'On-Duty' : 'Off-Duty';
                                $dutyBadge  = $dutyVal == 1
                                    ? '<span class="badge bg-success">On-Duty</span>'
                                    : '<span class="badge bg-secondary">Off-Duty</span>';

                                // All row data stored in data-* for the edit modal
                                $rowData = htmlspecialchars(json_encode([
                                    'empid'       => $empid,
                                    'officetitle' => $otitle,
                                    'desig'       => $desig,
                                    'role'        => $roleVal,
                                    'wloc'        => $wloc,
                                    'landmark'    => $landmark,
                                    'lat'         => $lat,
                                    'lng'         => $lng,
                                    'meter'       => $meter,
                                    'duty_status' => $dutyVal,
                                ]), ENT_QUOTES);

                                $wlocLabel = $wloc == 1
                                    ? 'On-Prem'
                                    : 'Field';

                                echo '<tr data-row="' . $rowData . '">';
                                    echo '<td>' . $xno_oo . '</td>';
                                    echo '<td class="sticky-col fw-bold">' . htmlspecialchars($empid, ENT_QUOTES) . '</td>';
                                    echo '<td>' . $ename . '</td>';
                                    echo '<td class="cell-duty" data-order="' . $dutyVal . '" data-search="' . $dutyLabel . '">' . $dutyBadge . '</td>';
                                    echo '<td class="cell-officetitle">' . htmlspecialchars($otitle) . '</td>';
                                    echo '<td class="cell-desig">' . htmlspecialchars($desig) . '</td>';
                                    echo '<td class="cell-role" data-search="' . $roleLabel . '" data-order="' . $roleVal . '">' . $roleLabel . '</td>';
                                    $wlocText = $wloc == 1 ? 'On-Prem' : 'Field';
                                    echo '<td class="cell-wloc" data-search="' . $wlocText . '" data-order="' . $wloc . '">' . $wlocLabel . '</td>';
                                    echo '<td class="cell-landmark">' . ($landmark ? htmlspecialchars($landmark) : '—') . '</td>';
                                    echo '<td class="cell-lng">' . ($lng !== '' ? $lng : '—') . '</td>';
                                    echo '<td class="cell-lat">' . ($lat !== '' ? $lat : '—') . '</td>';
                                    echo '<td class="cell-meter">' . ($meter !== '' ? number_format((float)$meter, 2) . ' m' : '—') . '</td>';
                                    echo '<td>' . ($deviceid ?: '—') . '</td>';
                                    echo '<td>' . ($devicename ?: '—') . '</td>';
                                    echo '<td class="' . $oTextColor . ' fw-bold" data-order="' . $ostatus . '">' . $oLabel . '</td>';
                                    echo '<td>
                                            <button class="btn btn-warning btn-sm px-2 py-1" style="font-size:.75rem;"
                                                    onclick="openEditModal(this)">
                                                <i class="fas fa-pen me-1"></i>Edit
                                            </button>
                                          </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="16" class="text-center text-muted py-4">No records found.</td></tr>';
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="remove-dropdown"></td>
                        <td class="remove-dropdown"></td>
                        <td class="remove-dropdown"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="remove-dropdown"></td>
                        <td class="remove-dropdown"></td>
                        <td class="remove-dropdown"></td>
                        <td class="remove-dropdown"></td>
                        <td class="remove-dropdown"></td>
                        <td class="remove-dropdown"></td>
                        <td></td>
                        <td class="remove-dropdown"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<!-- ========== UNIFIED EDIT MODAL ========== -->
<div class="modal fade text-dark" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2 bg-warning">
                <h6 class="modal-title mb-0 fw-bold">
                    <i class="fas fa-pen me-2"></i>Edit Employee Details
                    <small class="ms-2 fw-normal text-muted" id="editEmpIdLabel"></small>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editEmpid">

                <div class="row g-3">
                    <!-- Office Title -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Office Title</label>
                        <select class="form-select form-select-sm" id="editOfficetitle"></select>
                    </div>

                    <!-- Designation -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Designation</label>
                        <input type="text" class="form-control form-control-sm" id="editDesig" placeholder="Enter designation">
                    </div>

                    <!-- Role -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Role</label>
                        <select class="form-select form-select-sm" id="editRole">
                            <option value="0">Staff</option>
                            <option value="1">Official</option>
                            <option value="2">Executive</option>
                            <option value="3">Head Officer</option>
                            <option value="4">Assistant Head Officer</option>
                            <option value="5">Admin Officer</option>
                        </select>
                    </div>

                    <!-- Work Location -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Work Location</label>
                        <select class="form-select form-select-sm" id="editWloc">
                            <option value="0">Field</option>
                            <option value="1">On-Prem</option>
                        </select>
                    </div>

                    <!-- Duty Status -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">Duty Status</label>
                        <select class="form-select form-select-sm" id="editDutyStatus">
                            <option value="0">Off-Duty</option>
                            <option value="1">On-Duty</option>
                        </select>
                    </div>

                    <div class="col-12"><hr class="my-1"></div>

                    <!-- Landmark / Office -->
                    <div class="col-12">
                        <label class="form-label fw-bold mb-1">Office / Landmark</label>
                        <select class="form-select form-select-sm" id="editLandmark" onchange="onEditOfficeChange()">
                            <option value="">— Select Office —</option>
                        </select>
                    </div>

                    <!-- Latitude -->
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size:.85rem;">Latitude</label>
                        <input type="text" class="form-control form-control-sm" id="editLat" readonly placeholder="auto-filled">
                    </div>

                    <!-- Longitude -->
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size:.85rem;">Longitude</label>
                        <input type="text" class="form-control form-control-sm" id="editLng" readonly placeholder="auto-filled">
                    </div>

                    <!-- Meter -->
                    <div class="col-md-4">
                        <label class="form-label mb-1" style="font-size:.85rem;">Meter</label>
                        <input type="number" step="0.01" class="form-control form-control-sm" id="editMeter" placeholder="Enter meter value">
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning btn-sm" id="editSaveBtn" onclick="saveEditModal()">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>


<script>
// ============================================================
// OFFICES DATA
// ============================================================
const officesData = <?php echo json_encode($officesArr); ?>;

const officesParsed = officesData.map(o => {
    let lat = '', lng = '';
    if (o.office_gps_location) {
        const parts = o.office_gps_location.split(',');
        if (parts.length >= 2) { lat = parts[0].trim(); lng = parts[1].trim(); }
    }
    return { officetitle: o.officetitle, lat, lng, meter: o.meter || '' };
});

const roleBadgeClass = ['bg-secondary','bg-primary','bg-warning text-dark','bg-danger','bg-info text-dark','bg-success'];
const roleLabels     = ['Staff','Official','Executive','Head Officer','Assistant Head Officer','Admin Officer'];

// Build office select options once on load
document.addEventListener('DOMContentLoaded', function () {
    const offTitleSel  = document.getElementById('editOfficetitle');
    const landmarkSel  = document.getElementById('editLandmark');

    officesParsed.forEach(o => {
        // office title select
        offTitleSel.add(new Option(o.officetitle, o.officetitle));

        // landmark select — store meter as '' when null so dataset never holds "null"
        const opt = new Option(o.officetitle, o.officetitle);
        opt.dataset.lat   = o.lat;
        opt.dataset.lng   = o.lng;
        opt.dataset.meter = (o.meter !== null && o.meter !== undefined && o.meter !== '') ? o.meter : '';
        landmarkSel.add(opt);
    });
});


// ============================================================
// OPEN MODAL
// ============================================================
function openEditModal(btn) {
    const row  = btn.closest('tr');
    const data = JSON.parse(row.dataset.row);

    document.getElementById('editEmpid').value       = data.empid;
    document.getElementById('editEmpIdLabel').textContent = '— ' + data.empid;
    document.getElementById('editDesig').value        = data.desig   || '';
    document.getElementById('editRole').value         = data.role;
    document.getElementById('editWloc').value         = data.wloc;
    document.getElementById('editLat').value          = data.lat     || '';
    document.getElementById('editLng').value          = data.lng     || '';
    document.getElementById('editMeter').value        = data.meter   || '';
    document.getElementById('editDutyStatus').value  = data.duty_status ?? 0;

    // Set office title
    document.getElementById('editOfficetitle').value = data.officetitle || '';

    // Set landmark select and always auto-fill lat/lng/meter from the office record
    const lmkSel = document.getElementById('editLandmark');
    lmkSel.value = data.landmark || '';
    onEditOfficeChange();

    new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
}

function onEditOfficeChange() {
    const sel = document.getElementById('editLandmark');
    const opt = sel.options[sel.selectedIndex];
    if (opt && opt.dataset) {
        document.getElementById('editLat').value   = opt.dataset.lat   || '';
        document.getElementById('editLng').value   = opt.dataset.lng   || '';
        document.getElementById('editMeter').value = opt.dataset.meter || '';
    }
}


// ============================================================
// SAVE
// ============================================================
function saveEditModal() {
    const empid      = document.getElementById('editEmpid').value;
    const officetitle = document.getElementById('editOfficetitle').value.trim();
    const desig      = document.getElementById('editDesig').value.trim();
    const role       = document.getElementById('editRole').value;
    const wloc       = document.getElementById('editWloc').value;
    const landmark   = document.getElementById('editLandmark').value;
    const lat        = document.getElementById('editLat').value.trim();
    const lng        = document.getElementById('editLng').value.trim();
    const meter       = document.getElementById('editMeter').value.trim();
    const duty_status = document.getElementById('editDutyStatus').value;

    if (!desig) { alert('Designation cannot be empty.'); return; }

    const btn = document.getElementById('editSaveBtn');
    btn.disabled = true;
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

    fetch('model/employee-tracker/update-all.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ empid, officetitle, desig, role, wloc, landmark, lat, lng, meter, duty_status })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled  = false;
        btn.innerHTML = orig;
        if (data.status === 'success') {
            bootstrap.Modal.getInstance(document.getElementById('editEmployeeModal')).hide();
            updateRow(empid, { officetitle, desig, role: parseInt(role), wloc: parseInt(wloc), landmark, lat, lng, meter, duty_status: parseInt(duty_status) });
        } else {
            alert('Error: ' + (data.msg || 'Failed to save.'));
        }
    })
    .catch(() => {
        btn.disabled  = false;
        btn.innerHTML = orig;
        alert('Network error. Please try again.');
    });
}


// ============================================================
// UPDATE ROW IN TABLE
// ============================================================
function updateRow(empid, d) {
    const row = [...document.querySelectorAll('tr[data-row]')]
        .find(r => JSON.parse(r.dataset.row).empid === empid);
    if (!row) return;

    // Update data-row attribute
    const existing = JSON.parse(row.dataset.row);
    row.dataset.row = JSON.stringify(Object.assign(existing, d));

    // Office Title
    row.querySelector('.cell-officetitle').textContent = d.officetitle || '—';

    // Designation
    row.querySelector('.cell-desig').textContent = d.desig || '—';

    // Role
    const roleLabel = roleLabels[d.role] || String(d.role);
    const roleCell  = row.querySelector('.cell-role');
    roleCell.textContent          = roleLabel;
    roleCell.dataset.search       = roleLabel;
    roleCell.dataset.order        = d.role;

    // Work Location
    const wlocLabel = d.wloc == 1 ? 'On-Prem' : 'Field';
    const wlocCell  = row.querySelector('.cell-wloc');
    wlocCell.textContent    = wlocLabel;
    wlocCell.dataset.search = wlocLabel;
    wlocCell.dataset.order  = d.wloc;

    // Location cells
    row.querySelector('.cell-landmark').textContent = d.landmark || '—';
    row.querySelector('.cell-lng').textContent      = d.lng   || '—';
    row.querySelector('.cell-lat').textContent      = d.lat   || '—';
    row.querySelector('.cell-meter').textContent    = d.meter
        ? parseFloat(d.meter).toFixed(2) + ' m' : '—';

    // Duty Status
    if ('duty_status' in d) {
        const dutyCell  = row.querySelector('.cell-duty');
        const dutyLbl   = d.duty_status == 1 ? 'On-Duty' : 'Off-Duty';
        const dutyBadge = d.duty_status == 1
            ? '<span class="badge bg-success">On-Duty</span>'
            : '<span class="badge bg-secondary">Off-Duty</span>';
        dutyCell.innerHTML        = dutyBadge;
        dutyCell.dataset.order    = d.duty_status;
        dutyCell.dataset.search   = dutyLbl;
    }
}
</script>
