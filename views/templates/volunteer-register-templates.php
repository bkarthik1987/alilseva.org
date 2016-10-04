<script id="volunteer-register-step1" type="text/x-jsrender">
	<div class="col-sm-8 col-xs-12 col-md-8 col-lg-8 contact-form wow fadeInLeft text-left col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
		<div class="text-center page-title">Volunteer Registration</div>
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
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left col-lg-offset-2 col-md-offset-1 col-sm-offset-1">
			<form data-toggle="validator" role="form" class="signupAjaxForm" id="volunteerUserAjaxForm" action="<?=base_url("api/v1/volunteer/signup")?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="action" value="step1" />
				<?php $this->load->view('alerts'); ?>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="name">Name</label>
						<input type="text" autocomplete="off" name="name" class="required" id="name" placeholder="Name" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="gender">Gender</label>
						<select name="gender" id="gender" class="required">
							<option value="M">Male</option>
							<option value="F">Female</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="email_address">Email Address</label>
						<input type="text" autocomplete="off" class="required email-complete email" name="email_address" id="email_address" placeholder="Email Address" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="age">Age</label>
						<input type="text" autocomplete="off" name="age" class="number" maxlength="3" id="age" placeholder="Age" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="primary_number">Phone Number</label>
						<input type="text" autocomplete="off" name="primary_number" class="required number" minlength="10" maxlength="12" id="primary_number" placeholder="Phone Number" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="tlocation">City</label>
						<input type="text" autocomplete="off" name="tlocation" class="typeahead" id="tlocation" autocomplete="off" placeholder="Your location" application="location" />
						<div class="selected-list"><ul></ul></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="photo">Photo</label>
						<input type="file" name="userfile" id="photo" placeholder="Profession" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="education_level">Education Level</label>
						<input type="text" autocomplete="off" name="education" id="education_level" placeholder="Education Level" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-8">
						<label for="profession">Profession</label>
						<input type="text" autocomplete="off" name="profession" id="profession" placeholder="Profession" />
					</div>
				</div>
				<button type="submit" class="btn submit-button">Next</button>
				<button type="button" class="btn">Cancel</button>
			</form>
		</div>
	</div>
</script>
<script id="volunteer-register-step2" type="text/x-jsrender">
	<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 contact-form wow fadeInLeft text-left">
		<div class="row vrow">
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-offset-1 col-md-offset-1 text-left">
				<div class="text-center vabout">About</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-8 text-right">&nbsp;</div>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes">
			<form class="signupAjaxForm" id="volunteerSignupAjaxForm" role="form" action="<?=base_url("api/v1/volunteer/volunteer-preference")?>" method="post">
				<?php $this->load->view('alerts'); ?>
				<input type="hidden" name="action" value="step2" />
				<input type="hidden" name="user_id" id="user_id" value="{{:user_id}}" />
				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">About myself</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><textarea name="ability" class="required" value="" placeholder="Describe what you have done, what you are doing, and the kinds of things you are interested in."></textarea></div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">Why volunteering excites me</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><textarea name="interests" value="" placeholder="Describe you're looking for in a company"></textarea></div>
				</div>
				<div class="col-xs-12 col-lg-12 text-center">
					<br /><br />
					<button type="submit" class="btn submit-button" name="submit" >Next</button>
					<button type="button" class="btn skip-btn" name="button" next-page="{{:next_page}}" data-hash="{{:user_id}}"> Skip</button>
					<br /><br />
				</div>
			</form>
		</div>
	</div>
