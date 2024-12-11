<?php
$arCurrentPage = getCurrentPage($pageTitle);
?>

<!-- Default Modal -->
<div class="modal fade" id="defaultModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="defaultModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Small Modal -->
<div class="modal fade" id="smallModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Extra Large Modal -->
<div class="modal fade" id="extraLargeModal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-labelledby="extraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Large Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" data-focus="false" data-keyboard="false" data-backdrop="static" aria-labelledby="largeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> </div>
    </div>
</div>

<!-- Left Sidebar Start -->
<nav class="vertical_nav">
    <div class="left_section menu_left" id="js-menu">
        <div class="left_section">
            <ul>
                <li class="menu--item">
                    <a href="dashboard" class="menu--link <?php echo $arCurrentPage['dashboard'];?>" title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                        <i class="fa-solid fa-gauge menu--icon"></i>
                        <span class="menu--label">Dashboard</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="dashboardevents" class="menu--link <?php echo $arCurrentPage['dashboardevents'];?>" title="Events" data-bs-toggle="tooltip" data-bs-placement="right">
                        <i class="fa-solid fa-calendar-days menu--icon"></i>
                        <span class="menu--label">Events</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="dashboardbookings" class="menu--link <?php echo $arCurrentPage['dashboardbookings'];?>" title="Bookings" data-bs-toggle="tooltip" data-bs-placement="right">
                        <i class="fa-solid fa-calendar-check menu--icon"></i>
                        <span class="menu--label">Bookings</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="dashboardprofile" class="menu--link <?php echo $arCurrentPage['dashboardprofile'];?>" title="Profile" data-bs-toggle="tooltip" data-bs-placement="right">
                        <i class="fa-solid fa-user menu--icon"></i>
                        <span class="menu--label">Profile</span>
                    </a>
                </li>
                <li class="menu--item">
                    <a href="javascript:;" class="menu--link btnLogoutSidebar" title="Logout" data-bs-toggle="tooltip" data-bs-placement="right">
                        <i class="fa-solid fa-right-from-bracket menu--icon"></i>
                        <span class="menu--label">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Left Sidebar End -->