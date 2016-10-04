<link rel="stylesheet" href="<?php echo base_url('assets/css/rating.css'); ?>" >
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.barrating.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/review.script.js'); ?>"></script>
<script type="text/javascript">
<?php $this->load->view('review/rating_script',array('user_rate'=>isset($user_rate)?$user_rate:'')) ?>
</script>
<!-- Page Title -->
<div class="page-title-container event-title-container">
	<div class="container">
		<div class="row">
			<div class="col-lg-9 wow fadeIn">&nbsp;</div>
			<div class="col-lg-3 wow fadeIn text-right">
				<?php $this->load->view('review/detail-rating-box',array('source'=>$rating)) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-9 wow fadeIn">
				<div>&nbsp;</div>
			</div>
			<div class="col-lg-3 wow fadeIn text-right rating-bar">
				<?php $this->load->view('review/user-rate-box') ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-9 wow fadeIn">
				<span>&nbsp;</span>
			</div>
			<div class="col-lg-3 wow fadeIn text-right">
				<?php if(isset($already_reviewed) && $already_reviewed) {?>
					<span class="post-review"><button type="button" class="btn btn-default" href="#write_review_container">Your Review</button></span>
				<?php } else { ?>
					<span class="post-review"><button type="button" class="btn btn-default" href="#write_review_container">Write Review</button></span>
				<?php } ?>
				<span><?= isset($rating->reviews)?$rating->reviews:0 ?> Reviews</span>
			</div>
		</div>
		
	</div>
</div>