<?php

	$username = isset($_GET["username"]) ? $_GET["username"] : null;

	try {
		require_once "lib/env.php";

		$cnn = null;

		$cnn = new PDO("mysql:host={$host};dbname={$db}", $uname, $pw);
		$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Added LIMIT 1 since username should yield a single profile record
		$query_name = "SELECT * FROM user_tbl INNER JOIN profile_tbl ON user_tbl.profileid = profile_tbl.profileid WHERE user_tbl.uname = :username LIMIT 1";
		$stmt_name = $cnn->prepare($query_name);
		$stmt_name->bindParam(":username", $username);
		$stmt_name->execute();
		// Fetching with FETCH_ASSOC directly for a single row result
		$accountuser = $stmt_name->fetch(PDO::FETCH_ASSOC);

		$query = "SELECT * FROM portfolio_tbl WHERE username = :username";
		$stmt = $cnn->prepare($query);
		$stmt->bindParam(":username", $username);
		$stmt->execute();
		$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// --- Grouping projects by "portfolio_category" ---
		$categorized_projects = [];
		if (!empty($projects)) {
			foreach ($projects as $project) {
				// Checks the target "portfolio_category" column
				$cat = !empty($project['portfolio_category']) ? $project['portfolio_category'] : 'Other'; 
				$categorized_projects[$cat][] = $project;
			}
		}

	} catch (PDOException $error) {
		$err_msg = $error->getMessage();
		echo "<p>Error: {$err_msg}</p>";
		die;
	}

