<?php
require_once 'inc/utils.php';
$pageTitle = 'Dashboard Events';
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
                        <h3><i class="fa-solid fa-calendar-days me-3"></i>Events</h3>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-tabs">
                                <!-- <div class="card-header">
                                    <h3>All Events</h3>
                                </div> -->
                                <div class="m-3">
                                    <button class="button-theme" id="btnAddNewEvent"><i class="fa-solid fa-plus"></i> Add New</button>
                                    <button class="button-theme btn-sm float-end" id="btnReloadEventsTable"><i class="fa-solid fa-rotate-right"></i> Reload</button>
                                </div>
                                <div class="card-body">
                                    <table id="eventsTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Price</th>
                                                <th>Image</th>
                                                <th>Created Date</th>
                                                <th>Modified Date</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Price</th>
                                                <th>Image</th>
                                                <th>Created Date</th>
                                                <th>Modified Date</th>
                                                <th></th>
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
<script src="assets/js/vertical-responsive-menu.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
EOQ;

$arAdditionalJs[] = <<<EOQ
function editEvent(id)
{
    showModal('inc/popup/event?id='+id+'&action=updateevent', 'largeModal');
}

function deleteEvent(id)
{
    Swal.fire({
        title: '',
        text: 'Are you sure you want to delete this event?',
        icon: 'error',
        showCancelButton: true,
        reverseButtons: true,
        confirmButtonText: 'Delete',
        confirmButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed)
        {
            $.ajax({
                url: 'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: {
                    'id': id,
                    'action': 'deleteevent'
                },
                success: function(data) {
                    if (data.status == true) {
                        throwSuccess('Deleted successfully');
                        reloadTable('eventsTable');
                    }
                    else {
                        throwError(data.msg);
                    }
                }
            });
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
var eventsTable = $('#eventsTable').DataTable({
    processing: true,
    autoWidth: false,
    responsive: true,
    ajax: 'inc/actions?action=getevents',
    columns: [
        { data: 'sn' },
        { data: 'title' },
        { data: 'date' },
        { data: 'time' },
        { data: 'price' },
        { data: 'img' },
        { data: 'cdate' },
        { data: 'mdate' },
        { data: 'edit' },
        { data: 'delete' }
    ],
    columnDefs: [
        {"orderable": false, "targets": [8,9]}
    ],
    pageLength: 50,
});

$('#btnReloadEventsTable').click(function() {
    reloadTable('eventsTable');
});

$('#btnAddNewEvent').click(function() {
    showModal('inc/popup/event?action=addevent', 'largeModal');
});
EOQ;

require_once 'inc/foot.php';
?>