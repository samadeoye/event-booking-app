<?php
$isAdminPage = checkIfAdminPage();
if ($isAdminPage)
{
	if (!isset($_SESSION['admin']))
	{
		blockOutToMainPage();
	}
}
?>

<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, shrink-to-fit=9">
	<meta name="description" content="<?php echo SITE_NAME; ?> - <?php echo $pageTitle; ?>">
	<meta name="author" content="<?php echo SITE_AUTHOR; ?>">
	<base href="<?php echo DEF_ROOT_PATH; ?>/">
	<title><?php echo SITE_NAME; ?> - <?php echo $pageTitle; ?></title>
	<!-- Favicon Icon -->
	<link rel="icon" type="image/png" href="assets/images/favicon.png">
	<!-- Stylesheets -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&amp;display=swap" rel="stylesheet">
	<link href="assets/vendor/unicons-2.0.1/css/unicons.css' rel='stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">
	<link href="assets/css/responsive.css" rel="stylesheet">
	<link href="assets/css/night-mode.css" rel="stylesheet">
	<!-- Vendor Stylesheets -->
	<link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
	<link href="assets/vendor/OwlCarousel/assets/owl.carousel.css" rel="stylesheet">
	<link href="assets/vendor/OwlCarousel/assets/owl.theme.default.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<!-- Toast Alert -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

	<!-- To include the below only for admin pages -->
	<?php
	if ($isAdminPage)
	{
		echo <<<EOQ
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.0/dist/css/datepicker.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">
EOQ;
	}
	?>

	<?php
	if (count($arAdditionalCSS) > 0)
	{
		echo implode("\n", $arAdditionalCSS);
	}
	?>
</head>

<body class="d-flex flex-column h-100">
	<!-- Header Start-->
	<header class="header">
		<div class="header-inner">
			<nav class="navbar navbar-expand-lg bg-barren barren-head navbar fixed-top justify-content-sm-start pt-0 pb-0">
				<div class="container-fluid ps-0">
					<?php
					if ($isAdminPage)
					{
						echo <<<EOQ
<button type="button" id="toggleMenu" class="toggle_menu">
	<i class="fa-solid fa-bars-staggered"></i>
</button>
<button id="collapse_menu" class="collapse_menu me-4">
	<i class="fa-solid fa-bars collapse_menu--icon "></i>
	<span class="collapse_menu--label"></span>
</button>
<button class="navbar-toggler adminNavbarToggler order-3 ms-2 pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
	<span class="navbar-toggler-icon"></span>
</button>
EOQ;
					}
					else
					{
						echo <<<EOQ
<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
	<span class="navbar-toggler-icon"></span>
</button>
EOQ;
					}
					?>
					
					<a class="navbar-brand order-1 order-lg-0 ml-lg-0 ml-2 me-auto" href="<?php echo DEF_ROOT_PATH; ?>">
						<div class="res-main-logo">
							<img src="assets/images/logo.png" alt="">
						</div>
						<div class="main-logo" id="logo">
							<img src="assets/images/logo.png" alt="">
							<img class="logo-inverse" src="assets/images/logo.png" alt="">
						</div>
					</a>
					<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
						<div class="offcanvas-header">
							<div class="offcanvas-logo" id="offcanvasNavbarLabel">
								<img src="assets/images/logo.png" alt="">
							</div>
							<button type="button" class="close-btn" data-bs-dismiss="offcanvas" aria-label="Close">
								<i class="fa-solid fa-xmark"></i>
							</button>
						</div>
						<div class="offcanvas-body">
							<div class="offcanvas-top-area">
								<div class="create-bg">
									<a href="<?php echo MAIN_SITE_URL; ?>" class="offcanvas-create-btn">
										<i class="fas fa-home"></i>
										<span>Main Website</span>
									</a>
								</div>
							</div>							
							<ul class="navbar-nav justify-content-end flex-grow-1 pe_5">
								<li class="nav-item">
									<a class="nav-link active" aria-current="page" href="<?php echo DEF_ROOT_PATH; ?>">Events</a>
								</li>
							</ul>
						</div>
						<div class="offcanvas-footer">
							<div class="offcanvas-social">
								<h5>Follow Us</h5>
								<ul class="social-links">
									<li><a href="<?php echo SITE_YOUTUBE; ?>" class="social-link"><i class="fab fa-youtube"></i></a>
									<li><a href="<?php echo SITE_INSTAGRAM; ?>" class="social-link"><i class="fab fa-instagram"></i></a>
									<li><a href="<?php echo SITE_FACEBOOK; ?>" class="social-link"><i class="fab fa-facebook-square"></i></a>
								</ul>
							</div>
						</div>
					</div>

					<?php
					if ($isAdminPage)
					{
						echo <<<EOQ
<div class="right-header order-2">
	<ul class="align-self-stretch">
		<li class="dropdown account-dropdown">
			<a href="#" class="account-link" role="button" id="accountClick" data-bs-auto-close="outside" data-bs-toggle="dropdown" aria-expanded="false">
				<img src="assets/images/profile-img.png" alt="{$arUser['fullname']}">
				<i class="fas fa-caret-down arrow-icon"></i>
			</a>
			<ul class="dropdown-menu dropdown-menu-account dropdown-menu-end" aria-labelledby="accountClick">
				<li>
					<div class="dropdown-account-header">
						<div class="account-holder-avatar">
							<img src="assets/images/profile-img.png" alt="{$arUser['fullname']}">
						</div>
						<h5>{$arUser['fullname']}</h5>
						<p>{$arUser['email']}</p>
					</div>
				</li>
				<li class="profile-link">
					<a href="dashboard" class="link-item">Dashboard</a>
					<a href="dashboardprofile" class="link-item">Profile</a>									
					<a href="javascript:;" class="link-item btnLogoutSidebar">Sign Out<a>									
				</li>
			</ul>
		</li>
	</ul>
</div>
EOQ;
					}
					?>
				</div>
			</nav>
			<div class="overlay"></div>
		</div>
	</header>
	<!-- Header End-->