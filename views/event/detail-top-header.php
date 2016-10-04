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
			<div class="col-lg-9 wow fadeIn">
				<h1><?= $event_detail->event_name ?></h1>
			</div>
			<div class="col-lg-3 wow fadeIn text-right">
				<?php $this->load->view('review/detail-rating-box',array('source'=>$rating)) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-9 wow fadeIn">
				<span class="row-block">
					<?php if($event_detail->location_name!=''){ ?>
					ADDRESS: <?php echo $event_detail->country_name." &raquo; ".$event_detail->state_name." &raquo; ".$event_detail->district_name." &raquo; ".$event_detail->location_name?>  
					<?php } ?>
				</span>	
				<span class="row-block font14">
					Name : <?=$event_detail->user_name?>
				</span>
				<span class="row-block font14">
					Start Time : <?=date("d/m/Y h:i A",strtotime($event_detail->start_time))?>
				</span>
				<!--<span>PHONE:  04424780002</span>-->
			</div>
			<div class="col-lg-3 wow fadeIn text-right rating-bar">
				<?php $this->load->view('review/user-rate-box') ?>
				<p>&nbsp;</p>
			</div>
		</div>
	</div>
</div>