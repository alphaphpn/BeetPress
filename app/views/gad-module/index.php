<?php $gadRecords = $gadHealthData->getRecords(); ?>

<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div><h4 class="mb-1">GAD Health Data</h4><p class="text-muted mb-0">Maintain the records displayed on the public GAD chart.</p></div>
        <button class="btn btn-primary" type="button" onclick="openGadForm()"><i class="fas fa-plus me-1"></i>Add Record</button>
    </div>

    <div class="card shadow-sm"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>CY</th><th>Data Set</th><th>Diagnosis / Case</th><th class="text-end">Male</th><th class="text-end">Female</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                <?php if (!$gadRecords) { ?><tr><td colspan="6" class="text-center text-muted py-4">No GAD records found.</td></tr><?php } ?>
                <?php foreach ($gadRecords as $record) { ?>
                    <tr>
                        <td><?php echo (int)$record['calendar_year']; ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($record['data_category'])); ?></td>
                        <td><?php echo htmlspecialchars($record['diagnosis']); ?></td>
                        <td class="text-end"><?php echo number_format($record['male_count']); ?></td>
                        <td class="text-end"><?php echo number_format($record['female_count']); ?></td>
                        <td class="text-end text-nowrap">
                            <button class="btn btn-sm btn-warning" type="button" onclick='openGadForm(<?php echo json_encode($record, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'><i class="fas fa-edit"></i> Edit</button>
                            <form method="post" class="d-inline" onsubmit="return confirm('Remove this record from the GAD chart?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int)$record['gad_health_data_autoid']; ?>"><button class="btn btn-sm btn-outline-danger" type="submit"><i class="fas fa-trash"></i> Delete</button></form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div></div></div>
</div>

<div class="modal fade" id="gadRecordModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content">
    <form method="post"><input type="hidden" name="action" value="save"><input type="hidden" name="id" id="gad_id">
        <div class="modal-header"><h5 class="modal-title" id="gadFormTitle">Add GAD Record</h5><button class="btn-close" type="button" data-bs-dismiss="modal"></button></div>
        <div class="modal-body"><div class="row g-3">
            <div class="col-sm-5"><label class="form-label" for="gad_year">Calendar Year</label><input class="form-control" type="number" min="2000" max="2100" name="calendar_year" id="gad_year" value="<?php echo date('Y'); ?>" required></div>
            <div class="col-sm-7"><label class="form-label" for="gad_category">Data Set</label><select class="form-select" name="data_category" id="gad_category" required><option value="discharged">Discharged Diagnosis</option><option value="emergency">Emergency Cases (ER)</option><option value="opd">Consultations (OPD)</option></select></div>
            <div class="col-12"><label class="form-label" for="gad_diagnosis">Diagnosis / Case</label><input class="form-control" type="text" maxlength="255" name="diagnosis" id="gad_diagnosis" required></div>
            <div class="col-6"><label class="form-label" for="gad_male">Male Count</label><input class="form-control" type="number" min="0" name="male_count" id="gad_male" required></div>
            <div class="col-6"><label class="form-label" for="gad_female">Female Count</label><input class="form-control" type="number" min="0" name="female_count" id="gad_female" required></div>
        </div></div>
        <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit">Save Record</button></div>
    </form>
</div></div></div>

<script>
    function openGadForm(record) {
        record = record || {};
        document.getElementById('gadFormTitle').textContent = record.gad_health_data_autoid ? 'Edit GAD Record' : 'Add GAD Record';
        document.getElementById('gad_id').value = record.gad_health_data_autoid || '';
        document.getElementById('gad_year').value = record.calendar_year || new Date().getFullYear();
        document.getElementById('gad_category').value = record.data_category || 'discharged';
        document.getElementById('gad_diagnosis').value = record.diagnosis || '';
        document.getElementById('gad_male').value = record.male_count ?? '';
        document.getElementById('gad_female').value = record.female_count ?? '';
        new bootstrap.Modal(document.getElementById('gadRecordModal')).show();
    }
</script>
