<?php
require_once 'inc/utils.php';
$pageTitle = 'Register';
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
							<form method="post" onsubmit="return false;" id="registerForm">
								<input type="hidden" name="action" id="action" value="register">
								<h2 class="registration-title text-center">Sign in to continue</h2>
								<div class="form-group mt-4">
									<label class="form-label">First Name</label>
									<input type="fname" class="form-control h_50" id="fname" name="fname" placeholder="First Name">
								</div>
								<div class="form-group mt-4">
									<label class="form-label">Last Name</label>
									<input type="lname" class="form-control h_50" id="lname" name="lname" placeholder="Last Name">
								</div>
								<div class="form-group mt-4">
									<label class="form-label">Email Address</label>
									<input type="email" class="form-control h_50" id="email" name="email" placeholder="Email Address">
								</div>
								<div class="form-group mt-4">
									<label class="form-label">Password</label>
									<input type="password" class="form-control h_50" id="password1" name="password1" placeholder="Password">
								</div>
								<div class="form-group mt-4">
									<label class="form-label">Comfirm Password</label>
									<input type="password" class="form-control h_50" id="password2" name="password2" placeholder=" Confirm Password">
								</div>
								<button class="main-btn btn-hover w-100 mt-4" type="button" id="btnSubmit">Sign Up <i class="fas fa-sign-in-alt ms-2"></i></button>
							</form>
							
							<div class="mt-4">
								Already have an account?<a class="signup-link" href="login">Sign in</a>
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
$('#registerForm #btnSubmit').click(function()
{
	var formId = '#registerForm';
	var fname = $(formId+' #fname').val();
	var lname = $(formId+' #lname').val();
	var email = $(formId+' #email').val();
	var password1 = $(formId+' #password1').val();
	var password2 = $(formId+' #password2').val();

	if ((fname.length < 3 || lname.length < 3) || (fname.length > 50 || lname.length > 50))
	{
		throwError('Name is invalid');
	}
	else if (email.length < 13 || email.length > 100)
	{
		throwError('Email is incorrect');
	}
	else if (password1.length < 6)
	{
		throwError('Password must contain at least 6 characters');
	}
	else if (password1 != password2)
	{
		throwError('Passwords do not match');
	}
	else
	{
		var registerForm = $("#registerForm");
		$.ajax({
			url: 'inc/actions',
			type: 'POST',
			dataType: 'json',
			data: registerForm.serialize(),
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
					throwSuccess('Registration successful! Logging you in...');
					registerForm[0].reset();
					//redirect to dashboard
					window.location.href = "dashboard";
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