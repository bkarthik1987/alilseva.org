<div class="work-container">
	<div class="container myevents">
		<?php if(!empty($current_events)){  ?>
		<!--Current Events-->
		<div class="row">
			<div class="col-sm-12 work-title wow fadeIn">
				<h2>Current Events</h2>
			</div>
		</div>
		<div class="row">
			<?php foreach($current_events as $item){ ?>
				<div class="col-sm-3">
					<div class="work wow fadeInUp" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($item, "info", "event"))?>'">
						<?php 
							if($item->event_cover_photo!=''){
						?>
								<img src="<?php echo base_url(str_replace("/l/","/m/",$item->event_cover_photo)); ?>" alt="<?=$item->event_name?>" data-at2x="<?php echo base_url($item->event_cover_photo); ?>">
						<?php	
							}else{
						?>	
							<img src="<?php echo base_url('assets/img/no-image-available.jpg'); ?>" alt="<?=$item->event_name?>" data-at2x="<?php echo base_url('assets/img/no-image-available.jpg'); ?>">
						<?php	
							}
						?>
						<h3><?=$item->event_name?></h3>
						<p><?=truncate($item->event_description, 80)?></p>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if(!empty($completed_events)){  ?>
		<!--Completed Events-->
		<div class="row">
			<div class="col-sm-12 work-title wow fadeIn">
				<h2>Completed Events</h2>
			</div>
		</div>
		<div class="row">
			<?php foreach($completed_events as $item){ ?>
				<div class="col-sm-3">
					<div class="work wow fadeInUp" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($item, "info", "event"))?>'">
						<?php 
							if($item->event_cover_photo!=''){
						?>
								<img src="<?php echo base_url(str_replace("/l/","/m/",$item->event_cover_photo)); ?>" alt="<?=$item->event_name?>" data-at2x="<?php echo base_url($item->event_cover_photo); ?>">
						<?php	
							}else{
						?>	
							<img src="<?php echo base_url('assets/img/no-image-available.jpg'); ?>" alt="<?=$item->event_name?>" data-at2x="<?php echo base_url('assets/img/no-image-available.jpg'); ?>">
						<?php	
							}
						?>
						<h3><?=$item->event_name?></h3>
						<p><?=truncate($item->event_description, 80)?></p>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>