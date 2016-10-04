<script src="<?php echo base_url('assets/js/alil.profile.script.js'); ?>"></script>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding no-right-padding profile-top-header my-profile-basic">
	<div class="cover">&nbsp;</div>
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-lg-offset-2 col-md-offset-2 col-sm-offset-2 profile-info text-left no-left-padding">
		<div class="profile-photo-image" id="profile-photo-image">
			<?php if($user_detail->profile_url!=''){ ?>
				<img src="<?php echo base_url($user_detail->profile_url) ?>" />
			<?php }else{ ?>
				<img src="<?php echo base_url("assets/img/nopic.png") ?>" />
			<?php } ?>
		</div>
		<div class="info-text">
			<span class="" id="my-profile-user-name"><?=$user_detail->username?></span>
			<span class="link">&nbsp;</span>
			<?php if($user_detail->location_name){ ?>
				<span class="location">
					<i class="fa fa-map-marker" aria-hidden="true"></i>
					<?=$user_detail->location_name?>
				</span>
			<?php } ?>
		</div>
	</div>
</div>
<div class="container my-profile form-submit">
	<div class="container register-form oregister-form">
		<div class="col-sm-8 col-xs-12 col-md-8 col-lg-8 contact-form wow fadeInLeft text-left col-lg-offset-2 col-md-offset-2 col-sm-offset-2">
			<form class="ajaxForm" id="ajaxForm" role="form" action="<?=base_url("api/v1/organization/update-user")?>" method="post" enctype="multipart/form-data" application="organization-profile-form">
				<?php $this->load->view('alerts'); ?>
				<input type="hidden" name="contact_id" value="<?=$this->alil_lib->encrypt_data($user_detail->contact_id)?>" />
				<input type="hidden" name="secondary_contact_id" value="<?=$this->alil_lib->encrypt_data($user_detail->secondary_contact_id)?>" />
				<input type="hidden" name="organization_id" value="<?=$this->alil_lib->encrypt_data($user_organization->organization_id)?>" />
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="name">Organization Name</label>
						<input type="text" name="organization_name" value="<?=$user_organization->name?>" class="required" id="name" placeholder="Organization Name" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="website">Website</label>
						<input type="text" name="website" value="<?=$user_detail->website?>" class="required" id="website" placeholder="Website" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="address">Address</label>
						<input type="text" name="address" value="<?=$user_detail->address?>" id="address" placeholder="Address" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="location">Locality</label>
						<?php 
							if($user_detail->location_name!='' && $user_detail->district_name!='' && $user_detail->state_name!='' && $user_detail->country_name!=''){
								$location_name = $user_detail->country_name.",".$user_detail->district_name.",".$user_detail->location_name;
							}else{
								$location_name = '';
							}
						?>
						<input type="text" class="typeahead" name="tlocation" autocomplete="off" value="<?=$location_name?>" id="location" placeholder="Your location" application="location"/>
						<div class="selected-list"><ul>
						<?php 
							if($user_detail->location_name!='' && $user_detail->location_id!=''){
						?>
							<input type="hidden" name="location" value="<?=$user_detail->location_id?>" />
						<?php
							}
						?>
						</ul></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="organization-category">Category</label>
						<select name="organization_type" id="organization-category">
							<option value="">Select Organization type</option>
							<option value="GOV" <?=$user_organization->organization_type=='GOV'?'selected':''?>>Govt Run School</option>
							<option value="NGO" <?=$user_organization->organization_type=='NGO'?'selected':''?>>NGO</option>
							<option value="RWO" <?=$user_organization->organization_type=='RWO'?'selected':''?>>Resident Welfare Organization </option>
						</select>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="other-details">Registration license number, etc..</label>
						<input type="text" name="license_number" value="<?=$user_organization->license_number?>" id="other-details" placeholder="Registration license number, etc...." />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="primary-contact-name">Primary Contact name</label>
						<input type="text" name="primary_contact_name" value="<?=$user_detail->username?>" class="required" id="primary-contact-name" placeholder="Primary Contact name" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="primary-contact-number">Primary Contact Number</label>
						<input type="text" name="primary_number" value="<?=$user_detail->primary_number?>" id="primary-contact-number" placeholder="Primary Contact Number" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="primary_email_address">Primary Email Address</label>
						<input type="text" name="primary_email_address" value="<?=$user_detail->emailid?>" class="required email email-complete" id="primary_email_address" placeholder="Primary Email Address" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="secondary-contact-name">Secondary Contact name</label>
						<input type="text" name="secondary_contact_name" value="<?=$user_detail->secondary_contact_name?>" id="secondary-contact-name" placeholder="Secondary Contact name" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="secondary-contact-number">Secondary Contact Number</label>
						<input type="text" name="secondary_number" value="<?=$user_detail->secondary_number?>" id="secondary-contact-number" placeholder="Secondary Contact Number" />
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="secondary-email-id">Secondary Email Address</label>
						<input type="text" name="secondary_email_address" value="<?=$user_detail->secondary_emailid?>" class="email" id="secondary-email-id" placeholder="Secondary Email Address" />
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<label for="secondary-contact-number">Photos</label>
						<input type="file" name="userfile" id="photo" placeholder="Profession" />
						<div class="">
						<?php if($user_detail->profile_url!=''){ ?>
							<img src="<?php echo base_url($user_detail->profile_url) ?>" width="60" height="60"/>
						<?php } ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">&nbsp;</div>
				</div>
				<button type="submit" class="btn submit-button">Save</button>
			</form>
		</div>
	</div>	
</div>
<script type="text/javascript">
var type_skills=[];
var type_services=[];
var type_locality=$.parseJSON('<?=$locality?>');
var activites=[];
Alil.script.initialize(type_skills, type_services, type_locality, activites, []);
</script>