<script id="volunteer-edit-basic-form" type="text/x-jsrender">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 page-title text-left">
		{{>user.username}} / Edit Profile
	</div>
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 text-right no-right-padding">
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
				<span><input type="radio" name="ends" value="checked" class="repeat-on"/>&nbsp;My direct connections&nbsp;</span>
				<span><input type="radio" name="ends" value="checked" class="repeat-on"/>&nbsp;My direct connections and their connections&nbsp;</span>
				<span><input type="radio" name="ends" value="checked" class="repeat-on"/>&nbsp;Anyone on AngleList&nbsp;</span>
			</div>
			<br />
			<button type="button" class="btn" id="save-settings" name="" value="">Save</button>
		</div>
	</div>
	<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes">
		<form role="form" action="<?php echo base_url('api/v1/user/update-user'); ?>" method="post" id="submit-ajax-form" class="submitAjaxForm" application="update-user-info" enctype="multipart/form-data">
			<?php $this->load->view('alerts'); ?>
			<div class="row vrow">
				<div class="col-lg-6 col-lg-offset-1 text-left vabout">&nbsp;</div>
				<div class="col-lg-4 text-right vabout">
					<span class="link cancel-btn edit-profile-form-cancel" application="edit-basic-form">Cancel</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="submit" class="btn submit-button" name="submit">Save</button>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 text-left no-left-padding">
				<input type="hidden" name="contact_id" value="{{>user.contact_id}}" />
				<input type="hidden" name="user_id" value="{{>user.id}}" />
				<div class="spage-title text-left">Basics</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="name">Name</label>
						<input type="text" name="name" value="{{>user.username}}" id="name" placeholder="Name" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="gender">Gender</label>
						<select name="gender" id="gender">
							<option value="M" {{if user.gender=='M'}}selected{{/if}}}>Male</option>
							<option value="F" {{if user.gender=='F'}}selected{{/if}}}>Female</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="email_address">Email Address</label>
						<input type="text" class="email-complete" name="email_address" value="{{>user.emailid}}" id="email_address" placeholder="Email Address" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="age">Age</label>
						<input type="text" name="age" value="{{>user.age}}" id="age" placeholder="Age" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="primary_number">Phone Number</label>
						<input type="text" name="primary_number" value="{{>user.primary_number}}" id="primary_number" placeholder="Phone Number" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="area">Area</label>
						<input type="text" name="area" value="{{>user.area}}" id="area" placeholder="Area" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="tlocation">City</label>
						<input type="text" name="tlocation" value="{{if user.location_name!='' && user.district_name!='' && user.country_name!=''}} {{>user.country_name}},{{>user.district_name}},{{>user.location_name}}{{/if}}" class="typeahead" id="tlocation" autocomplete="off" placeholder="Your location" application="location" />
						<div class="selected-list">
							<ul>
							{{if user.location_name!='' && user.location_id!=''}}
								<input type="hidden" name="location" value="{{>user.location_id}}" />
							{{/if}}
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="photo">Photo</label>
						<input type="file" name="userfile" id="photo" placeholder="Profession" />
						<div class="">
						{{if user.profile_url!=''}}
							<img src="<?php echo base_url() ?>/{{>user.profile_url}}" width="60" height="60"/>
						{{/if}}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="education">Education Level</label>
						<input type="text" name="education" value="{{>user.education_level}}" id="education" placeholder="Education Level" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="profession">Profession</label>
						<input type="text" name="profession" value="{{>user.profession}}" id="profession" placeholder="Profession" />
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 text-left">
				<div class="spage-title text-left">Links</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="name">LinkedIn</label>
						<input type="text" name="socialmedia[0]" class="letterswithbasic" value="{{>social_media.linkedin.handle}}" id="" placeholder="" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="name">Twitter</label>
						<input type="text" name="socialmedia[1]" class="letterswithbasic" value="{{>social_media.twitter.handle}}" id="" placeholder="" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="name">Facebook</label>
						<input type="text" name="socialmedia[2]" class="letterswithbasic" value="{{>social_media.facebook.handle}}" id="" placeholder="" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-10 col-md-10">
						<label for="name">GitHub</label>
						<input type="text" name="socialmedia[3]" class="letterswithbasic" value="{{>social_media.github.handle}}" id="" placeholder="" />
					</div>
				</div>
			</div>
		</form>
	</div>
</script>
<script id="user-edit-about-form" type="text/x-jsrender">
	<form role="form" action="<?php echo base_url('api/v1/volunteer/volunteer-preference'); ?>" method="put" id="submit-ajax-form" class="submitAjaxForm update-user-about-info" application="update-user-about-info">
		<?php $this->load->view('alerts'); ?>
		<div class="row vrow">
			<div class="col-lg-6 col-lg-offset-1 text-left vabout">About</div>
			<div class="col-lg-4 text-right vabout">
				<span class="link cancel-btn edit-profile-form-cancel" application="edit-about-form" show-default-target="#default-about">Cancel</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="submit" class="btn submit-button" name="submit">Save</button>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">About myself</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<textarea name="ability" value="" placeholder="Describe what you have done, what you are doing, and the kinds of things you are interested in.">{{:volunteer_preference.ability}}</textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">Achievements</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<textarea name="acheivments" value="" placeholder="Describe the most impressive thing you've done">{{:volunteer_preference.acheivments}}</textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">Skills</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 profile-skills">
				<input type="text" class="typeahead" name="tskill" id="skills" autocomplete="off" placeholder="Add Skills" application="skills"/>
				<div class="selected-list">
					<ul>
						{^{props skills}}
							<li data-hash="{{>prop.skill_id}}">{{>prop.skill_name}}
								<i class="fa fa-times-circle links delete-skills" data-hash="{{>prop.id}}" application="live" aria-hidden="true"></i>
								<input type="hidden" name="skills[]" value="{{>prop.skill_id}}">
							</li>
						{{/props}}
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">Services</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 profile-services">
				<input type="text" class="typeahead" name="tservice" id="services" autocomplete="off" placeholder="Add Services" application="services" />
				<div class="selected-list">
					<ul>
						{^{props services}}
							<li data-hash="{{>prop.service_id}}">{{>prop.service_name}}
								<i class="fa fa-times-circle links delete-services" data-hash="{{>prop.id}}" application="live" aria-hidden="true"></i>
								<input type="hidden" name="services[]" value="{{>prop.service_id}}">
							</li>
						{{/props}}
					</ul>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">Why volunteering excites me</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<textarea name="interests" value="" placeholder="Describe you're looking for in a company">{{:volunteer_preference.interests}}</textarea>
			</div>
		</div>
	</form>
