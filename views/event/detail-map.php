<?php $this->load->view('event/detail-top-header') ?>
<div class="event">
	<?php $this->load->view('event/detail-tabs') ?>
	<div class="about-us-container">
		<div class="container review-details">
			<div class="row text-left">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d497699.99741367646!2d77.35073181656826!3d12.953847712434039!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae1670c9b44e6d%3A0xf8dfc3e8517e4fe0!2sBengaluru%2C+Karnataka+560001!5e0!3m2!1sen!2sin!4v1469178827204" width="100%" height="450" frameborder="0" style="pointer-events:none;border:0" allowfullscreen></iframe>
					</div>
					<!--#Related Events-->
					<?php $this->load->view('event/related-events') ?>
					<!--#Related Events-->
				</div>
			</div>
		</div>
	</div>
</div>