<?php if($this->session->flashdata('show_success_login_popup')){ ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#siginModal").modal("show");
});
</script>
<?php } ?>
<?php if($this->session->flashdata('show_verfy_popup')){ ?>
<script type="text/javascript">
$(document).ready(function(){
	$("#verification_user_id").val("<?=$this->session->flashdata('user_id')?>")
	$("#verificationFormModal").modal("show");
});
</script>
<?php } ?>
<!--SignIn Modal-->
<div id="siginModal" class="modal" role="dialog">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Sign in to Alil</h4>
		</div>
		<div class="modal-body loginmodal-container">
			<form class="loginAjaxForm" id="loginAjaxForm" action="<?=base_url("api/v1/user/login")?>" method="post">
				<?php $this->load->view('alerts'); ?>
				<input type="text" name="username" placeholder="Email address or Username" class="required" />
				<input type="password" name="password" placeholder="Password" class="required" />
				<button type="submit" class="login submit-button loginmodal-submit">Login</button>
			</form>
			<div class="login-help">
				<a href="javascript:void(0);" class="forgot">Forgot Password</a>
			</div>
		</div>
    </div>
  </div>
</div>
<!--Verification page-->
<div id="verificationFormModal" class="modal" role="dialog">
	<div class="modal-dialog  modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Verification Code</h4>
			</div>
			<div class="modal-body loginmodal-container">
				<div class="verfication_style">
					<strong></strong><br />
					<p class="text-justify">Check your inbox for a verification email
						from Alil. Please follow the instructions in that
						email or enter your verification code below.
					</p>
				</div>
				<form action="<?php echo base_url('api/v1/user/verify-code'); ?>" method="post" id="verificationForm" class="form-inline">
					<?php $this->load->view('alerts'); ?>
					<input type="hidden" name="user_id" value="" id="verification_user_id">
					<input type="hidden" name="process" value="" id="verification_process">
					<div class="form-group hp_global_search">
						<div class="input-group">
							<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 no-right-padding">
								<input type="text" class="form-control" id="vcode" name="vcode" autocomplete="off">
							</div>
							<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
								<span class="input-group-btn">
									<button class="btn submit-button verfication-button" type="submit">Go!</button>
								</span>
							</div>
						</div>
					</div>
				</form>
				<div class="fb_decsription">
					Please check your spam/bulk folder in case our confirmation mail not found in your inbox.
				</div> 
				<div class="fb_decsription">
					No luck? <a href="javascript:void(0);" id="resendVerficationCode">Resend verification email</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!--#Verification page-->
<!--Success page-->
<div id="loginSuccessModal" class="modal" role="dialog">
	<div class="modal-dialog  modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Welcome!</h4>
			</div>
			<div class="modal-body loginmodal-container">
				<p>Your email have been verified and you are logged in successfully!.</p>
			</div>
		</div>
	</div>
</div>
<!--#Success page-->
<!--Alerts page-->
<div id="alertsModal" class="modal" role="dialog">
	<div class="modal-dialog  modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Organization Approval!</h4>
			</div>
			<div class="modal-body loginmodal-container">
				<p>Your email have been verified and you are logged in successfully!.</p>
			</div>
		</div>
	</div>
</div>
<!--#Alerts page-->
<!--Forget Modal-->
<div id="forgotModal" class="modal" role="dialog">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Forgot Password</h4>
		</div>
		<div class="modal-body loginmodal-container">
			<form action="<?php echo base_url('api/v1/user/forgot-password'); ?>" id="forgotAjaxForm" method="post">
				<?php $this->load->view('alerts'); ?>
				<input type="text" name="email" placeholder="Email address" />
				<input type="submit" class="login loginmodal-submit submit-button" value="Submit">
			</form>
		</div>
    </div>
  </div>
</div>
<div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel">
	<div class="modal-dialog  modal-md">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Edit Review</h4>
		</div>
		<div class="modal-body loginmodal-container">
			
		</div>
    </div>
  </div>
</div>