</script>
<script id="volunteer-edit-preference-form" type="text/x-jsrender">
	<form role="form" action="<?php echo base_url('api/v1/volunteer/volunteer-preference'); ?>" method="put" id="submit-ajax-form" class="submitAjaxForm" application="update-volunteer-preference-info">
		<?php $this->load->view('alerts'); ?>
		<div class="row vrow">
			<div class="col-lg-6 col-lg-offset-1 text-left vabout">Volunteer Preference</div>
			<div class="col-lg-4 text-right vabout">
				<span class="link cancel-btn edit-profile-form-cancel" application="edit-volunteer-preference-form" show-default-target="#default-volunteer-preference">Cancel</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="submit" class="btn submit-button" name="submit">Save</button>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
				<input type="radio" name="availabilty" value="WEEKEND" class="" {{if volunteer_preference.availabilty=='WEEKEND'}}checked{{/if}} />&nbsp;&nbsp;&nbsp;2 to 4 hours over weekend
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
				<input type="radio" name="availabilty" value="FULLDAY" class="" {{if volunteer_preference.availabilty=='FULLDAY'}}checked{{/if}}/>&nbsp;&nbsp;&nbsp;Full day
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
				<input type="radio" name="availabilty" value="WEEKDAYS" class="" {{if volunteer_preference.availabilty=='WEEKDAYS'}}checked{{/if}}/>&nbsp;&nbsp;&nbsp;2 to 4 hours on weekdays <strong>6PM</strong> to <strong>8PM</strong>
			</div>
		</div>
		<div class="col-lg-5 col-lg-offset-1 no-left-padding text-left vabout">Loan Preference <br /><br /></div>
		<div class="row">
			<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
				<input type="radio" name="location_preference" value="MY_LOCATION" class="parent-location" {{if volunteer_preference.location_preference=='MY_LOCATION'}}checked{{/if}}/>&nbsp;&nbsp;&nbsp;My Location
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
				<input type="radio" name="location_preference" value="20KM" class="parent-location" {{if volunteer_preference.location_preference=='20KM'}}checked{{/if}} id="parent-outside-location"/>&nbsp;&nbsp;&nbsp;Don't mind outside locations
				<div class="row outside-location">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						<br />
						<input type="radio" name="location_preference" value="20KM" class="" {{if volunteer_preference.location_preference=='ANY_DISTANCE'}}checked{{/if}}/>&nbsp;&nbsp;&nbsp;With in 20kms
					</div>
				</div>
				<div class="row outside-location">
					<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
						<input type="radio" name="location_preference" value="ANY_DISTANCE" class="" {{if volunteer_preference.location_preference=='ANY_DISTANCE'}}checked{{/if}}/>&nbsp;&nbsp;&nbsp;Any distance
					</div>
				</div>
			</div>
		</div>
	</form>
</script>
<script id="volunteer-activities-preference-form" type="text/x-jsrender">
	<form role="form" action="<?=base_url("api/v1/volunteer/activities")?>" method="post" id="submit-ajax-form" class="submitAjaxForm" application="update-volunteer-activites-preference-info">
	<?php $this->load->view('alerts'); ?>
		<div class="row vrow">
			<div class="col-lg-6 col-lg-offset-1 text-left vabout">Activities Preference</div>
			<div class="col-lg-4 text-right vabout">
				<span class="link cancel-btn edit-profile-form-cancel" application="edit-activities-preference-form" show-default-target="#default-activities-preference">Cancel</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<button type="submit" class="btn submit-button" name="submit">Save</button>
			</div>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 vfinal-boxes1">
			<select name="activities[]" id="activities_list" class="multipart required" multiple="true" size="6">
				{{props activites_list}}
					<option value="{{>prop.id}}" {{:~isSelected(prop.id)}}>{{>prop.name}}</option>
				{{/props}}
			</select>
			<div class="col-md-11 col-lg-11 text-right vfinal-boxes2 no-right-padding">
				<div class="text-right">
					Doesn't Found your prefered activity <a href="javascript:void(0);" data-toggle="modal" data-target="#addActivitiesPreferenceModal">Add Here</a>
				</div>
			</div>
		</div>
	</form>
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
						<input type="text" name="activites" class="required" placeholder="Activities Preference...">
						<input type="submit" name="login" class="login submit-button loginmodal-submit" value="Submit">
					</form>
				</div>
			</div>
		</div>
	</div>
</script>
<script id="volunteer-activities-preference-list" type="text/x-jsrender">
	{{props activites}}
		<li>{{>prop.activity_name}}</li>
	{{/props}}
</script>