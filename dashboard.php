<?php
require_once 'inc/utils.php';
$pageTitle = 'Dashboard';
$arAdditionalCSS[] = <<<EOQ
<link href="assets/css/vertical-responsive-menu.min.css" rel="stylesheet">
EOQ;

$arData = KpakpandoEventsBooking\Dashboard\Dashboard::getDashboardData();

require_once 'inc/head.php';
require_once 'inc/sidebar.php';
?>

<!-- Body Start -->
<div class="wrapper wrapper-body">
    <div class="dashboard-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-main-title">
                        <h3><i class="fa-solid fa-gauge me-3"></i>Dashboard</h3>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="main-card mt-4">
                        <div class="dashboard-wrap-content">
                            <div class="dashboard-report-content p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="dashboard-report-card purple">
                                            <div class="card-content">
                                                <div class="card-content">
                                                    <span class="card-title fs-6">Events</span>
                                                    <span class="card-sub-title fs-3"><?php echo $arData['numEvents']; ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <a href="dashboardevents" class="btn btn-dark dashboardViewMoreBtn">View More</a>
                                                    </div>
                                                </div>
                                                <div class="card-media">
                                                    <i class="fa-solid fa-calendar-days"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="dashboard-report-card info">
                                            <div class="card-content">
                                                <div class="card-content">
                                                    <span class="card-title fs-6">Bookings</span>
                                                    <span class="card-sub-title fs-3"><?php echo $arData['numBookings']; ?></span>
                                                    <div class="d-flex align-items-center">
                                                        <a href="dashboardbookings" class="btn btn-dark dashboardViewMoreBtn">View More</a>
                                                    </div>
                                                </div>
                                                <div class="card-media">
                                                    <i class="fa-solid fa-calendar-check"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Body End -->

<?php
$arAdditionalJsScripts[] = <<<EOQ
<script src="assets/js/vertical-responsive-menu.min.js"></script>
EOQ;

require_once 'inc/foot.php';
?>