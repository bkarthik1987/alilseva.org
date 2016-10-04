<?php $this->load->view('event/break-down-detail-top-header') ?>
<div class="event">
	<?php $this->load->view('event/break-down-detail-tabs') ?>
	<div class="about-us-container">
		<div class="container event-details">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 wow fadeInLeft no-left-padding no-right-padding">
					<div class="row info-panel">
						<?php 
							if(isset($volunteers) && !empty($volunteers)){ 
								foreach($volunteers as $item){
						?>
						<div class="col-sm-4 col-xs-6 col-lg-4 col-md-4 top-padding15">
							<div class="user-list">
								<div class="photo text-center">
									<?php if($item->user_cover_photo!=''){ ?>
										<img src="<?php echo base_url($item->user_cover_photo); ?>" />
									<?php }else{ ?>
										<img src="<?php echo base_url('assets/img/user.png'); ?>" />
									<?php } ?>
								</div>
								<div class="user-text text-center"><?=$item->user_name?></div>
								<div class="user-rate text-left">
									<div class="urating"><i class="fa fa-star icon" aria-hidden="true"></i>Top Rated</div>
									<div class="location">
										<?php if($item->location_name){ ?>
											<i class="fa fa-map-marker icon" aria-hidden="true"></i><?=$item->location_name?>
										<?php } ?>
									</div>
								</div>
								<div class="view-btn text-center">
									<button type="button" class="btn" onClick="window.location.href='<?php echo base_url('user/'.$item->user_handle); ?>'">View Profile</button>
								</div>
							</div>
						</div>
						<?php 	}
							}else{ 
						?>
							<div class="col-sm-4 col-xs-6 col-lg-4 col-md-4">No Volunteers enrolled</div>
						<?php } ?>
					</div>
					<!--#Related Events-->
					<?php $this->load->view('event/related-events') ?>
					<!--#Related Events-->
				</div>
			</div>
		</div>
	</div>
</div>
<!--Add Volunteers Modal-->
<div id="addVolunteersModal" class="modal" role="dialog">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Add Volunteers</h4>
		</div>
		<div class="modal-body loginmodal-container">
			<form role="form" action="<?php echo base_url('api/v1/event/event-volunteer-map'); ?>" id="ajaxForm" class="ajaxForm" method="post" application="volunteer-map">
				<input type="hidden" name="event_task_id" value="<?=$this->alil_lib->encrypt_data($task_detail->id)?>">
				<?php $this->load->view('alerts'); ?>
				<input type="text" name="" class="utypeahead" id="volunteers" autocomplete="off" placeholder="Assign volunteers for this task" application="volunteers" data-hash="<?=$this->alil_lib->encrypt_data($task_detail->event_id)?>"/>
				<div class="selected-list volunteer-action"><ul></ul></div>
				<input type="submit" class="login loginmodal-submit submit-button" value="Submit">
			</form>
		</div>
    </div>
  </div>
</div>