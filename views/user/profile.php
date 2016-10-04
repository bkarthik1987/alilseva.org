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
	<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 text-left no-right-padding no-left-padding">
		<div class="link cancel-btn edit-profile-form" application="edit-basic-form" show-target="#show-edit-basic-form" show-default-target=".my-profile-basic">Edit</div>
	</div>
</div>
<div class="container my-profile form-submit">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form profile-form fadeInLeft">
			<br /> <br /> 
			<div id="show-edit-basic-form"></div>
			<!--Second Box--->
			<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes">
				<div id="show-about">
					
				</div>
				<div id="default-about">
					<div class="row vrow">
						<div class="col-lg-5 col-lg-offset-1 text-left vabout">About</div>
						<div class="col-lg-5 text-right vabout">
							<div class="link edit-profile-form" application="edit-about-form" show-target="#show-about" show-default-target="#default-about">Edit</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">WHAT I DO</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="view-ability">
							<?php
								if(isset($preference_detail->ability) && $preference_detail->ability){
									echo '<p class="bold">'.$preference_detail->ability.'</p>';
								}else{
									echo "Describe what you have done, what you are doing, and the kinds of things you are interested in.";
								}
							?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">ACHIEVEMENTS</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="view-acheivments">
							<?php
								if(isset($preference_detail->acheivments) && $preference_detail->acheivments){
									echo '<p class="bold">'.$preference_detail->acheivments.'</p>';
								}else{
									echo "Describe the most impressive thing you've done.";
								}
							?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">SKILLS</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="skills-text">
							<?php 
								if(!empty($user_skills)){
									echo '<p class="bold">'.$user_skills_list.'</p>';
								}else{
									echo "Add Skills";
								}
							?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">SERVICES</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="services-text">
							<?php
								if(!empty($user_services)){
									echo '<p class="bold">'.$user_services_list.'</p>';
								}else{
									echo "Add Services";
								}
							?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">WHAT I'M LOOKING FOR</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" id="view-interests">
							<?php
								if(isset($preference_detail->interests) && $preference_detail->interests){
									echo '<p class="bold">'.$preference_detail->interests.'</p>';
								}else{
									echo "Describe you're looking for in a company";
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<!--Third Box--->
			<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes">
				<div id="show-volunteer-preference"> </div>
				<div id="default-volunteer-preference">
					<div class="row vrow">
						<div class="col-lg-5 col-lg-offset-1 text-left vabout">Volunteer Preference</div>
						<div class="col-lg-5 text-right vabout">
							<div class="link edit-profile-form" application="edit-volunteer-preference-form" show-target="#show-volunteer-preference" show-default-target="#default-volunteer-preference">Edit</div>
						</div>
					</div>
					<div class="col-lg-5 col-lg-offset-1 no-left-padding text-left vabout">Availability <br /><br /></div>
					<?php if(isset($preference_detail->availabilty) && $preference_detail->availabilty!=''){ ?>
						<div class="row">
							<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2" id="volunteer-preference-availabilty-text">
								<p class="bold"><?=$availabilty[''.$preference_detail->availabilty.'']?></p>
							</div>
						</div>
					<?php }else{ ?>
						<div class="row">
							<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2" id="volunteer-preference-availabilty-text">
								N/A
							</div>
						</div>
					<?php } ?>
					<div class="col-lg-5 col-lg-offset-1 no-left-padding text-left vabout">Location Preference <br /><br /></div>
					<?php if(isset($preference_detail->location_preference) && $preference_detail->location_preference!=''){ ?>
						<div class="row">
							<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2" id="volunteer-preference-location-preference-text">
								<p class="bold"><?=$location_preference[''.$preference_detail->location_preference.'']?></p>
							</div>
						</div>
					<?php }else{ ?>
						<div class="row">
							<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-2 col-md-offset-2 col-lg-offset-2" id="volunteer-preference-location-preference-text">
								N/A
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<!--Fourth Box--->
			<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 boxes contact-form">
				<div class="row">
					<div class="col-lg-12">
						<div id="show-activities-preference"></div>
						<div id="default-activities-preference">
							<div class="row vrow">
								<div class="col-lg-5 col-lg-offset-1 text-left vabout">Activities Preference</div>
								<div class="col-lg-5 text-right vabout">
									<div class="link edit-profile-form" application="edit-activities-preference-form" show-target="#show-activities-preference" show-default-target="#default-activities-preference">Edit</div>
								</div>
							</div>
							<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 text-left col-lg-offset-1 col-md-offset-1 col-sm-offset-1 vfinal-boxes1">
								<ul>
									<?php 
										if(!empty($activity_preference)){ 
											foreach($activity_preference as $preference){
									?>
											<li><?=$preference->activity_name?></li>
									<?php
											}
										}else{
									?>
											<li>N/A</li>
									<?php	
										}
									?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br /><br />
</div>
<?php $this->load->view('user/profile-forms'); ?>
<script type="text/javascript">
var type_skills=$.parseJSON('<?=$skills?>');
var type_services=$.parseJSON('<?=$services?>');
var type_locality=$.parseJSON('<?=$locality?>');
var activites=$.parseJSON('<?=$activites?>');
Alil.script.initialize(type_skills, type_services, type_locality, activites, []);
</script>