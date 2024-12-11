<?php
require_once 'inc/utils.php';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$redirectToHome = true;
$row = [];
$dateTime = $imgPath = $title = $priceFormatted = '';
$qty = 0;
if ($id != '')
{
    if (strlen($id) == 36)
    {
        $row = KpakpandoEventsBooking\Crud\Crud::getRecordInfo(
            DEF_TBL_BOOKINGS, $id, [
				'event_id'
				, 'fullname'
				, 'qty'
				, 'total_amount'
				, 'status'
			]
        );
		if ($row)
		{
			if ($row['status'] == 'approved')
			{
				$redirectToHome = false;

				$eventId = $row['event_id'];
				$fullName = $row['fullname'];
				$qty = doTypeCastInt($row['qty']);
				$priceFormatted = getCurrencyAmount($row['total_amount']);

				//get event info
				$rowEvent = KpakpandoEventsBooking\Crud\Crud::getRecordInfo(
					DEF_TBL_EVENTS, $eventId, [
						'title'
						, 'datetype'
						, 'date'
						, 'datefrom'
						, 'dateto'
						, 'time'
						, 'img'
					]
				);
				$title = $rowEvent['title'];
				$time = getFormattedDate(strtotime($rowEvent['time']), 'H:ia');
				$dateTime = KpakpandoEventsBooking\Event\Event::getEventFormattedEventDate([
					'datetype' => $rowEvent['datetype']
					, 'date' => $rowEvent['date']
					, 'dateFrom' => $rowEvent['datefrom']
					, 'dateTo' => $rowEvent['dateto']
					, 'time' => $time
				]);

				$img = $rowEvent['img'];
				if ($img == '')
				{
					$img = 'default.jpg';
				}
				$imgPath = "assets/images/events/{$img}";
			}
		}
    }
}
if ($redirectToHome)
{
    header('location: ' . DEF_ROOT_PATH);
}

$pageTitle = $title;
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
								<li class="breadcrumb-item active" aria-current="page">Booking Confirmed</li>
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
				<div class="col-xl-5 col-lg-7 col-md-10">
					<div class="booking-confirmed-content">
						<div class="main-card">
							<div class="booking-confirmed-top text-center p_30">
								<div class="booking-confirmed-img mt-4">
									<img src="assets/images/confirmed.png" alt="<?php echo $title; ?>">
								</div>
								<h4>Booking Confirmed</h4>
								<p class="ps-lg-4 pe-lg-4">We are pleased to inform you that your booking has been confirmed.</p>
							</div>
							<div class="booking-confirmed-bottom">
								<div class="booking-confirmed-bottom-bg p_30">
									<div class="event-order-dt">
										<div class="event-thumbnail-img">
											<img src="<?php echo $imgPath; ?>" alt="<?php echo $title; ?>">
										</div>
										<div class="event-order-dt-content">
											<h5><?php echo $title; ?></h5>
											<span><?php echo $dateTime; ?></span>
											<div class="buyer-name"><?php echo $fullName;?></div>
											<div class="booking-total-tickets">
												<i class="fa-solid fa-ticket rotate-icon"></i>
												<span class="booking-count-tickets mx-2"><?php echo $qty; ?></span>x Ticket
											</div>
											<div class="booking-total-grand">
												Total: <span><?php echo $priceFormatted; ?></span>
											</div>
										</div>
									</div>
									<a href="javascript:;" id="printTicket" class="main-btn btn-hover h_50 w-100 mt-5"><i class="fa-solid fa-ticket rotate-icon me-3"></i>Print Ticket</a>
								</div>
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
$('#printTicket').on('click', function(){
	window.open('inc/actions?action=printticket&id={$id}', '_blank');
});
EOQ;

require_once 'inc/foot.php';
?>