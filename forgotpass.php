<?php
require_once 'inc/utils.php';
$pageTitle = 'Reset Password';
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
							<form method="post" onsubmit="return false;" id="forgotPassForm">
								<input type="hidden" name="action" id="action" value="forgotpassverifyemail">
								<h2 class="registration-title text-center">Enter your registered email</h2>
								<div class="form-group mt-5">
									<label class="form-label">Your Email</label>
									<input type="email" class="form-control h_50" id="email" name="email" placeholder="Email">
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
$('#forgotPassForm #btnSubmit').click(function()
{
	var formId = '#forgotPassForm';
	var email = $(formId+' #email').val();

	if (email.length < 13)
	{
		throwError('Please enter a valid email');
	}
	else
	{
		var form = $('#forgotPassForm');
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
					throwSuccess('Password reset link has been sent to your email: '+ email);
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