</script>
<script id="volunteer-register-step3" type="text/x-jsrender">
	<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 contact-form wow fadeInLeft text-left">
		<div class="row vrow">
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-offset-1 col-md-offset-1 text-left">
				<div class="text-center vabout">Social Links</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-8 text-right">&nbsp;</div>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes">
			<form class="signupAjaxForm" id="volunteerSignupAjaxForm" role="form" action="<?=base_url("api/v1/volunteer/social-media")?>" method="post">
				<?php $this->load->view('alerts'); ?>
				<input type="hidden" name="action" value="step3" />
				<input type="hidden" name="user_id" id="user_id" value="{{:user_id}}" />
				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						LinkedIn
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<input type="text" autocomplete="off" name="socialmedia[0]" id="" placeholder="" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						Twitter
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<input type="text" autocomplete="off" name="socialmedia[1]" id="" placeholder="" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						Facebook
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<input type="text" autocomplete="off" name="socialmedia[2]" id="" placeholder="" />
					</div>
				</div>
				<div class="col-xs-12 col-lg-12 text-center">
					<br /><br />
					<button type="submit" class="btn submit-button">Next</button>
					<button type="button" class="btn skip-btn" name="button" next-page="{{:next_page}}" data-hash="{{:user_id}}"> Skip</button>
					<br /><br />
				</div>
			</form>
		</div>
	</div>
</script>
<!--Hold 4th step-->
<script id="volunteer-register-step-hold-on-it" type="text/x-jsrender">
	<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 contact-form wow fadeInLeft text-left">
		<div class="row vrow">
			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-offset-1 col-md-offset-1 text-left">
				<div class="text-center vabout">&nbsp;</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-8 text-right">&nbsp;</div>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes">
			<form class="signupAjaxForm" id="volunteerSignupAjaxForm" role="form" action="<?=base_url("api/v1/volunteer/settings")?>" method="post">
				<?php $this->load->view('alerts'); ?>
				<input type="hidden" name="action" value="step4" />
				<input type="hidden" name="user_id" id="user_id" value="{{:user_id}}" />
				<div class="row text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="btn-group" data-toggle="buttons" id="settings">
						<label class="btn btn-primary">
							<input type="checkbox" name="settings" autocomplete="off">Settings
							<i class="fa fa-cog icon" aria-hidden="true"></i>
						</label>
					</div>
					<div class="setting-popover box setting-popup text-left">
						<div class="text-left cpopover-title">
							Who should be able to message you?<br />
						</div>
						<div class="text-left cpopover-body">
							<span><input type="radio" name="communicate_by" value="ALL" class="repeat-on"/>&nbsp;My direct connections&nbsp;</span>
							<span><input type="radio" name="communicate_by" value="FRIENDS" class="repeat-on"/>&nbsp;My direct connections and their connections&nbsp;</span>
							<span><input type="radio" name="communicate_by" value="FOF" class="repeat-on"/>&nbsp;Anyone on AlilList&nbsp;</span>
						</div>
						<br />
						<button type="button" class="btn" id="save-settings" name="" value="">Save</button>
					</div>
				</div>
				
				<div class="row text-center">
					<div class="btn-group" data-toggle="buttons">
					  <label class="btn btn-primary active vsocial_icon">
						<input type="radio" name="primary" value="TWITTER" autocomplete="off" >
						<i class="fa fa-twitter icon" aria-hidden="true"></i>
					  </label>
					  <label class="btn btn-primary vsocial_icon">
						<input type="radio" name="primary" value="FACEBOOK" autocomplete="off" checked>
						<i class="fa fa-facebook icon" aria-hidden="true"></i>
					  </label>
					  <label class="btn btn-primary vsocial_icon">
						<input type="radio" name="primary" value="LINKEDIN" autocomplete="off">
						<i class="fa fa-linkedin icon" aria-hidden="true"></i>
					  </label>
					  <label class="btn btn-primary vsocial_icon">
						<input type="radio" name="primary" value="MESSAGE" autocomplete="off">
						<i class="fa fa-envelope icon" aria-hidden="true"></i>
					  </label>
					</div>
				</div>
				<div class="col-xs-12 col-lg-12 text-center">
					<br /><br />
					<button type="submit" class="btn submit-button">Next</button>
					<button type="button" class="btn skip-btn" name="button" next-page="{{:next_page}}" data-hash="{{:user_id}}"> Skip</button>
					<br /><br />
				</div>
			</form>
		</div>
	</div>
