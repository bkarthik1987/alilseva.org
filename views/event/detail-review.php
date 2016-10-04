<link rel="stylesheet" href="<?php echo base_url('assets/css/rating.css'); ?>" >
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.barrating.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/review.script.js'); ?>"></script>
<script type="text/javascript">
<?php $this->load->view('review/rating_script',array('user_rate'=>isset($user_rate)?$user_rate:'')) ?>
</script>
<?php $this->load->view('event/detail-top-header') ?>
<div class="event">
	<?php $this->load->view('event/detail-tabs') ?>
	<div class="about-us-container">
		<div class="container review-details">
			<div class="row text-left">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<!--Review List-->
					<?php $this->load->view('review/review-list') ?>
					<!--#Review List-->
					<!--Write a Review-->
					<?php $this->load->view('review/write-review') ?>
					<!--#Write a Review-->
					<!--#Related Events-->
					<?php $this->load->view('event/related-events') ?>
					<!--#Related Events-->
				</div>
			</div>
		</div>
	</div>
</div>