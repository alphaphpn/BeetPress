
<?php
	$contactMessageSent = null;
	$contactMessageError = null;
	$contactForm = array('name' => '', 'phone' => '', 'email' => '', 'address' => '', 'message' => '');

	if (empty($_SESSION['contactus_csrf'])) {
		$_SESSION['contactus_csrf'] = bin2hex(random_bytes(32));
	}

	try {
		$contactCnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$contactCnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$contactCnn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$contactCnn->exec("CREATE TABLE IF NOT EXISTS tbl_contactusmsg (
			contactusmsg_autoid BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			name VARCHAR(150) NOT NULL,
			phone VARCHAR(50) NOT NULL,
			email VARCHAR(150) DEFAULT NULL,
			address VARCHAR(255) NOT NULL,
			message TEXT NOT NULL,
			status TINYINT(1) NOT NULL DEFAULT 0,
			created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (contactusmsg_autoid)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
		$contactStatusColumn = $contactCnn->query("SHOW COLUMNS FROM tbl_contactusmsg LIKE 'status'")->fetch(PDO::FETCH_ASSOC);
		if (!$contactStatusColumn) {
			$contactCnn->exec("ALTER TABLE tbl_contactusmsg ADD COLUMN status TINYINT(1) NOT NULL DEFAULT 0 AFTER message");
		} elseif ((string) $contactStatusColumn['Default'] !== '0') {
			$contactCnn->exec("ALTER TABLE tbl_contactusmsg MODIFY COLUMN status TINYINT(1) NOT NULL DEFAULT 0");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contactus_submit'])) {
			foreach ($contactForm as $field => $value) {
				$contactForm[$field] = trim((string) ($_POST[$field] ?? ''));
			}

			if (!hash_equals($_SESSION['contactus_csrf'], (string) ($_POST['contactus_csrf'] ?? ''))) {
				$contactMessageError = 'Your message session has expired. Please try again.';
			} elseif ($contactForm['name'] === '' || $contactForm['phone'] === '' || $contactForm['address'] === '' || $contactForm['message'] === '') {
				$contactMessageError = 'Please complete all required fields.';
			} elseif ($contactForm['email'] !== '' && !filter_var($contactForm['email'], FILTER_VALIDATE_EMAIL)) {
				$contactMessageError = 'Please enter a valid email address.';
			} else {
				$saveContactMessage = $contactCnn->prepare("INSERT INTO tbl_contactusmsg (name, phone, email, address, message) VALUES (:name, :phone, :email, :address, :message)");
				$saveContactMessage->execute(array(
					':name' => $contactForm['name'],
					':phone' => $contactForm['phone'],
					':email' => $contactForm['email'] ?: null,
					':address' => $contactForm['address'],
					':message' => $contactForm['message']
				));
				$contactMessageSent = $contactForm;
				$contactForm = array('name' => '', 'phone' => '', 'email' => '', 'address' => '', 'message' => '');
			}
		}
	} catch (PDOException $exception) {
		$contactMessageError = 'We could not send your message right now. Please try again later.';
	}
?>
	<section id="contact" class="position-relative primary-bg-color-light w-100 h-100 pt-5 pb-5 clearfix">
		<div class="container">
			<div class="slideanim row">
				<div class="col-md-6 m-auto">
					<div class="position-relative clearfix">
						<h2>Contact Us</h2>
						<p>Contact us and will be in touch to you as soon as we get online.</p>
						<p><a class="text-body text-decoration-none" href="https://www.google.com/maps/dir//Zamboanga+Sibugay+Provincial+Capitol+QHQF%2B6C9+Capitol+View+Rd+Ipil,+Zamboanga+Sibugay/@7.7880421,122.5735959,538m/data=!3m1!1e3!4m8!4m7!1m0!1m5!1m1!1s0x3253d85b738c4ae9:0x1087044389f062a7!2m2!1d122.5735959!2d7.7880421!5m1!1e2?entry=ttu&g_ep=EgoyMDI1MDcyMy4wIKXMDSoASAFQAw%3D%3D" target="_blank"><span class="fas fa-map-marker-alt"></span> Capitol Site, Ipil, Zamboanga Sibugay 7001</a></p>
						<p><a class="text-body text-decoration-none" href="tel:+639177094070"><span class="fas fa-phone"></span> +63 917 709 4070</a></p>
						<p><a class="text-body text-decoration-none" href="mailto:info@sibugay.gov.ph"><span class="fas fa-envelope-square"></span> info@sibugay.gov.ph</a></p>
						<p>
							<a class="text-body text-decoration-none" href="//www.facebook.com/DrAnnKHofer" target="_blank"><span class="fab fa-facebook-square"></span> DrAnnKHofer</a>
							<a class="text-body text-decoration-none" href="//www.facebook.com/ross.peralta.58" target="_blank"><span class="fab fa-facebook-square"></span> Sibug Ngari Bagay</a>
							<a class="text-body text-decoration-none" href="https://www.facebook.com/SibugNgariBagay" target="_blank"><span class="fab fa-facebook-square"></span> Lambo pa Sibugay</a>
						</p>
					</div>
				</div>
				<div class="col-md-6  m-auto">
					<div class="position-relative clearfix slideanim">
						<form method="post" class="needs-validation" novalidate>
							<input type="hidden" name="contactus_csrf" value="<?php echo htmlspecialchars($_SESSION['contactus_csrf']); ?>">
							<div class="row my-2">
								<div class="col-sm-6 form-group">
									<input class="form-control" id="name" name="name" placeholder="Name" type="text" required value="<?php echo htmlspecialchars($contactForm['name']); ?>">
								</div>
								<div class="col-sm-6 form-group">
									<input class="form-control" id="phone" name="phone" placeholder="Phone" type="tel" required value="<?php echo htmlspecialchars($contactForm['phone']); ?>">
								</div>
							</div>

							<div class="row my-2">
								<div class="col-sm-6 form-group">
									<input class="form-control" id="email" name="email" placeholder="Email" type="email" value="<?php echo htmlspecialchars($contactForm['email']); ?>">
								</div>
								<div class="col-sm-6 form-group">
									<input class="form-control" id="address" name="address" placeholder="Address" type="text" required value="<?php echo htmlspecialchars($contactForm['address']); ?>">
								</div>
							</div>

							<textarea class="form-control  my-2" id="message" name="message" placeholder="Message" rows="5" required><?php echo htmlspecialchars($contactForm['message']); ?></textarea><br>

							<div class="row my-2">
								<div class="col-sm-12 form-group">
									<button class="btn third-bg-color text-white w-100" type="submit" name="contactus_submit">Send</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php if ($contactMessageSent): ?>
<div class="modal fade" id="contact-confirmation-modal" tabindex="-1" aria-labelledby="contact-confirmation-title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered"><div class="modal-content">
		<div class="modal-header"><h5 class="modal-title" id="contact-confirmation-title">Message</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
		<div class="modal-body">
			<p class="text-success fw-semibold">Your message already sent</p>
			<dl class="row mb-0">
				<dt class="col-sm-4">Name</dt><dd class="col-sm-8"><?php echo htmlspecialchars($contactMessageSent['name']); ?></dd>
				<dt class="col-sm-4">Phone</dt><dd class="col-sm-8"><?php echo htmlspecialchars($contactMessageSent['phone']); ?></dd>
				<?php if ($contactMessageSent['email'] !== ''): ?><dt class="col-sm-4">Email</dt><dd class="col-sm-8"><?php echo htmlspecialchars($contactMessageSent['email']); ?></dd><?php endif; ?>
				<dt class="col-sm-4">Address</dt><dd class="col-sm-8"><?php echo htmlspecialchars($contactMessageSent['address']); ?></dd>
				<dt class="col-sm-4">Message</dt><dd class="col-sm-8" style="white-space: pre-wrap;"><?php echo htmlspecialchars($contactMessageSent['message']); ?></dd>
			</dl>
		</div>
		<div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div>
	</div></div>
</div>
<?php endif; ?>

<?php if ($contactMessageError): ?>
<script>document.addEventListener('DOMContentLoaded', function () { alert(<?php echo json_encode($contactMessageError); ?>); });</script>
<?php endif; ?>

<?php if ($contactMessageSent): ?>
<script>document.addEventListener('DOMContentLoaded', function () { bootstrap.Modal.getOrCreateInstance(document.getElementById('contact-confirmation-modal')).show(); });</script>
<?php endif; ?>

	<section class="position-relative primary-bg-color-light w-100 h-100 clearfix">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1160.5243771523446!2d122.57341190737397!3d7.788072104030759!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3253d85b738c4ae9%3A0x1087044389f062a7!2sZamboanga%20Sibugay%20Provincial%20Capitol!5e1!3m2!1sen!2sph!4v1753497573739!5m2!1sen!2sph" width="100%" height="320" style="border:0; margin-bottom: -4px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
	</section>