</script>
<script id="volunteer-register-step4" type="text/x-jsrender">
	<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 contact-form wow fadeInLeft text-left">
		<div class="row vrow">
			<div class="col-xs-12 col-sm-8 col-md-8 text-left">
				<div class="text-left vabout">Skills & Endorsements</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4  text-right">
				<button type="button" class="btn btn-sm"><i class="fa fa-plus icon" aria-hidden="true"></i> Add Skill</button>
			</div>
		</div>
		<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-lg-offset-1 col-md-offset-1 text-left no-left-padding no-right-padding">
		<form class="signupAjaxForm" id="volunteerSignupAjaxForm" role="form" action="<?=base_url("api/v1/volunteer/signup")?>" method="post">
			<?php $this->load->view('alerts'); ?>
			<input type="hidden" name="action" value="step4" />
			<input type="hidden" name="user_id" id="user_id" value="{{:user_id}}" />
			<div class="col-lg-12 no-left-padding text-left vabout">Top Skills</div>
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">30</div>
					<div class="types">Software Development</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--2-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">29</div>
					<div class="types">TCP/IP</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--3-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">25</div>
					<div class="types">Network Security</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--4-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">21</div>
					<div class="types">Networking</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--5-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">20</div>
					<div class="types">Cisco Technologies</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--6-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">15</div>
					<div class="types">Security</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--7-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">11</div>
					<div class="types">Routing</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--8-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">10</div>
					<div class="types">SNMP</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--9-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">10</div>
					<div class="types">Aglie Methodologies</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<!--10-->
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 text-left skill-left no-left-padding no-right-padding">
					<div class="user-count">8</div>
					<div class="types">Cloud Computing</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 text-right skill-right no-left-padding no-right-padding">
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/1.jpg'); ?>" /></span>
					<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
					<span class="right-direction"><i class="fa fa-caret-right icon" aria-hidden="true"></i></span>
				</div>
			</div>
			<div class="col-lg-12 no-left-padding text-left vabout">Krishna also knows about...</div>
			
			<div class="skills col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left skills-bottom">
				<div class="skill-left">
					<div class="user-count">23</div>
					<div class="types">Java</div>
				</div>
				<div class="skill-left">
					<div class="user-count">67</div>
					<div class="types">IPSec</div>
				</div>
				<div class="skill-left">
					<div class="user-count">23</div>
					<div class="types">Project Management</div>
				</div>
				<div class="skill-left">
					<div class="user-count">12</div>
					<div class="types">Network Architecture</div>
				</div>
				<div class="skill-left">
					<div class="user-count">09</div>
					<div class="types">Testing</div>
				</div>
				<div class="skill-left">
					<div class="user-count">08</div>
					<div class="types">Cisco</div>
				</div>
				
				<div class="skill-left">
					<div class="user-count">12</div>
					<div class="types">Switches</div>
				</div>
				<div class="skill-left">
					<div class="user-count">09</div>
					<div class="types">Distributed System</div>
				</div>
				<div class="skill-left">
					<div class="user-count">08</div>
					<div class="types">Internet Protocol Suite...</div>
				</div>
				
			</div>
			<div class="col-xs-12 col-lg-12 text-center">
				<br /><br />
				<!--<button type="submit" class="btn">Next</button>-->
				<button type="button" class="btn skip-btn" name="button" next-page="{{:next_page}}" data-hash="{{:user_id}}"> Next</button>
				<button type="button" class="btn skip-btn" name="button" next-page="{{:next_page}}" data-hash="{{:user_id}}"> Skip</button>
				<br /><br />
			</div>
		</form>	
		</div>
	</div>
