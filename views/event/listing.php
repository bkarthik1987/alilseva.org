<div class="work-container">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<?php if($this->session->userdata('active_user_loggedin')){ ?>
					<div class="create-event">
						<button type="submit" class="btn btn-primary btn-block" onClick="window.location.href='<?php echo base_url('create-events'); ?>'">Create Event</button>
					</div>
					<?php } ?>
					<div class="well">
						<div class="filter-boxes text-left">
							<span class="ftitle">Location &nbsp;</span>
							<span class="ficon link" data-toggle="modal" data-target="#locationModal">
								<span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
							</span>
						</div>
						<div class="filter-boxes text-left hidden remove-filter" id="filtering-location-name">
							<span class="ftitle bold"></span>
							<span class="ficon link" application="location">
								<span><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>
							</span>
						</div>
						<div class="filter-boxes text-left left-dropdown-boxes">
							<span class="ftitle">Category &nbsp;</span>
							<span class="ficon link" href="#fcategory" data-toggle="collapse">
								<span><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
							</span>
							<div id="fcategory" class="collapse event-filtering">
								<?php 
									if(!empty($event_domain)){ 
										foreach($event_domain as $domain){
								?>
											<a href="javascript:void(0);" class="filter" application="category" application-target="#filtering-category-name" data-hash="<?=$domain->id?>" data-hash-text="<?=$domain->name?>"><?=$domain->name?></a>
								<?php
										}
									}
								?>
							</div>
						</div>
						<div class="filter-boxes text-left hidden remove-filter" id="filtering-category-name">
							<span class="ftitle bold"></span>
							<span class="ficon link" application="category">
								<span><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>
							</span>
						</div>
						<div class="filter-boxes text-left left-dropdown-boxes">
							<span class="ftitle">Type of project &nbsp;</span>
							<span class="ficon link" href="#ftypeofproject" data-toggle="collapse">
								<span><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
							</span>
							<div id="ftypeofproject" class="collapse event-filtering">
								<a href="javascript:void(0);" class="filter" application="typeofproject" application-target="#filtering-typeofproject-name" data-hash="onetime" data-hash-text="One Time Event">One Time Event</a>
								<a href="javascript:void(0);" class="filter" application="typeofproject" application-target="#filtering-typeofproject-name" data-hash="repeat" data-hash-text="Repetitive" >Repetitive</a>
							</div>
						</div>
						<div class="filter-boxes text-left hidden remove-filter" id="filtering-typeofproject-name">
							<span class="ftitle bold"></span>
							<span class="ficon link" application="typeofproject">
								<span><i class="fa fa-times-circle-o" aria-hidden="true"></i></span>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9" id="event-list-page">
				<?php 
					if(!empty($events)){ 
						foreach($events as $item){
				?>
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
							<p><?=truncate($item->event_description, 100)?></p>
						</div>
					</div>
				<?php
						}
					}
				?>
			</div>
		</div>
	</div>
</div>
<!-- Testimonials -->
<div class="testimonials-container">
	<div class="container">
		&nbsp;
	</div>
</div>