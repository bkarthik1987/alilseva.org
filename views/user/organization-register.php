<script type="text/javascript">
var type_locality=$.parseJSON('<?=$locality?>');
Alil.script.initialize([], [], type_locality, [], []);
</script>
<div class="container register-form oregister-form">
	<div class="col-sm-8 col-xs-12 col-md-8 col-lg-8 contact-form wow fadeInLeft text-left col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
		<div class="text-center page-title">Organization Registration</div>
		<div class="col-xs-12 col-sm-6 col-md-6 text-center">
			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 social-btn text-left col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
				<i class="fa fa-facebook facebook-icons social-icons" aria-hidden="true"></i>
				Log in with Facebook
			</div>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6 text-center">
			<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 social-btn text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
				<i class="fa fa-twitter twitter-icons social-icons" aria-hidden="true"></i>
				Log in with Twitter
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 or-style text-center">OR</div>
		<form class="signupAjaxForm" id="organizationSignupAjaxForm" role="form" action="<?=base_url("api/v1/organization/signup")?>" method="post">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="name">Organization Name</label>
					<input type="text" name="organization_name" autocomplete="off" class="required" id="name" placeholder="Organization Name" />
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="website">Website</label>
					<input type="text" name="website" class="" autocomplete="off" id="website" placeholder="Website" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="address">Address</label>
					<input type="text" name="address" id="address" autocomplete="off" placeholder="Address" />
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="location">Locality</label>
					<input type="text" class="typeahead" name="location" autocomplete="off" id="location" placeholder="Your location" application="location"/>
					<div class="selected-list"><ul></ul></div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="organization-category">Category</label>
					<select name="organization_type" id="organization-category">
						<option value="">Select Organization type</option>
						<option value="GOV">Govt Run School</option>
						<option value="NGO">NGO</option>
						<option value="RWO">Resident Welfare Organization </option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="other-details">Registration license number, etc..</label>
					<input type="text" name="license_number" autocomplete="off" id="other-details" placeholder="Registration license number, etc...." />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="primary-contact-name">Primary Contact name</label>
					<input type="text" name="primary_contact_name" autocomplete="off" class="required" id="primary-contact-name" placeholder="Primary Contact name" />
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="primary-contact-number">Primary Contact Number</label>
					<input type="text" name="primary_number" autocomplete="off" class="required" maxlength="12" minlength="10" id="primary-contact-number" placeholder="Primary Contact Number" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="primary_email_address">Primary Email Address</label>
					<input type="text" name="primary_email_address" autocomplete="off" class="required email email-complete" id="primary_email_address" placeholder="Primary Email Address" />
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="secondary-contact-name">Secondary Contact name</label>
					<input type="text" name="secondary_contact_name" autocomplete="off" id="secondary-contact-name" placeholder="Secondary Contact name" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="secondary-contact-number">Secondary Contact Number</label>
					<input type="text" name="secondary_number" autocomplete="off" id="secondary-contact-number" placeholder="Secondary Contact Number" />
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<label for="secondary-email-id">Secondary Email Address</label>
					<input type="text" name="secondary_email_address" autocomplete="off" class="email" id="secondary-email-id" placeholder="Secondary Email Address" />
				</div>
			</div>
			<button type="submit" class="btn submit-button">Save</button>
		</form>
	</div>
</div>