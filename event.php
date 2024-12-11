<?php
require_once 'inc/utils.php';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$redirectToHome = true;
$row = [];
$dateTime = $imgPath = $venue = $venueMap = $title = '';
$dateJs =  $slotsAvl = '';
if ($id != '')
{
    if (strlen($id) == 36)
    {
        $redirectToHome = false;
        $row = KpakpandoEventsBooking\Crud\Crud::getRecordInfo(
            DEF_TBL_EVENTS, $id
        );
        $title = $row['title'];
        $date = $row['date'];
        $dateFrom = $row['datefrom'];
        $time = getFormattedDate(strtotime($row['time']), 'H:ia');

        $dateTime = KpakpandoEventsBooking\Event\Event::getEventFormattedEventDate([
            'datetype' => $row['datetype']
            , 'date' => $date
            , 'dateFrom' => $dateFrom
            , 'dateTo' => $row['dateto']
            , 'time' => $time
        ]);

        if ($date == '')
        {
            $date = $dateFrom;
        }

        $dateJs = getFormattedDate(strtotime($date), 'm/d/Y');
        $img = $row['img'];
        if ($img == '')
        {
            $img = 'default.jpg';
        }
        $imgPath = "assets/images/events/{$img}";
        $venue = $row['venue'];
        $venueMap = $row['map'];
        $slotsUsed = $row['slots_used'];
        $slots = $row['slots'];

        $slotsAvlDisplay = '';
        if ($slots != null && $slots != 0)
        {
            $slotsAvlDisplay = doTypeCastInt($slots) - doTypeCastInt($slotsUsed);
            if ($slotsAvlDisplay < 0)
            {
                $slotsAvlDisplay = 0;
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
                                <li class="breadcrumb-item"><a href="<?php echo DEF_ROOT_PATH; ?>">Explore Events</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="event-dt-block p-80">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="event-top-dts">
                        <div class="event-top-dt">
                            <h3 class="event-main-title"><?php echo $title; ?></h3>
                            <div class="event-top-info-status">
                                <?php
                                if ($venue != '')
                                {
                                    echo <<<EOQ
<span class="event-type-name"><i class="fa-solid fa-location-dot"></i>{$venue}</span>
EOQ;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7 col-md-12">
                    <div class="main-event-dt">
                        <div class="event-img">
                            <img src="<?php echo $imgPath; ?>" alt="<?php echo $title; ?>">		
                        </div>
                        <div class="share-save-btns dropdown">
                            <button class="sv-btn" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-share-nodes me-2"></i>Share</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;" onclick="shareOnFacebook()"><i class="fa-brands fa-facebook me-3"></i>Facebook</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="shareOnTwitter()"><i class="fa-brands fa-twitter me-3"></i>Twitter</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="shareOnLinkedIn()"><i class="fa-brands fa-linkedin-in me-3"></i>LinkedIn</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="shareOnWhatsApp()"><i class="fa-brands fa-whatsapp me-3"></i>WhatsApp</a></li>
                            </ul>
                        </div>
                        <div class="main-event-content">
                            <h4>About This Event</h4>
                            <?php echo $row['description']; ?>
                        </div>							
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-12">
                    <div class="main-card event-right-dt">
                        <div class="bp-title">
                            <h4>Event Details</h4>
                        </div>
                        <div class="time-left" id="time-left">
                            <div class="countdown" id="countdownDiv">
                                <div class="countdown-item">
                                    <span id="day"></span>days
                                </div>
                                <div class="countdown-item">
                                    <span id="hour"></span>Hours
                                </div>
                                <div class="countdown-item">
                                    <span id="minute"></span>Minutes
                                </div>
                                <div class="countdown-item">
                                    <span id="second"></span>Seconds
                                </div>
                            </div>
                        </div>
                        <div class="event-dt-right-group mt-5">
                            <div class="event-dt-right-icon">
                                <i class="fa-solid fa-circle-user"></i>
                            </div>
                            <div class="event-dt-right-content">
                                <h4>Organised by</h4>
                                <h5><?php echo SITE_NAME; ?></h5>
                            </div>
                        </div>
                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon">
                                <i class="fa-solid fa-calendar-day"></i>
                            </div>
                            <div class="event-dt-right-content">
                                <h4>Date and Time</h4>
                                <h5><?php echo $dateTime; ?></h5>
                            </div>
                        </div>
                        <div class="event-dt-right-group">
                            <div class="event-dt-right-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="event-dt-right-content">
                                <h4>Location</h4>
                                <h5 class="mb-0"><?php echo $row['venue']; ?></h5>
                                <?php
                                if ($venueMap != '')
                                {
                                    echo <<<EOQ
<a href="{$venueMap}"><i class="fa-solid fa-location-dot me-2"></i>View Map</a>
EOQ;
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        /*
                        if ($slotsAvlDisplay != '')
                        {
                            echo <<<EOQ
<div class="event-dt-right-group">
    <div class="event-dt-right-icon">
        <i class="fa-solid fa-ticket"></i>
    </div>
    <div class="event-dt-right-content">
        <h4>Slots Available</h4>
        <h5>{$slotsAvlDisplay}</h5>
    </div>
</div>
EOQ;
                        }
                        */
                        ?>
                        <div class="booking-btn">
                            <a href="checkout?id=<?php echo $id; ?>" class="main-btn btn-hover w-100">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Body End-->

<?php
$arAdditionalJs[] = <<<EOQ
const pageUrl = encodeURIComponent(window.location.href);
const pageTitle = "{$title}";

function shareOnFacebook()
{
    window.open('https://www.facebook.com/sharer/sharer.php?u='+pageUrl, '_blank');
}

function shareOnTwitter()
{
    window.open('https://twitter.com/intent/tweet?text='+pageTitle+'&url='+pageUrl, '_blank');
}

function shareOnLinkedIn()
{
    window.open('https://www.linkedin.com/sharing/share-offsite/?url='+pageUrl, '_blank');
}

function shareOnWhatsApp()
{
    window.open('https://api.whatsapp.com/send?text='+pageTitle+' - '+pageUrl, '_blank');
}
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
const second = 1000,
    minute = second * 60,
    hour = minute * 60,
    day = hour * 24;

const countDown = new Date("{$dateJs}").getTime(),
    x = setInterval(function()
    {
        const now = new Date().getTime(),
        distance = countDown - now;

        $("#day").text(Math.floor(distance / day));
        $("#hour").text(Math.floor((distance % day) / hour));
        $("#minute").text(Math.floor((distance % hour) / minute));
        $("#second").text(Math.floor((distance % minute) / second));

        if (distance < 0)
        {
            $("#time-left").append('<p class="p-3">Booking Ended!</p>');
            $("#countdownDiv").css("display", "none");
            clearInterval(x);
        }
    }, 0);
EOQ;

require_once 'inc/foot.php';
?>