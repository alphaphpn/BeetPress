
	<section class="position-relative second-last-bg-color w-100 h-100 pt-5 pb-5 clearfix">
		<div class="container">
			<div class="row text-light">
				<div class="col-md-3 mb-5">
					<div class="position-relative clearfix">
						<h3 id="seal" class="txt-color-primary">Seal</h3>
						<hr>
						<a href="//www.gov.ph/" target="_blank"><img src="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>assets/media/republikangpilipinaslogo-dark.0600f80a.svg"></a>
					</div>

					<div class="position-relative my-2 clearfix">
						<a href="<?php echo trim($domainhome); ?>" target="_blank"><img class="foot-img-logo" src="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>assets/media/favicon.png"></a>
						<a href="//www.bagongpilipinastayo.com/" target="_blank"><img class="foot-img-logo" src="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>assets/media/bagong-pilipinas.png"></a>
						<img class="foot-img-logo" src="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>assets/media/transparency-seal.png">
						<a href="//www.foi.gov.ph/" target="_blank"><img class="foot-img-logo" src="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>assets/media/foi-logo.png"></a>
						<a href="//www.pco.gov.ph/" target="_blank"><img class="foot-img-logo" src="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>assets/media/pco.png"></a>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="position-relative clearfix">
						<h3 class="txt-color-primary">Quick Link</h3>
						<hr>
						<ul>
							<li><a href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#home" class="text-decoration-none text-light">Home</a></li>
							<li><a href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#services" class="text-decoration-none text-light">Services</a></li>
							<li><a href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#office" class="text-decoration-none text-light">Office</a></li>
							<li><a href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#transparency" class="text-decoration-none text-light">Transparency</a></li>
							<li><a href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#testimonial" class="text-decoration-none text-light">Testimonial</a></li>
							<li><a href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#about" class="text-decoration-none text-light">About</a></li>
							<li><a href="<?php if ( $the_homepage == 1) { } else { echo trim($domainhome).'/'; } ?>#contact" class="text-decoration-none text-light">Contact</a></li>
							<?php 
								try {
									$cnn = new PDO("mysql:host={$host};", $uname, $pw);
									$cnn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

									$sql = "use {$db}";
									$cnn->exec($sql);
								} catch(PDOException $e) {
									echo "<li><a href='{$domainhome}/install' class='text-decoration-none text-light'>CreaDB</a></li>";
								}
							?>
						</ul>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="position-relative clearfix">
						<h3 class="txt-color-primary">Gov't Link</h3>
						<hr>
						<a class="text-decoration-none" href="//e.bir.gov.ph" target="_blank">eGOV</a> | 
						<a class="text-warning text-decoration-none" href="//www.bir.gov.ph" target="_blank">BIR</a> | 
						<a class="text-decoration-none" href="//dict.gov.ph" target="_blank">DICT</a> | 
						<a class="text-decoration-none" href="//pnpclearance.ph/register" target="_blank">PNP</a> | 
						<a class="text-warning text-decoration-none" href="//clearance.nbi.gov.ph/" target="_blank">NBI</a> | 
						<a class="text-light text-decoration-none" href="//www.gppb.gov.ph/" target="_blank">GPPB</a> | 
						<a class="text-decoration-none" href="//philgeps.gov.ph/" target="_blank">PhilGEPS</a> | 
						<a class="text-light text-decoration-none" href="//www.coa.gov.ph/" target="_blank">COA</a> | 
						<a class="text-light text-decoration-none" href="//www.dbm.gov.ph/" target="_blank">DBM</a>
					</div>
				</div>

				<div class="col-md-3 mb-5">
					<div class="position-relative clearfix">
						<h3 class="txt-color-primary">Office Hours</h3>
						<hr>
						<p>
							Monday to Thursday <br> 
							<b>9:00 AM to 5:00 PM</b>
						</p>

						<p>
							Saturday and Sunday <br> 
							<b>CLOSED</b>
						</p>
					</div>
				</div>
			</div>
		</div>
	</section>