<div class="work-container">
	<div class="container">
		
		<div class="row">
			<div class="col-sm-3 col-xs-6 col-lg-3 col-md-3 event-box">
				<i class="fa fa-book icon"></i>
				<div class="underline"></div>
				<h3>Events</h3>
				<div class="hover-style">
					It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
					<a href="<?php echo base_url('events'); ?>">More</a>
				</div>
			</div>
			<div class="col-sm-3 col-xs-6 col-lg-3 col-md-3 event-box">
				<i class="fa fa-user icon"></i>
				<div class="underline"></div>
				<h3>Volunteers</h3>
				<div class="hover-style">
					It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
					<a href="<?php echo base_url('users'); ?>">More</a>
				</div>
			</div>
			<div class="col-sm-3 col-xs-6 col-lg-3 col-md-3 event-box">
				<i class="fa fa-info icon"></i>
				<div class="underline"></div>
				<h3>About Us</h3>
				<div class="hover-style">
					It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
					<a href="<?php echo base_url('about-us'); ?>">More</a>
				</div>
			</div>
			<div class="col-sm-3 col-xs-6 col-lg-3 col-md-3 event-box">
				<i class="fa fa-envelope icon"></i>
				<div class="underline"></div>
				<h3>Contact</h3>
				<div class="hover-style">
					It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
					<a href="<?php echo base_url('contact-us'); ?>">More</a>
				</div>
			</div>
			
		</div>
		
	</div>
</div>
<!-- Latest work -->
<div class="work-container">
	<div class="container">
		<?php if(!empty($events)){  ?>
		<div class="row">
			<div class="col-sm-12 work-title wow fadeIn">
				<h2>Our Latest Events</h2>
			</div>
		</div>
		<div class="row">
			<?php foreach($events as $item){ ?>
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

<!-- Testimonials -->
<div class="testimonials-container">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 testimonials-title wow fadeIn">
				<h2>Event News</h2>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1 testimonial-list">
				<div role="tabpanel">
					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane fade in active" id="tab1">
							<div class="testimonial-image">
								<img src="<?php echo base_url('assets/img/testimonials/1.jpg'); ?>" alt="" data-at2x="<?php echo base_url('assets/img/testimonials/1.jpg'); ?>">
							</div>
							<div class="testimonial-text">
								<p>
									"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. 
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. 
									Lorem ipsum dolor sit amet, consectetur..."<br>
									<a href="#">Lorem Ipsum, dolor.co.uk</a>
								</p>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="tab2">
							<div class="testimonial-image">
								<img src="<?php echo base_url('assets/img/testimonials/2.jpg'); ?>" alt="" data-at2x="<?php echo base_url('assets/img/testimonials/2.jpg'); ?>">
							</div>
							<div class="testimonial-text">
								<p>
									"Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip 
									ex ea commodo consequat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit 
									lobortis nisl ut aliquip ex ea commodo consequat..."<br>
									<a href="#">Minim Veniam, nostrud.com</a>
								</p>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="tab3">
							<div class="testimonial-image">
								<img src="<?php echo base_url('assets/img/testimonials/3.jpg'); ?>" alt="" data-at2x="<?php echo base_url('assets/img/testimonials/3.jpg'); ?>">
							</div>
							<div class="testimonial-text">
								<p>
									"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. 
									Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. 
									Lorem ipsum dolor sit amet, consectetur..."<br>
									<a href="#">Lorem Ipsum, dolor.co.uk</a>
								</p>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="tab4">
							<div class="testimonial-image">
								<img src="<?php echo base_url('assets/img/testimonials/1.jpg'); ?>" alt="" data-at2x="<?php echo base_url('assets/img/testimonials/1.jpg'); ?>">
							</div>
							<div class="testimonial-text">
								<p>
									"Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip 
									ex ea commodo consequat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit 
									lobortis nisl ut aliquip ex ea commodo consequat..."<br>
									<a href="#">Minim Veniam, nostrud.com</a>
								</p>
							</div>
						</div>
					</div>
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active">
							<a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab"></a>
						</li>
						<li role="presentation">
							<a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab"></a>
						</li>
						<li role="presentation">
							<a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab"></a>
						</li>
						<li role="presentation">
							<a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab"></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>