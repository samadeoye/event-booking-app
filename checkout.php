<?php
require_once 'inc/utils.php';

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$redirectToHome = true;
$row = [];
$dateTime = $imgPath = $venue = $venueMap = $title = '';
$priceFormatted = '';
$price = 0;
if ($id != '')
{
    if (strlen($id) == 36)
    {
        $row = KpakpandoEventsBooking\Crud\Crud::getRecordInfo(
            DEF_TBL_EVENTS, $id
        );
        if ($row)
		{
            $redirectToHome = false;

            $title = $row['title'];
            $price = doTypeCastDouble($row['price']);
            $priceFormatted = getCurrencyAmount($price);
            $time = getFormattedDate(strtotime($row['time']), 'H:ia');

            $dateTime = KpakpandoEventsBooking\Event\Event::getEventFormattedEventDate([
                'datetype' => $row['datetype']
                , 'date' => $row['date']
                , 'dateFrom' => $row['datefrom']
                , 'dateTo' => $row['dateto']
                , 'time' => $time
            ]);
            
            $img = $row['img'];
            if ($img == '')
            {
                $img = 'default.jpg';
            }
            $imgPath = "assets/images/events/{$img}";
            $venue = $row['venue'];
            $venueMap = $row['map'];
        }
    }
}
if ($redirectToHome)
{
    header('location: ' . DEF_ROOT_PATH);
}

$redirectError = '';
if (isset($_SESSION['redirectError']))
{
    $redirectError = $_SESSION['redirectError'];
    unset($_SESSION['redirectError']);
}

$currencySymbol = DEF_CURRENCY_SYMBOL;

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
                                <li class="breadcrumb-item" aria-current="page"><?php echo $title; ?></li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="event-dt-block p-80">
        <div class="container">
            <form class="pt-3" id="bookingForm" method="post" action="inc/actions" onsubmit="return false;">
                <input type="hidden" id="action" name="action" value="bookticket">
                <input type="hidden" id="eventId" name="eventId" value="<?php echo $id; ?>">
                <!-- <input type="hidden" value="1" id="ticketQty" name="ticketQty"> -->
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="main-title checkout-title">
                            <h3>Order Information</h3>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-12 col-md-12">
                        <div class="checkout-block">
                            <div class="main-card">
                                <div class="bp-title">
                                    <h4>Participant information</h4>
                                </div>
                                <div class="bp-content bp-form">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group mt-4">
                                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                                <input class="form-control h_50" type="text" id="firstName" name="firstName">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group mt-4">
                                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                                <input class="form-control h_50" type="text" id="lastName" name="lastName">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group mt-4">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input class="form-control h_50" type="text" id="email" name="email">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group mt-4">
                                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                <input class="form-control h_50" type="text" id="phone" name="phone">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group main-form mt-4">
                                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                                <select class="selectpicker" data-size="5" id="gender" name="gender" title="Nothing selected" data-live-search="true">
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>								
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12">
                                            <div class="form-group mt-4">
                                                <label class="form-label">Age <span class="text-danger">*</span></label>
                                                <input class="form-control h_50" type="text" id="age" name="age">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-12 col-md-12">
                        <div class="main-card order-summary">
                            <div class="bp-title">
                                <h4>Booking Information</h4>
                            </div>
                            <div class="order-summary-content p_30">
                                <div class="event-order-dt">
                                    <div class="event-thumbnail-img">
                                        <img src="<?php echo $imgPath; ?>" alt="">
                                    </div>
                                    <div class="event-order-dt-content">
                                        <h5><?php echo $title; ?></h5>
                                        <span><?php echo $dateTime; ?></span>
                                        <div class="category-type"><?php echo $venue; ?></div>
                                    </div>
                                </div>
                                <div class="order-total-block">
                                    <div class="order-total-dt">
                                        <div class="order-text">Total Ticket(s)</div>
                                        <div class="quantity">
                                            <div class="counter" id="ticketQtyDiv">
                                                <span class="down" onClick='decreaseCount(event, this)'>-</span>
                                                <input type="text" value="1" id="ticketQty" name="ticketQty">
                                                <span class="up" id="increaseTicketQty">+</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-total-dt">
                                        <div class="order-text">Sub Total</div>
                                        <div class="order-number" id="subtotalAmt"><?php echo $priceFormatted; ?></div>
                                    </div>
                                    <div class="divider-line"></div>
                                    <div class="order-total-dt">
                                        <div class="order-text">Total</div>
                                        <input type="hidden" id="ticketAmt" name="ticketAmt" value="<?php echo $price; ?>">
                                        <div class="order-number ttl-clr" id="totalAmt"><?php echo $priceFormatted; ?></div>
                                    </div>
                                </div>
                                <div class="confirmation-btn">
                                    <button class="main-btn btn-hover h_50 w-100 mt-5" type="button" id="btnSubmit">Confirm & Pay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Body End-->

