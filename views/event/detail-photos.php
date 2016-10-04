<?php $this->load->view('event/detail-top-header') ?>
<div class="event">
	<?php $this->load->view('event/detail-tabs') ?>
	<div class="about-us-container">
		<div class="container photo-details">
			<div class="row text-left">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="row">
						<div class="col-sm-12 portfolio-masonry">
							<?php 
								if(!empty($event_photos)){
									foreach($event_photos as $photo){
							?>
								<div class="portfolio-box">
									<img src="<?php echo base_url(str_replace("/l/","/t/",$photo->url)); ?>" width="108" height="100" alt="" data-at2x="<?php echo base_url($photo->url) ?>">
									<?php if($this->session->userdata('role')==='ORGANIZATION' && $organization_permission){ ?>
									<div class="remove-photos" id="remove-photos">
										<i class="fa fa-times-circle icons" aria-hidden="true" onClick="Alil.eventScript.removePhotos(this)" data-hash="<?=$this->alil_lib->encrypt_data($photo->photo_id)?>"></i>
									</div>
									<?php } ?>
								</div>
							<?php	
									}
								}
							?>
							<?php 
								if(empty($event_photos) && $this->session->userdata('role')=='VOLUNTEER'){
							?>
								<div class="text-center">Photos will updated soon</div><br /><br />
							<?php } ?>
							<?php if($this->session->userdata('role')==='ORGANIZATION' && $organization_permission){ ?>
							<div class="portfolio-box photo-add-icons">
								<i class="fa fa-plus icons" data-toggle="modal" data-target="<?=$this->session->userdata('active_user_loggedin')?"#eventPhotomodal":"#siginModal"?>" aria-hidden="true"></i>
							</div>
							<?php } ?>
						</div>
					</div>
					<!--#Related Events-->
					<?php $this->load->view('event/related-events') ?>
					<!--#Related Events-->
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('popup/upload-event-photo'); ?>