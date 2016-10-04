<div class="container register-form user_profile">
	<div class="col-sm-8 col-xs-12 col-md-8 col-lg-8 contact-form wow fadeInLeft text-left col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
		<form class="ajaxSubmit formValid" id="updatePassword" role="form" action="<?=base_url("api/v1/user/update-password")?>" method="put">
			<?php $this->load->view('alerts'); ?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label for="name">Current Password</label>
					<input type="password" name="current_password" id="current_password" class="required" placeholder="Current Password" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label for="name">New Password</label>
					<input type="password" name="new_password" id="new_password" class="required" placeholder="New Password" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<label for="name">Confirm Password</label>
					<input type="password" name="confirm_password" id="confirm_password" equalTo="#new_password" class="required" placeholder="Confirm Password" />
				</div>
			</div>
			<button type="submit" class="btn submit-button">Save</button>
		</form>
	</div>
</div>