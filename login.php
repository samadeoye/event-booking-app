<?php
require_once 'inc/utils.php';
$pageTitle = 'Login';
$arAdditionalCSS[] = <<<EOQ

EOQ;
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
							<form method="post" onsubmit="return false;" id="loginForm">
								<input type="hidden" name="action" id="action" value="login">
								<h2 class="registration-title text-center">Sign in to continue</h2>
								<div class="form-group mt-5">
									<label class="form-label">Your Email</label>
									<input type="email" class="form-control h_50" id="email" name="email" placeholder="Enter your email">
								</div>
								<div class="form-group mt-4">
									<div class="field-password">
										<label class="form-label">Password</label>
										<a class="forgot-pass-link" href="forgotpass">Forgot Password?</a>
									</div>
									<div class="loc-group position-relative">
										<input type="password" class="form-control h_50" id="password" name="password" placeholder="Enter your password">
									</div>
								</div>
								<button class="main-btn btn-hover w-100 mt-4" type="button" id="btnSubmit">Sign In <i class="fas fa-sign-in-alt ms-2"></i></button>
							</form>
							
							<div class="mt-4">
								Don't have an account?<a class="signup-link" href="register">Sign up</a>
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
$('#loginForm #btnSubmit').click(function ()
{
	var formId = '#loginForm';
	var email = $(formId+' #email').val();
	var password = $(formId+' #password').val();

	if (email.length < 13 || email.length > 100)
	{
		throwError('Email is invalid');
	}
	else if (password.length < 6)
	{
		throwError('Password is invalid');
	}
	else
	{
		var loginForm = $("#loginForm");
		$.ajax({
			url: 'inc/actions',
			type: 'POST',
			dataType: 'json',
			data: loginForm.serialize(),
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
					throwSuccess('Login successful! Logging you in...');
					loginForm[0].reset();
					//redirect to dashboard
					window.location.href = 'dashboard';
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