?>

	<style>
		body {
			background-color: #121212;
			color: #e0e0e0;
		}
		.card {
			background-color: #1e1e1e;
			border: 1px solid #2d2d2d;
			transition: transform 0.2s ease-in-out;
		}
		.card:hover {
			transform: translateY(-5px);
		}
		.text-tech {
			color: #ffc107;
		}
		.modal-content {
			background-color: #1e1e1e;
			color: #e0e0e0;
			border: 1px solid #333;
		}
		.modal-header, .modal-footer {
			border-color: #2d2d2d;
		}
		.btn-close {
			filter: invert(1);
		}
		.text-align-justify {text-align: justify;}
		
		/* Dark Mode Nav Tab styling matching your UI */
		.nav-tabs {
			border-bottom: 2px solid #2d2d2d;
		}
		.nav-tabs .nav-link {
			color: #a0a0a0;
			border: none;
		}
		.nav-tabs .nav-link:hover {
			color: #fff;
			border: none;
		}
		.nav-tabs .nav-link.active {
			background-color: transparent !important;
			color: #ffc107 !important;
			border-bottom: 2px solid #ffc107;
		}
		.portfolio-header {
			position: -webkit-sticky; /* For Safari compatibility */
			position: sticky;
			top: 0;
			z-index: 1000; /* Keeps it layered on top of other scrolling content */
			background-color: #121212;
		}
	</style>

	<div class="container py-5">
		<header class="text-center mb-5">
			<?php if ($accountuser): ?>
				<div class="portfolio-header">
					<h1 class="display-5 fw-bold"><?php echo htmlspecialchars($accountuser['first_name']." ".substr($accountuser['middle_name'], 0, 1).". ".$accountuser['last_name']) ?></h1>
					<h2 class="display-7 fw-bold"><?php echo htmlspecialchars($accountuser['position_desired']) ?></h2>
				</div>
				<p class="lead text-align-justify text-light"><?php echo htmlspecialchars($accountuser['about']) ?></p>
			<?php else: ?>
				<h1 class="display-4 fw-bold text-white">Unknown User</h1>
				<p class="lead text-light">Please provide a valid username parameter.</p>
			<?php endif; ?>
			<hr class="border-secondary my-4">
		</header>

		<?php if (!empty($categorized_projects)): ?>
			<ul class="nav nav-tabs mb-4 justify-content-center" id="portfolioTabs" role="tablist">
				<?php 
				$isActive = true;
				foreach ($categorized_projects as $category => $items): 
					// Converts spaces and special chars to safe DOM IDs (e.g., "IT Services" -> "it-services")
					$tabId = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $category));
				?>
					<li class="nav-item" role="presentation">
						<button class="nav-link <?= $isActive ? 'active' : '' ?> fw-semibold text-uppercase px-4" 
								id="<?= $tabId ?>-tab" 
								data-bs-toggle="tab" 
								data-bs-target="#<?= $tabId ?>-pane" 
								type="button" 
								role="tab" 
								aria-controls="<?= $tabId ?>-pane" 
								aria-selected="<?= $isActive ? 'true' : 'false' ?>">
							<?= htmlspecialchars($category) ?> (<?= count($items) ?>)
						</button>
					</li>
				<?php 
					$isActive = false;
				endforeach; 
				?>
			</ul>

			<div class="tab-content" id="portfolioTabsContent">
				<?php 
				$isActive = true;
				foreach ($categorized_projects as $category => $items): 
					$tabId = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $category));
				?>
					<div class="tab-pane fade <?= $isActive ? 'show active' : '' ?>" 
						 id="<?= $tabId ?>-pane" 
						 role="tabpanel" 
						 aria-labelledby="<?= $tabId ?>-tab" 
						 tabindex="0">
						 
						<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
							<?php foreach ($items as $project): ?>
								<div class="col">
									<div class="card h-100 shadow-sm">
										<a href="<?= htmlspecialchars($domainhome.'/public/portfolio/'.$username.'/'.$project['image_url'].'.jpg') ?>" target="_blank">
											<img src="<?= htmlspecialchars($domainhome.'/public/portfolio/'.$username.'/'.$project['image_url'].'.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($project['title']) ?>" style="height: 394px; object-fit: cover; object-position: top;">
										</a>
										
										<div class="card-body d-flex flex-column">
											<h5 class="card-title text-white fw-bold"><?= htmlspecialchars($project['title']) ?></h5>
											
											<p class="card-text small text-tech mb-3">
												<strong>Tech Stack:</strong> <?= htmlspecialchars($project['technologies']) ?>
											</p>
											
											<div class="mt-auto d-flex gap-2">
												<button type="button" 
														class="btn btn-warning w-100 fw-semibold view-project-btn" 
														data-bs-toggle="modal" 
														data-bs-target="#projectModal"
														data-title="<?= htmlspecialchars($project['title']) ?>"
														data-url="<?= htmlspecialchars($project['url']) ?>"
														data-tech="<?= htmlspecialchars($project['technologies']) ?>"
														data-image="<?= htmlspecialchars($domainhome.'/public/portfolio/'.$username.'/'.$project['image_url'].'.jpg') ?>"
														data-image-url="<?= htmlspecialchars($domainhome.'/public/portfolio/'.$username.'/'.$project['image_url'].'.jpg') ?>"
														data-desc="<?= htmlspecialchars($project['description'] ?? 'No extra details provided.') ?>">
													View Details
												</button>
												<a href="<?= htmlspecialchars($project['url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-light">
													Launch
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						
					</div>
				<?php 
					$isActive = false;
				endforeach; 
				?>
			</div>

		<?php else: ?>
			<div class="text-center py-5">
				<p class="text-muted">No projects found for this profile.</p>
			</div>
		<?php endif; ?>
	</div>

	<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title fw-bold" id="projectModalLabel">Project Title</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 mb-3 mb-md-0">
							<a id="modalImageUrl" href="" target="_blank"><img id="modalImage" src="" class="img-fluid rounded border border-secondary" alt="Project Image"></a>
						</div>
						<div class="col-md-6 d-flex flex-column justify-content-between">
							<div>
								<h6 class="text-info text-uppercase small tracking-wider mb-1">Technologies Used</h6>
								<p id="modalTech" class="text-tech fw-semibold mb-3"></p>
								
								<h6 class="text-info text-uppercase small tracking-wider mb-1">Description</h6>
								<p id="modalDesc" class="text-light small"></p>
							</div>
							<div class="pt-3">
								<a id="modalUrl" href="#" target="_blank" rel="noopener noreferrer" class="btn btn-warning w-100 fw-bold">
									Visit Live Website &rarr;
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const projectModal = document.getElementById('projectModal');
			
			if (projectModal) {
				projectModal.addEventListener('show.bs.modal', function (event) {
					const button = event.relatedTarget;
					
					const title = button.getAttribute('data-title');
					const url = button.getAttribute('data-url');
					const tech = button.getAttribute('data-tech');
					const image = button.getAttribute('data-image');
					const desc = button.getAttribute('data-desc');
					const imgurl = button.getAttribute('data-image-url');
					
					const modalTitle = projectModal.querySelector('#projectModalLabel');
					const modalImage = projectModal.querySelector('#modalImage');
					const modalTech = projectModal.querySelector('#modalTech');
					const modalDesc = projectModal.querySelector('#modalDesc');
					const modalUrl = projectModal.querySelector('#modalUrl');
					const modalImgUrl = projectModal.querySelector('#modalImageUrl');
					
					modalTitle.textContent = title;
					modalImage.src = image;
					modalImage.alt = title;
					modalTech.textContent = tech;
					modalDesc.textContent = desc;
					modalUrl.href = url;
					modalImgUrl.href = imgurl;
				});
			}
		});
	</script>