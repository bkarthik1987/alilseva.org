<?php $this->load->view('event/detail-top-header') ?>
<div class="event">
	<?php $this->load->view('event/detail-tabs') ?>
	<div class="about-us-container">
		<div class="container event-details">
			<div class="row text-left">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="row">
						<div class="col-sm-12 about-us-text wow fadeInLeft">
							<p><?= nl2br($event_detail->event_description) ?></p>
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