</script>
<script id="volunteer-register-step5" type="text/x-jsrender">
	<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 contact-form wow fadeInLeft text-left vcontact-form">
		<div class="row vrow">
			<div class="col-xs-12 col-sm-4 col-md-4 col-lg-offset-1 col-md-offset-1 text-left">
				<div class="text-left vabout">Volunteer Preference</div>
			</div>
			<div class="col-xs-12 col-sm-8 col-md-8 text-right">&nbsp;</div>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes">
			<form class="signupAjaxForm" id="volunteerSignupAjaxForm" role="form" action="<?=base_url("api/v1/volunteer/volunteer-preference")?>" method="put">
				<?php $this->load->view('alerts'); ?>
				<input type="hidden" name="action" value="step5" />
				<input type="hidden" name="user_id" id="user_id" value="{{:user_id}}" />
				<div class="col-lg-12 no-left-padding text-left vabout">Availability <br /><br /></div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						<input type="radio" name="availabilty" value="WEEKEND" class=""/>&nbsp;&nbsp;&nbsp;2 to 4 hours over weekend
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						<input type="radio" name="availabilty" value="FULLDAY" class=""/>&nbsp;&nbsp;&nbsp;Full day
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						<input type="radio" name="availabilty" value="WEEKDAYS" class=""/>&nbsp;&nbsp;&nbsp;2 to 4 hours on weekdays <strong>6PM</strong> to <strong>8PM</strong>
					</div>
				</div>
				<div class="col-lg-12 no-left-padding text-left vabout">Location Preference <br /><br /></div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						<input type="radio" name="location_preference" value="MY_LOCATION" class="parent-location"/>&nbsp;&nbsp;&nbsp;My Location
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						<input type="radio" name="location_preference" value="20KM" class="parent-location" id="parent-outside-location"/>&nbsp;&nbsp;&nbsp;Don't mind outside locations
						<div class="row outside-location hidden">
							<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
								<br />
								<input type="radio" name="location_preference" value="ANY_DISTANCE" class=""/>&nbsp;&nbsp;&nbsp;With in 20kms
							</div>
						</div>
						<div class="row outside-location hidden">
							<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
								<input type="radio" name="location_preference" value="ANY_DISTANCE" class=""/>&nbsp;&nbsp;&nbsp;Any distance
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-lg-12 text-center">
					<br /><br />
					<button type="submit" class="btn submit-button">Next</button>
					<button type="button" class="btn skip-btn" name="button" next-page="{{:next_page}}" data-hash="{{:user_id}}"> Skip</button>
					<br /><br />
				</div>
			</form>
		</div>
	</div>
</script>
<script id="volunteer-register-step6" type="text/x-jsrender">
	<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12 contact-form wow fadeInLeft text-left vcontact-form">
		<form class="signupAjaxForm" id="volunteerSignupActivitesForm" role="form" action="<?=base_url("api/v1/volunteer/activities")?>" method="post">
			<?php $this->load->view('alerts'); ?>
			<input type="hidden" name="action" value="step6" />
			<input type="hidden" name="user_id" id="user_id" value="{{:user_id}}" />
			<div class="row vrow">
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-offset-3 col-md-offset-3 text-left">
					<div class="text-left vabout">Activities Preference</div>
				</div>
				<div class="col-xs-12 col-sm-8 col-md-8 text-right">&nbsp;</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 text-left col-lg-offset-3 col-md-offset-3 col-sm-offset-3 boxes">
				<div class="col-xs-10 col-sm-12 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 vfinal-boxes1">
					<div class="row">
						<div class="col-lg-12">
							<select name="activities[]" id="activities_list" class="multipart required" multiple="true" size="6">
								{{props activites_list}}
									<option value="{{>prop.id}}">{{>prop.name}}</option>
								{{/props}}
							</select>
						</div>
					</div>
					<div class="text-right">
						Doesn't Found your prefered activity <a href="javascript:void(0);" data-toggle="modal" data-target="#addActivitiesPreferenceModal">Add Here</a>
					</div>
					<br /><br />
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 text-right vfinal-boxes2 no-right-padding clearboth">
				<button type="submit" class="btn btn-sm submit-button">Done</button>
			</div>
		</form>
	</div>
	<div id="addActivitiesPreferenceModal" class="modal" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Activities Preference</h4>
				</div>
				<div class="modal-body loginmodal-container">
					<form id="addNewActivites" role="form" action="<?=base_url("api/v1/volunteer/new-activities")?>" method="post">
						<?php $this->load->view('alerts'); ?>
						<input type="hidden" name="user_id" id="user_id" value="{{:user_id}}" />
						<input type="text" autocomplete="off" name="activites" class="required" placeholder="Activities Preference...">
						<input type="submit" name="login" class="login submit-button loginmodal-submit" value="Submit">
					</form>
				</div>
			</div>
		</div>
	</div>
</script>