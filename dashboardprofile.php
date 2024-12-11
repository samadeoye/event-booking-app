<?php
require_once 'inc/utils.php';
$pageTitle = 'Dashboard Profile';
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
                        <h3><i class="fa-solid fa-user me-3"></i>Profile</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mt-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title">Update Profile</h4>
                            </div>
                            <div class="card-body">
                                <form method="post" onsubmit="return false;" id="profileForm">
                                    <input type="hidden" name="action" id="action" value="updateprofile">
                                    <div class="form-group mb-3">
                                        <label for="fname">First Name</label>
                                        <input type="text" id="fname" name="fname" class="form-control" value="<?php echo $arUser['first_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="lname">Last Name</label>
                                        <input type="text" id="lname" name="lname" class="form-control" value="<?php echo $arUser['last_name']; ?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" id="email" name="email" class="form-control" value="<?php echo $arUser['email']; ?>" readonly>
                                    </div>
                                    <div class="form-group mt-4">
                                        <input type="submit" value="Save Changes" class="btn btn-success" id="btnSubmit">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title">Change Password</h4>
                            </div>
                            <div class="card-body">
                                <form method="post" onsubmit="return false;" id="changePasswordForm">
                                    <input type="hidden" name="action" value="changepassword">
                                    <div class="form-group mb-3">
                                        <label for="currentPassword">Current Password</label>
                                        <input type="password" id="currentPassword" name="currentPassword" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" id="newPassword" name="newPassword" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="confirmPassword">Confirm New Password</label>
                                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control">
                                    </div>
                                    <div class="form-group mt-4">
                                        <input type="submit" value="Save Changes" class="btn btn-success" id="btnSubmitChangePass">
                                    </div>
                                </form>
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

$arAdditionalJsOnLoad[] = <<<EOQ
$('#profileForm #btnSubmit').click(function ()
{
    var formId = '#profileForm';
    var fname = $(formId+' #fname').val();
    var lname = $(formId+' #lname').val();

    if (fname.length < 3 || lname.length < 3)
    {
        throwError('Please fill all required fields');
    }
    else
    {
        var form = $("#profileForm");
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
            success: function(data)
            {
                if(data.status == true)
                {
                    throwSuccess('Profile updated successfully!');
                    $(formId+' #fname').val(data.data['first_name']);
                    $(formId+' #lname').val(data.data['last_name']);
                }
                else
                {
                    throwError(data.msg);
                }
            }
        });
    }
});

$('#changePasswordForm #btnSubmitChangePass').click(function ()
{
    var formId = '#changePasswordForm';
    var currentPassword = $(formId+' #currentPassword').val();
    var newPassword = $(formId+' #newPassword').val();
    var confirmPassword = $(formId+' #confirmPassword').val();

    if (currentPassword.length < 6 || newPassword.length < 6 || confirmPassword.length < 6)
    {
        throwError('Password must contain at least 6 characters');
    }
    else if (newPassword != confirmPassword)
    {
        throwError('Passwords do not match');
    }
    else
    {
        var form = $("#changePasswordForm");
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
            success: function(data)
            {
                if (data.status)
                {
                    throwSuccess('Password changed successfully!');
                    form[0].reset();
                }
                else
                {
                    throwError(data.msg);
                }
            }
        });
    }
});
EOQ;

require_once 'inc/foot.php';
?>