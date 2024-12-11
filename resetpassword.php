<?php
require_once 'inc/utils.php';
$pageTitle = 'Reset Password';

$redirect = true;
$token = isset($_GET['token']) ? trim($_GET['token']) : '';
if ($token != '')
{
    if (strlen($token) == 36)
    {
        $redirect = false;
    }
}
if ($redirect)
{
    header('location: forgotpass');
}

require_once 'inc/head.php';
?>
		
<div class="form-wrapper mt-4">
	<div class="app-form">
		<div class="app-form-content">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-lg-10 col-md-10">
						<div class="app-top-items">
							<a href="index-2.html">
								<div class="sign-logo" id="logo">
									<img src="assets/images/logo.png" alt="">
								</div>
							</a>
						</div>
					</div>
					<div class="col-xl-5 col-lg-6 col-md-7">
						<div class="registration">
							<form method="post" onsubmit="return false;" id="resetPasswordForm">
								<input type="hidden" name="action" id="action" value="resetpassword">
								<input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
								<h2 class="registration-title text-center">Enter new password to complete your password reset</h2>
								<div class="form-group mt-5">
									<label class="form-label">New Password</label>
									<input type="password" class="form-control h_50" id="password" name="password" placeholder="New Password">
								</div>
								<div class="form-group mt-5">
									<label class="form-label">Confirm Password</label>
									<input type="password" class="form-control h_50" id="passwordConfirm" name="passwordConfirm" placeholder="Confirm Password">
								</div>
								<button class="main-btn btn-hover w-100 mt-4" type="button" id="btnSubmit">Proceed <i class="fas fa-sign-in-alt ms-2"></i></button>
							</form>
							
							<div class="mt-4">
								Back to Sign in?<a class="signup-link" href="login">Sign in</a>
							</div>
						</div>							
					</div>
				</div>
			</div>
		</div>			
	</div>
</div>

<?php
$arAdditionalJsOnLoad[] = <<<EOQ
$('#resetPasswordForm #btnSubmit').click(function()
{
	var formId = '#resetPasswordForm';
	var password = $(formId+' #password').val();
	var passwordConfirm = $(formId+' #passwordConfirm').val();

	if (password.length < 6)
	{
		throwError('Please enter a valid password');
	}
	else if (password != passwordConfirm)
	{
		throwError('Passwords do not match');
	}
	else
	{
		var form = $('#resetPasswordForm');
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
					throwSuccess('Password reset successfully! Proceed to login.');
					form[0].reset();
					window.location.href = 'login';
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