<?php
$arAdditionalJsScripts[] = <<<EOQ
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>
EOQ;

$arAdditionalJs[] = <<<EOQ
function increaseCount(a, b)
{
    var input = b.previousElementSibling;
    var value = parseInt(input.value, 10);
    value = isNaN(value) ? 0 : value;
    value++;
    input.value = value;

    getFormattedTotalAmount(value);
}

function decreaseCount(a, b)
{
    var input = b.nextElementSibling;
    var value = parseInt(input.value, 10);
    if (value > 1)
    {
        value = isNaN(value) ? 0 : value;
        value--;
        input.value = value;

        getFormattedTotalAmount(value);
    }
}

function getFormattedTotalAmount(value)
{
    var totalAmt = value * parseFloat('{$price}');
    $('#ticketAmt').val(totalAmt);

    var totalAmtFormatted = totalAmt.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    totalAmtFormatted = '{$currencySymbol}' + totalAmtFormatted;
    $('#subtotalAmt').text(totalAmtFormatted);
    $('#totalAmt').text(totalAmtFormatted);
    $('#totalPayableAmt').text(totalAmtFormatted);
}
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ

if ("{$redirectError}" != '')
{
    throwError("{$redirectError}");
}

$("#increaseTicketQty").on('click', function(){
    increaseCount(event, this);
});

$("#decreaseTicketQty").on('click', function(){
    decreaseCount(event, this);
});

$("#ticketQty").on('change', function(){
    var ticketQty = $("#ticketQty").val();
    if (ticketQty <= 0)
    {
        ticketQty = 1;
    }
    getFormattedTotalAmount(ticketQty);
});

new AutoNumeric('#age', {
    decimalPlaces: 0,
    digitGroupSeparator: '',
    minimumValue: '1'
});

var formId = '#bookingForm';
var form = $('#bookingForm');

$(formId+' #btnSubmit').click(function(){
    var firstName = $(formId+' #firstName').val();
    var lastName = $(formId+' #lastName').val();
    var email = $(formId+' #email').val();
    var phone = $(formId+' #phone').val();
    var gender = $(formId+' #gender').val();
    var age = $(formId+' #age').val();

    if (firstName.length < 3 || firstName.length > 50 || lastName.length < 3 || lastName.length > 50)
    {
        throwError('Name is invalid!');
        return false;
    }
    else if (email.length < 13 || email.length > 200)
    {
        throwError('Email is invalid!');
        return false;
    }
    else if (phone.length < 6 || phone.length > 14)
    {
        throwError('Phone is invalid!');
        return false;
    }
    else if (gender.length == 0)
    {
        throwError('Please select a gender!');
        return false;
    }
    else if (age.length == 0)
    {
        throwError("Please enter participant's age!");
        return false;
    }
    else
    {
        Swal.fire({
            title: '',
            text: 'Are you sure you want to confirm this booking?',
            icon: 'success',
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonText: 'Confirm',
        }).then((result) => {
            if (result.isConfirmed)
            {
                $.ajax({
                    url: 'inc/actions',
                    type: 'POST',
                    dataType: 'json',
                    data: form.serialize(),
                    beforeSend: function() {
                        enableDisableBtn(formId+' #btnSubmit', 0);
                    },
                    complete: function() {
                        enableDisableBtn(formId+' #btnSubmit', 1);
                    },
                    success: function(data) {
                        if (data.status == true)
                        {
                            throwSuccess(data.msg);
                            form[0].reset();

                            window.location.href = data.link;
                        }
                        else
                        {
                            throwError(data.msg);
                        }
                    }
                });
            }
        });
    }
});

EOQ;

require_once 'inc/foot.php';
?>