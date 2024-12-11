<?php
require_once 'inc/utils.php';
use KpakpandoEventsBooking\Event\Event;

$redirectError = '';
if (isset($_SESSION['redirectError']))
{
    $redirectError = $_SESSION['redirectError'];
    unset($_SESSION['redirectError']);
}

$pageTitle = 'Event Center';
require_once 'inc/head.php';
?>

<!-- Body Start-->
<div class="wrapper">
	<div class="hero-banner">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-xl-7 col-lg-9 col-md-10">
					<div class="hero-banner-content">
						<h2><?php echo SITE_NAME; ?></h2>
						<p>Event Center</p>
						<a href="<?php echo MAIN_SITE_URL; ?>" class="main-btn btn-hover">Main Website <i class="fa-solid fa-arrow-right ms-3"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="explore-events p-80">
		<div class="container">
			<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="main-title">
						<h3>Upcoming Events</h3>
					</div>
				</div>
				<div class="col-xl-12 col-lg-12 col-md-12">
					<div class="event-filter-items">
						<div class="featured-controls">
							<div class="row">
								<?php
								$rows = Event::getUpcomingEvents([
									'id
									, title
									, price
									, datetype
									, date
									, datefrom
									, dateto
									, time
									, img'
									, 'slots'
									, 'slots_used'
								]);
								if (count($rows) > 0)
								{
									foreach ($rows as $row)
									{
										$id = $row['id'];
										$title = $row['title'];
										$price = getCurrencyAmount($row['price']);
										$date = $row['date'];
										$dateFrom = $row['datefrom'];
										$dateTo = $row['dateto'];
										$time = getFormattedDate(strtotime($row['time']), 'H:ia');
										$slotsUsed = $row['slots_used'];
										$slots = $row['slots'];

										$slotsAvlDisplay = '';
										if ($slots != null && $slots != 0)
										{
											$slotsAvl = doTypeCastInt($slots) - doTypeCastInt($slotsUsed);
											if ($slotsAvl < 0)
											{
												$slotsAvl = 0;
											}
											$slotsAvlDisplay = <<<EOQ
<i class="fa-solid fa-ticket fa-rotate-90"></i><span id="slotsAvl">{$slotsAvl} Remaining</span>
EOQ;
										}

										$date = Event::getEventFormattedEventDate([
											'datetype' => $row['datetype']
											, 'date' => $date
											, 'dateFrom' => $dateFrom
											, 'dateTo' => $dateTo
											, 'time' => $time
										]);

										$img = $row['img'];
										if ($img == '')
										{
											$img = "default.jpg";
										}
										$imgPath = "assets/images/events/{$img}";
										
										echo <<<EOQ
<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mix" data-ref="mixitup-target">
	<div class="main-card mt-4">
		<div class="event-thumbnail">
			<a href="event?id={$id}" class="thumbnail-img">
				<img src="{$imgPath}" alt="{$title}">
			</a>
		</div>
		<div class="event-content">
			<a href="event?id={$id}" class="event-title">{$title}</a>
			<div class="duration-price-remaining">
				<span class="duration-price">{$price}</span>
			</div>
		</div>
		<div class="event-footer">
			<div class="event-timing">
				<div class="publish-date">
					<span><i class="fa-solid fa-calendar-day me-2"></i>{$date}</span>
				</div>
			</div>
			<a class="main-btn btn-hover mt-4" href="event?id={$id}">Book Now</a>
		</div>
	</div>
</div>
EOQ;
									}
								}
								else
								{
									echo <<<EOQ
<div class="col-md-12">
	<p>No events at the moment!</p>
</div>
EOQ;
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Body End-->

<?php
$arAdditionalJsOnLoad[] = <<<EOQ

if ("{$redirectError}" != '')
{
    throwError("{$redirectError}");
}

EOQ;

require_once 'inc/foot.php';
?>