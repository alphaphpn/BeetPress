	<section class="position-relative bg-light w-100 vh-86 pt-3 pb-5 clearfix">
		<div class="container">
			<div class="w-100 text-center d-flex justify-content-center">
				<div class="text-center mb-3" style="width: fit-content;">
					<h4 class="text-center mobile-font-size-12">Change PIN</h4>
					<hr class="y-axis-margin-0-nobile">
				</div>
			</div>
			<div class="row">
				<div class="col-md-4"></div>
				<div class="col-md-4">
					<div class="card shadow-sm">
						<div class="card-body">
							<div class="position-relative clearfix">
								<form id="empChangePIN" method="post" class="needs-validation" onsubmit="return validateSubmission(event)" novalidate>
									<div class="mb-3">
										<label for="newpinInput" class="form-label">New PIN</label>
										<div class="input-group">
											<input type="password"
												   onfocus="this.select();"
												   oninput="filterPinInput(this, 'newpinAlert'); validatePins();"
												   inputmode="numeric"
												   maxlength="8"
												   pattern="[0-9]{6,8}"
												   class="form-control password"
												   id="newpinInput"
												   name="newpinInput"
												   autocomplete="new-password"
												   onpaste="return false;"
												   required>
											<div class="input-group-prepend cursor-hand">
												<span id="show_hide_pin_new" class="input-group-text h-100 rounded-0 rounded-end">
													<i class="fa fa-eye-slash" aria-hidden="true" onclick="PinHideShow3()"></i>
												</span>
											</div>
										</div>
										<div id="newpinAlert" class="alert alert-danger py-1 px-2 mt-1 small d-none" role="alert">
											⚠️ Numbers only! Letters and symbols are not allowed.
										</div>
										<div id="newpinFeedback" class="small mt-1 d-none"></div>
									</div>

									<div class="mb-3">
										<label for="retypepinInput" class="form-label">Re-Type PIN</label>
										<div class="input-group">
											<input type="password"
												   onfocus="this.select();"
												   oninput="filterPinInput(this, 'retypepinAlert'); validatePins();"
												   inputmode="numeric"
												   maxlength="8"
												   pattern="[0-9]{6,8}"
												   class="form-control password"
												   id="retypepinInput"
												   name="retypepinInput"
												   autocomplete="new-password"
												   onpaste="return false;"
												   required>
											<div class="input-group-prepend cursor-hand">
												<span id="show_hide_pin_retype" class="input-group-text h-100 rounded-0 rounded-end">
													<i class="fa fa-eye-slash" aria-hidden="true" onclick="PinHideShow4()"></i>
												</span>
											</div>
										</div>
										<div id="retypepinAlert" class="alert alert-danger py-1 px-2 mt-1 small d-none" role="alert">
											⚠️ Numbers only! Letters and symbols are not allowed.
										</div>
										<div id="retypepinFeedback" class="small mt-1 d-none"></div>
									</div>

									<div class="mb-3">
										<?php include "change-pin.php"; ?>
									</div>
									<button type="submit"
											id="btnChangePIN"
											name="btnChangePIN"
											class="btn third-bg-color text-white mb-3">
										Change PIN
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4"></div>
			</div>

			<script>
				function filterPinInput(input, alertId) {
					const alertEl = document.getElementById(alertId);
					const cleaned = input.value.replace(/[^0-9]/g, '');
					if (input.value !== cleaned) {
						alertEl.classList.remove('d-none');
						input.value = cleaned;
					} else {
						alertEl.classList.add('d-none');
					}
				}

				function validatePins() {
					const newPin        = document.getElementById('newpinInput');
					const retypePin     = document.getElementById('retypepinInput');
					const newFeedback   = document.getElementById('newpinFeedback');
					const retypeFeedback = document.getElementById('retypepinFeedback');

					const newVal    = newPin.value;
					const retypeVal = retypePin.value;

					// ── New PIN validation ──────────────────────────────────────────
					if (newVal.length > 0) {
						newFeedback.classList.remove('d-none');
						if (newVal.length < 6) {
							// Too short — red
							setFeedback(newPin, newFeedback, false, '⚠️ PIN must be 6–8 digits.');
						} else {
							// Length OK — green
							setFeedback(newPin, newFeedback, true, '✔ PIN length is valid.');
						}
					} else {
						// Empty — reset
						resetField(newPin, newFeedback);
					}

					// ── Re-type PIN validation ──────────────────────────────────────
					if (retypeVal.length > 0) {
						retypeFeedback.classList.remove('d-none');
						if (retypeVal !== newVal) {
							// Mismatch — red
							setFeedback(retypePin, retypeFeedback, false, '⚠️ PINs do not match.');
						} else if (retypeVal.length < 6) {
							// Matches but too short — red
							setFeedback(retypePin, retypeFeedback, false, '⚠️ PIN must be 6–8 digits.');
						} else {
							// Match + valid length — green
							setFeedback(retypePin, retypeFeedback, true, '✔ PINs match!');
						}
					} else {
						// Empty — reset
						resetField(retypePin, retypeFeedback);
					}
				}

				function setFeedback(input, feedbackEl, isValid, message) {
					// Remove both states first
					input.classList.remove('is-valid', 'is-invalid');
					feedbackEl.classList.remove('text-success', 'text-danger');

					if (isValid) {
						input.classList.add('is-valid');
						feedbackEl.classList.add('text-success');
					} else {
						input.classList.add('is-invalid');
						feedbackEl.classList.add('text-danger');
					}

					feedbackEl.textContent = message;
					feedbackEl.classList.remove('d-none');
				}

				function resetField(input, feedbackEl) {
					input.classList.remove('is-valid', 'is-invalid');
					feedbackEl.classList.add('d-none');
					feedbackEl.textContent = '';
				}

				// ── Stop Form Submission if Invalid ──────────────────────────────
				function validateSubmission(event) {
					const newVal = document.getElementById('newpinInput').value;
					const retypeVal = document.getElementById('retypepinInput').value;

					// Force validation check to show messages if user clicked submit while fields were empty
					validatePins();

					// Check conditions: Must be at least 6 digits and must match exactly
					if (newVal.length < 6 || newVal !== retypeVal) {
						event.preventDefault(); // Stop form from submitting
						event.stopPropagation();
						return false;
					}
					
					return true; // Allows form to submit
				}
			</script>
		</div>
	</section>