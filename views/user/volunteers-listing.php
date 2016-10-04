<!-- Contact Us -->
<div class="contact-us-container">
	<div class="container">
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
			</div>
		</div>
	</div>
</div>