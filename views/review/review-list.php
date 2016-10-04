<script type="text/javascript">
$(document).ready(function(){
	var groups = {};
	$('.galleryItem').each(function() {
		var id = parseInt($(this).attr('data-group'), 10);
		if(!groups[id]) {
			groups[id] = [];
		} 
		groups[id].push( this );
	});
	$("a#load_more").click(function(){
		var req_data = {};
		var offset=parseInt($(this).attr("offset"));
		var source_id=$(this).attr("source_hash");
		var source_type=$(this).attr("source_type");
		var user_id=$(this).attr("data-hash-user");
		$("a#load_more").hide();
		$(".review-pagination img.loading").removeClass("hidden");
		req_data.offset=offset;
		req_data.source_id=source_id;
		req_data.source_type=source_type;
		req_data.user_id=false;
		if(user_id)
			req_data.user_id=user_id;
		Alil.util.apiGet(base_url+api_path+"user/load-reviews",req_data,function(response){
			if(response.status=='SUCCESS'){
				var HTMLOutput = $("#reviews-list").render(response);
				if(HTMLOutput!=''){
					$("#add-review-listing").append(HTMLOutput);
				}
				$("a#load_more").attr("offset",offset+1);
				if(response.remaining_rows>0)
					$(".load_more_count").text(response.remaining_rows);
				else{
					$("div#no_more_reviews").show();
					$("a#load_more").remove();
				}
			}
			$(".review-pagination img.loading").addClass("hidden");
			$("a#load_more").show();
			var groups = {};
			$('.galleryItem').each(function() {
				var id = parseInt($(this).attr('data-group'), 10);
				if(!groups[id]) {
					groups[id] = [];
				} 
				groups[id].push( this );
			});
			imageGallery(groups);
		});
	})
	
	imageGallery(groups);
	
	function imageGallery(images){
		$.each(images, function() {
			$(this).magnificPopup({
				type: 'image',
				delegate: 'img',
				closeOnContentClick: true,
				closeBtnInside: false,
				gallery: {
					enabled: true,
				},
				callbacks: {
					elementParse: function(item) {				
						if(item.el.hasClass('portfolio-video')) {
							item.type = 'iframe';
							item.src = item.el.data('portfolio-video');
						}
						else {
							item.type = 'image';
							item.src = item.el.attr('src');
						}
					}
				}
			});
		});
	}
});
</script>
<div class="row text-left third-row" style="border-bottom:none;">
	<div class="col-lg-12 no-left-padding">
		<div class="review-title">USER REVIEWS</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 nopadding" id="add-review-listing">
	<?php 
		if(!empty($reviews)){ 
			foreach($reviews as $review_key=>$review){
	?>
				<div class="user-review-box col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding no-right-padding">
						<div class="col-lg-12 no-left-padding no-right-padding">
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 no-left-padding">
								<span><img src="<?php echo base_url('assets/img/ur-info-avath.jpg'); ?>" /></span>
								<span><?=$review->user_name?></span>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right no-right-padding">
								<span class="rating-text rating-text-review">
									<div>RATED AS</div>
									<div><?=$this->mrating->getRatingDescription($review->points)?></div>
								</span>
								<span class="rating <?= $this->alil_lib->makeShortName($this->mrating->getRatingDescription($review->points))."_rated" ?>"><?= $review->points; ?></span>
							</div>
						</div>
						<div class="col-lg-12 no-left-padding">
							<p>
								<?= $review->content; ?> 
							</p>
						</div>
						<?php if($review->photos!=''){ ?>
						<div class="review_photo_list portfolio-masonry">
							<ul class="list-inline">  
								<li class="galleryItem" data-group="<?=$review->review_id?>">
									<?php
										$images=json_decode($review->photos);
										foreach($images as $image_key=>$image){ 
									?>
										<img data-at2x="<?= base_url($image->url)?>" src="<?= base_url($image->url)?>" width="60" height="60" alt="<?= truncate($review->content,15); ?>">
									<?php }  ?>
								</li>
							</ul> 
						</div>
						<?php }  ?>
						<?php if(isset($review->user_id) && $review->user_id==$this->alil_lib->decrypt_data($this->session->userdata('user_id'))){?>
							<div class="user_btn_edit pull-right">
								<button class="btn-link" href="javascript:void(0)" id="edit_review" data-review-hash="<?= $this->alil_lib->encrypt_data($review->review_id); ?>" onclick="Alil.review.edit(this);">Edit</button>
								<button class="btn-link" href="javascript:void(0)" id="delete_review" data-review-hash="<?= $this->alil_lib->encrypt_data($review->review_id); ?>" data-rating-hash="<?= $this->alil_lib->encrypt_data($review->rating_id); ?>" data-source-hash="<?= $this->alil_lib->encrypt_data($review->source_id); ?>" data-source-type='<?= $source_type ?>'  onclick="Alil.review.delete(this);">Delete</button>
							</div>
						<?php } ?>
					</div>
				</div>
	<?php	
			}
		}
	?>
	</div>
	<div class="col-lg-12 text-center">
		<div class="well load-more review-pagination">
			<img title="loading.." width="43" height="11" class="loading hidden" src="<?php echo base_url('assets/img/loading.gif'); ?>" alt="Please wait.."/>
			<?php if($reviews_pagination) { ?>
				<?php 
					if($this->session->userdata('active_user_loggedin'))
						$user_id=$this->session->userdata('user_id');
					else
						$user_id=null;
				?>
				<a id="load_more" href="javascript:void(0)" offset="1" source_hash="<?= $source_id ?>" source_type="<?= $source_type ?>" data-hash-user="<?=$user_id?>"> 
					Load More <span class="load_more_count"><?= $reviews_count-$this->_reviews_pagination_count ?> </span>
				</a>
				<div id="no_more_reviews" style="display:none;"> No More Reviews </div>
			<?php } else if($reviews_count==0) { ?>
				<a id="no_reviews" href="javascript:void(0);" > No Reviews Yet </a>
			<?php } else { ?>
				<div id="no_more_reviews"> No More Reviews </div>
			<?php } ?>
		</div>
	</div>
</div>