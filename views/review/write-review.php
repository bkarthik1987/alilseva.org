<?php if(isset($already_reviewed) && $already_reviewed=='0'){ ?>
<div class="row text-left second-row" id="write_review_container">
	<div class="col-lg-12 no-left-padding">
		<?php $this->load->view('alerts'); ?>
		<div class="review-title">WRITE A REVIEW</div>
	</div>
	<div class="col-lg-12 rating-review no-left-padding write_reviews_rating">
		<span>YOUR RATING</span>
		<?php $this->load->view('review/user-rate-box') ?>
	</div>
	<form action="<?php echo base_url("api/v1/user/write-review"); ?>" method="post" id="reviewForm" class="reviewAjaxForm"> 
		<div class="col-lg-12 no-left-padding">
			<input type="hidden" name="source_type" value="<?=$source_type?>">
			<input type="hidden" name="add_photo" value="No">
			<input type="hidden" name="source_id" value="<?= isset($event_detail->event_id)?$this->alil_lib->encrypt_data($event_detail->event_id):$this->alil_lib->encrypt_data($user_detail->id) ?>" id="source_id">
            <input type="hidden" name="points" id="user_points" value="<?= isset($user_rate->points)?$user_rate->points:''  ?>">
			<input type="hidden" name="review_rating" value="<?= isset($user_rate->points)?$user_rate->points:''; ?>" class="input_review" />
			<input type="hidden" name="rating_id" value="<?= isset($user_rate->id)?$this->alil_lib->encrypt_data($user_rate->id):''; ?>" class="input_ratingID" />
			<div class="form-group">
				<textarea class="form-control" rows="8" id="comment" name="content"></textarea>
			</div>
		</div>
		<div class="review_photo_ids"></div>
	</form>	
	<div class="col-lg-12 no-left-padding">			
		<form action="<?php echo base_url("api/v1/user/review-photo"); ?>" method="post" id="reviewForm" class="review_photoAjaxForm">
			<div class="well">
				<i class="fa fa-picture-o photo_btn link" aria-hidden="true"></i>&nbsp;&nbsp;ADD PHOTOS TO THIS REVIEW
				<input type="file" class="hidden" multiple="" name="files[]" id="review_photo" />
			</div>
		</form>
	</div>
	<div class="col-lg-12 text-right no-left-padding">
		<button type="button" class="btn btn-default" id="publish-review-btn">Publish Review</button>
	</div>
	<div id="review_photo_loading" style="display:none;"><img width="16" height="11" src="<?php echo base_url('images/loading.gif'); ?>" alt="Loading.. Please Wait."/></div>
	<div class="write_review_photo_list">
		<ul class="list-inline"></ul>
	</div>
</div>
<?php } ?> 