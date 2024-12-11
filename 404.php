﻿<?php
require_once 'inc/utils.php';
$pageTitle = 'Event Center';
require_once 'inc/head.php';
?>
<!-- Body Start-->
<div class="wrapper">
	<div class="breadcrumb-block">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-10">
					<div class="barren-breadcrumb">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH; ?>">Home</a></li>
								<li class="breadcrumb-item active" aria-current="page">Error 404</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="event-dt-block p-80">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-6 col-lg-8">
					<div class="error-404-content text-center">
						<h2>404</h2>
						<h4>Oops! Page not found</h4>
						<p>Seems you're looking for something that doesn't exist.</p>
						<a href="<?php echo DEF_ROOT_PATH; ?>" class="main-btn btn-hover h_50"><i class="fa-solid fa-house me-3"></i>Back to home</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Body End-->
	
<?php
require_once 'inc/foot.php';
?>