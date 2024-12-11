<?php
require_once 'inc/utils.php';
$pageTitle = 'Dashboard Bookings';
$arAdditionalCSS[] = <<<EOQ
<link href="assets/css/vertical-responsive-menu.min.css" rel="stylesheet">
<link href="assets/css/analytics.css" rel="stylesheet">
<link href="assets/vendor/chartist/dist/chartist.min.css" rel="stylesheet">
<link href="assets/vendor/chartist-plugin-tooltip/dist/chartist-plugin-tooltip.css" rel="stylesheet">
<link href="assets/css/datepicker.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
EOQ;
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
                        <h3><i class="fa-solid fa-calendar-days me-3"></i>Bookings</h3>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-tabs">
                                <div class="m-3">
                                    <button class="button-theme btn-sm float-end" id="btnReloadBookingsTable"><i class="fa-solid fa-rotate-right"></i> Reload</button>
                                </div>
                                <div class="card-body">
                                    <table id="bookingsTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ticket Number</th>
                                                <th>Event</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Amount</th>
                                                <th>Booking Date</th>
                                                <th>Paid</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Ticket Number</th>
                                                <th>Event</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Amount</th>
                                                <th>Booking Date</th>
                                                <th>Paid</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
EOQ;

$arAdditionalJs[] = <<<EOQ
function checkIn(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to check-in this participant?',
        icon: 'success',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Check-in',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed)
        {
            checkinAction('validatecheckin', id);
        }
    });
}

function checkinAction(action, id)
{
    $.ajax({
        url: 'inc/actions',
        type: 'POST',
        dataType: 'json',
        data: {
            'id': id,
            'action': action
        },
        success: function(data) {
            if (data.status == true) {
                var datemismatched = false;
                if (data.datemismatched != undefined)
                {
                    if (data.datemismatched != '')
                    {
                        datemismatched = data.datemismatched;
                    }
                }

                if (datemismatched)
                {
                    Swal.fire({
                        title: '',
                        text: 'Event date does not match the current date. Do you want to continue?',
                        icon: 'info',
                        showCancelButton: true,
                        reverseButtons: true,
                        confirmButtonText: 'Check-in',
                        confirmButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed)
                        {
                            checkinAction('checkinbooking', id);
                        }
                    });
                }
                else
                {
                    if (action == 'validatecheckin')
                    {
                        checkinAction('checkinbooking', id);
                    }
                    else
                    {
                        throwSuccess('Checked-in successfully');
                        reloadTable('bookingsTable');
                    }
                }
            }
            else {
                throwError(data.msg);
            }
        }
    });
}
EOQ;

$arAdditionalJsScripts[] = <<<EOQ
<script src="assets/js/vertical-responsive-menu.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.4/dist/autoNumeric.min.js"></script>
EOQ;

$arAdditionalJsOnLoad[] = <<<EOQ
var bookingsTable = $('#bookingsTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: 'inc/actions?action=getbookings',
    columns: [
        { data: 'sn' },
        { data: 'reference' },
        { data: 'event' },
        { data: 'fullname' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'gender' },
        { data: 'age' },
        { data: 'amount' },
        { data: 'cdate' },
        { data: 'paid' },
        { data: 'checkin' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [10]}
    ],
    pageLength: 50,
});

$('#btnReloadBookingsTable').click(function() {
    reloadTable('bookingsTable');
});
EOQ;

require_once 'inc/foot.php';
?>