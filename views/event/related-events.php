<div class="row text-left related-events">
	<?php  if(isset($related_events) && !empty($related_events)){ ?>
		<div class="col-lg-12">
			<div class="full-underline">&nbsp;</div>
			<div class="review-title">Related Events</div>
		</div>
		<?php foreach($related_events as $item){ ?>
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
	<?php } ?>
</div>