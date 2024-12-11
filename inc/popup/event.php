<?php
require_once '../utils.php';
use KpakpandoEventsBooking\Crud\Crud;
use KpakpandoEventsBooking\Event\Event;

$action = trim($_REQUEST['action']);
$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

$title = $dateFrom = $dateTo = $date = $time = $description = $venue = $map = '';
$dateType = $slots = '';
$price = 0;
$modalTitle = 'New Event';
$modalId = 'largeModal';
if ($action == 'updateevent')
{
    $modalTitle = 'Update Event';

    $row = Crud::getRecordInfo(
        DEF_TBL_EVENTS, $id
    );
    if ($row)
    {
        $title = $row['title'];
        $date = $row['date'];
        $dateFrom = $row['datefrom'];
        $dateTo = $row['dateto'];
        $time = $row['time'];
        $price = doTypeCastDouble($row['price']);
        $description = $row['description'];
        $venue = $row['venue'];
        $map = $row['map'];
        $dateType = $row['datetype'];
        $slots = $row['slots'];
    }
    else
    {
        //throw error and exit
        echo '<p class="p-3">An erorr occurred while loading details. Please refresh page and try again.</p>';
        exit;
    }
}
?>

<form class="pt-3" id="eventForm" method="post" action="inc/actions" onsubmit="return false;" enctype="multipart/form-data">
    <div class="modal-header">
        <h5 class="modal-title"><?php echo $modalTitle; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="<?php echo $action; ?>">
        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

        <div class="model-content main-form">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" id="title" value="<?php echo $title; ?>">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Description</label>
                        <div class="form-control" id="description"><?php echo $description;?></div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Venue</label>
                        <input type="text" class="form-control" name="venue" id="venue" value="<?php echo $venue; ?>">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Map</label>
                        <input type="text" class="form-control" name="venueMap" id="venueMap" value="<?php echo $map; ?>">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Featured Image</label>
                        <input type="file" class="form-control" name="img" id="img">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Date Type</label>
                        <select class="form-control" name="dateType" id="dateType">
                            <?php echo Event::getEventDateTypeOptions($dateType); ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" id="dateDiv">
                    <div class="form-group mt-4">
                        <label class="form-label">Date</label>
                        <div class="loc-group position-relative">
                            <input class="form-control h_50" data-language="en" type="text" placeholder="DD/MM/YYYY" value="<?php echo $date; ?>" name="date" id="date">
                            <span class="absolute-icon"><i class="fa-solid fa-calendar-days"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" id="dateFromDiv">
                    <div class="form-group mt-4">
                        <label class="form-label">Date From</label>
                        <div class="loc-group position-relative">
                            <input class="form-control h_50" data-language="en" type="text" placeholder="DD/MM/YYYY" value="<?php echo $dateFrom; ?>" name="dateFrom" id="dateFrom">
                            <span class="absolute-icon"><i class="fa-solid fa-calendar-days"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12" id="dateToDiv">
                    <div class="form-group mt-4">
                        <label class="form-label">Date To</label>
                        <div class="loc-group position-relative">
                            <input class="form-control h_50 datepicker-here" data-language="en" type="text" placeholder="DD/MM/YYYY" value="<?php echo $dateTo; ?>" name="dateTo" id="dateTo">
                            <span class="absolute-icon"><i class="fa-solid fa-calendar-days"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Time</label>
                        <input type="text" class="form-control" name="time" id="time" value="<?php echo $time; ?>">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Price</label>
                        <input type="text" class="form-control" name="price" id="price" value="<?php echo $price; ?>">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="form-group mt-4">
                        <label class="form-label">Slots Available</label>
                        <input type="text" class="form-control" name="slots" id="slots" value="<?php echo $slots; ?>">
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    <div class="modal-footer">
        <button type="button" class="button-default" data-bs-dismiss="modal">Close</button>
        <button type="button" class="button-theme" id="btnSubmit">Submit</button>
    </div>
</form>

<script>
var formId = 'eventForm';
var modalId = '<?php echo $modalId; ?>';
$(document).ready(function() {

    toggleDateType();
    $('#dateType').on('change', function(){
        toggleDateType();
    });
    $('#time').timepicker({});

    new AutoNumeric('#price', {
        decimalPlaces: 2,
        digitGroupSeparator: '',
        decimalCharacter: '.',
    });
    new AutoNumeric('#slots', {
        decimalPlaces: 0,
        digitGroupSeparator: '',
        decimalCharacter: '.',
    });

    ClassicEditor
    .create(document.querySelector('#description'), {
        toolbar: [
            'heading', '|',
            'bold', 'italic', 'underline', 'link', '|',
            'bulletedList', 'numberedList', '|',
            'undo', 'redo'
        ]
    })
    .then(editor => {
        descriptionEditor = editor;
    })
    .catch( err => {
        console.error(err.stack);
    } );

    $('#'+formId+' #btnSubmit').click(function(){
        var title = $('#'+formId+' #title').val();
        var description = descriptionEditor.getData();
        var dateType = $('#'+formId+' #dateType').val();
        var date = $('#'+formId+' #date').val();
        var dateFrom = $('#'+formId+' #dateFrom').val();
        var dateTo = $('#'+formId+' #dateTo').val();

        if (title.length < 4 || title.length > 200)
        {
            throwError('Title is invalid!');
            return false;
        }
        else if (description.length < 10)
        {
            throwError('Description is invalid!');
            return false;
        }

        var dateSet = true;
        if (dateType == 'singleDate')
        {
            if (date.length != 10)
            {
                dateSet = false;
                throwError('Date is invalid!');
                return false;
            }
        }
        else
        {
            //date range
            if (dateFrom.length != 10 || dateTo.length != 10)
            {
                dateSet = false;
                throwError('Date is invalid!');
                return false;
            }
        }

        if (dateSet)
        {
            var formData = new FormData(this.form);
            formData.append('description', description);
            $.ajax({
                url: 'inc/actions',
                type: 'POST',
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 0);
                },
                complete: function() {
                    enableDisableBtn('#'+formId+' #btnSubmit', 1);
                },
                success: function(data) {
                    if (data.status == true)
                    {
                        throwSuccess(data.msg);
                        closeModal(modalId, true);
                        reloadTable('eventsTable');
                    }
                    else {
                        toastr.error(data.msg);
                    }
                }
            });
        }
    });

});

function toggleDateType()
{
    var dateType = $('#dateType').val();
    if (dateType == 'dateRange')
    {
        $('#dateFromDiv').show();
        $('#dateToDiv').show();
        $('#dateDiv').hide();

        $('#date').val('');

        new Datepicker($('#dateFrom')[0], {
            format: 'yyyy-mm-dd',
            autohide: true
        });
        new Datepicker($('#dateTo')[0], {
            format: 'yyyy-mm-dd',
            autohide: true
        });
    }
    else
    {
        //single date
        $('#dateDiv').show();
        $('#dateFromDiv').hide();
        $('#dateToDiv').hide();

        $('#dateFrom').val('');
        $('#dateTo').val('');

        new Datepicker($('#date')[0], {
            format: 'yyyy-mm-dd',
            autohide: true
        });
    }
}
</script>