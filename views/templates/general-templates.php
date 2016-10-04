<script id="filtering-location-list" type="text/x-jsrender">
	{{props location_list}}
		<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 filter" application="location" application-target="#filtering-location-name" data-hash="{{>prop.id}}" data-hash-text="{{>prop.name}}"><a href="javascript:void(0);">{{>prop.name}}</a></div>
		{{else}}
		<div class="text-center">Information will updated soon</div>
	{{/props}}
</script>
<script id="event-list" type="text/x-jsrender">
	{{props event_list}}
		<div class="col-sm-3">
			<div class="work wow fadeInUp" onClick="window.location.href='<?php echo base_url(); ?>{{>prop.event_base_url}}'">
				{{if prop.event_cover_photo && prop.event_cover_photo!=''}}
					<img src="<?php echo base_url(); ?>{{:~photoReplace(prop.event_cover_photo, 'medium')}}" alt="{{>prop.event_name}}" data-at2x="<?php echo base_url(); ?>/{{>prop.event_cover_photo}}">
				{{else}}
					<img src="<?php echo base_url('assets/img/no-image-available.jpg'); ?>" alt="{{>prop.event_name}}" data-at2x="<?php echo base_url('assets/img/no-image-available.jpg'); ?>">	
				{{/if}}
				<h3>{{>prop.event_name}}</h3>
				<p>{{:~truncate(prop.event_description, 100)}}</p>
			</div>
		</div>
		{{else}}
		<div class="text-center">Information will updated soon</div>
	{{/props}}
</script>
<script id="reviews-list" type="text/x-jsrender">
	{{props reviews}}
		<div class="user-review-box col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding no-right-padding">
				<div class="col-lg-12 no-left-padding no-right-padding">
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 no-left-padding">
						<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
						<span>{{>prop.user_name}}</span>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right no-right-padding">
						<span class="rating-text rating-text-review">
							<div>RATED AS</div>
							<div>{{>prop.rating_description}}</div>
						</span>
						<span class="rating {{>prop.rating_avg_description}}">{{>prop.points}}</span>
					</div>
				</div>
				<div class="col-lg-12 no-left-padding">
					<p>
						{{>prop.content}}
					</p>
				</div>
				{{if prop.photos}}
				<div class="review_photo_list portfolio-masonry">
					<ul class="list-inline">
						<li class="galleryItem" data-group="{{>prop.review_id}}">
						{{props prop.photos}}
							<img src="<?= base_url()?>/{{>prop.url}}" data-at2x="<?= base_url()?>/{{>prop.url}}" width="60" height="60" alt="" />
						{{/props}}
						</li>
					</ul> 
				</div>
				{{/if}}
				{{if prop.edit===true}}
					<div class="user_btn_edit pull-right">
						<button class="btn-link" href="javascript:void(0)" id="edit_review" data-review-hash="{{>prop.review_id}}" onclick="Alil.review.edit(this);">Edit</button>
						<button class="btn-link" href="javascript:void(0)" id="delete_review" data-review-hash="{{>prop.review_id}}" data-rating-hash="{{>prop.rating_id}}" data-source-hash="{{>prop.source_id}}" data-source-type='<?= $source_type ?>' onclick="Alil.review.delete(this);">Delete</button>
					</div>
				{{/if}}
			</div>
		</div>
	{{/props}}
</script>
<script id="edit-review" type="text/x-jsrender">
	<?php $this->load->view('alerts'); ?>
	<div class="nopadding text-left">
		<div class="nopadding">
			<div class="write_reviews_rating">
				<div class="rating-a">        
					<div class="rating-top-row">
						<div class="rate-txt">your rating</div>
						<div class="rate-number">{{>review.points}}</div>
						<div class="rate-icon">
							<input type="hidden" name="rating_id" id="rating_id" value="{{>review.rating_id}}">
							<i class="fa fa-times rating_close_ico clear clear_rating {{if review.points && review.points>0}}hidden{{/if}} {{if already_reviewed}}review_attached{{/if}}" aria-hidden="true"></i>
						</div>
					</div>
					<select id="bar-rating2" class="bar-rating edit-bar-rating" name="rating">
						<?php
							for($i=1;$i<=5;$i+=.5){
								$num=number_format($i, 1, '.', '');
						?>
							<option value="<?php echo $num; ?>" ><?php echo $num; ?></option>
						<?php } ?>
					</select>
					<div class="review_rating"></div>   
				</div>
			</div>
		</div>
	</div>
	<div class="nopadding text-left">
		<div class="write_reviews_text_area">
			<form action="<?php echo base_url('api/v1/user/update-review'); ?>" method="post" class="updatereviewAjaxForm" >
				<input type="hidden" name="source_id" value="{{>review.source_id}}" id="source_id">
				<input type="hidden" name="source_type" value="<?= $source_type ?>" id="source_type">
				<input type="hidden" name="points" id="user_points" value="{{>review.points}}">
				<input type="hidden" name="review_id" value="{{>review.review_id}}" class="input_review" />
				<input type="hidden" name="review_rating" value="{{>review.points}}" class="input_review" />
				<input type="hidden" name="rating_id" value="{{>review.rating_id}}" class="input_ratingID" />
				<textarea name="content" class="enter_text required">{{>review.content}}</textarea>
			</form>
		</div>
		<div class="wr_publishreview">
			<button type="button" class="btn btn-primary update_review" id="publishReviewBtn">Save</button>
		</div>
	</div>
</script>