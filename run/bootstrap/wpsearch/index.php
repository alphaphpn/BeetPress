<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<form method="get" class="needs-validation" enctype="multipart/form-data" novalidate>
	<div class="row">
		<div class="col-lg-7">
			<div class="form-floating my-1">
				<input type="text" class="form-control" id="s" onfocus="this.select();" placeholder="Property, City, District, Neighborhood or Area" name="s" required autofocus>
				<label for="s"><b class="text-danger">*&nbsp;</b>Property, City, District, Neighborhood or Area</label>
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Please fill out this field.</div>
			</div>
		</div>

		<div class="col-lg-2">
			<div class="form-floating my-1">
				<select id="price-from" class="form-select form-control" placeholder="* From" name="price-from" required>
				</select>
				<label for="price-from"><b class="text-danger">*&nbsp;</b> From</label>
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Please fill out this field.</div>
			</div>
		</div>

		<div class="col-lg-2">
			<div class="form-floating my-1">
				<select id="price-to" class="form-select form-control" placeholder="* To" name="price-to" required>
				</select>
				<label for="price-to"><b class="text-danger">*&nbsp;</b> To</label>
				<div class="valid-feedback">Valid.</div>
				<div class="invalid-feedback">Please fill out this field.</div>
			</div>
		</div>

		<div class="col-lg-1">
			<button type="submit" class="btn btn-primary w-100 h-100 my-1">Search</button>
		</div>
	</div>
</form>

<script>
	const outputDivfrom = document.getElementById('price-from');
	const outputDiv = document.getElementById('price-to');
	let content = '';

	for (let i = 0; i <= 6000; i += 100) {
		content += '<option value="' + i + '" data-value="€' + i + '">€' + i + '</option>';
	}

	outputDiv.innerHTML = content;
	outputDivfrom.innerHTML = content;
</script>