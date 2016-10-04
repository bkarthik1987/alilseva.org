<script type="text/javascript">
$(document).ready(function () {
  	 var num = new Number('<?= isset($edit->points)?$edit->points:''; ?>');
     var n=num.toPrecision(2);
     
     rating = n;
      $('.bar-rating').barrating('show', {
		initialRating:n,                    
        showValues:false,
        showSelectedRating:false,
        onSelect: onSelect
  		});
  		attachListener();
      setNormalState();
      
      $('span.clear_rating').on('click',function () {
        if(!confirm("You Review about this colleage also removed. Are you sure remove your rating?"))
          return true;;
        $(".input_review").val(0);
		var rating_id=$("#rating_id").val();
        $.get("<?php echo base_url('AjaxValidation'); ?>/?do=rating&college_id=<?php echo $edit->source_id; ?>&rating=0&rating_id="+rating_id, function (data) {
            var obj = $.parseJSON(data);

            if (obj.status == 'true'){
				window.location.reload();	
            }
        });
    });



  		$(".update_review").click(function(){
  		    $('.updatereviewAjaxForm').ajaxForm({
              clearForm: false,
              beforeSubmit:function(){
                $(".reviewBeforeLoading").hide();
                $("#reviewAjaxFormLoading").show();
              },
              success:function(responseText, statusText, xhr, $form)  {
                var obj=$.parseJSON(responseText);
                if(obj.status=='false'){
                  $(".error-message").html(obj.msg).slideDown(100).delay(5000).slideUp(1000);
                }else if(obj.status=='review_true'){
                 $(".review_photo_ids").html(""); 
                 $(".msg-message").html(obj.msg).slideDown(100).delay(5000).slideUp(1000);
                }
                $(".reviewBeforeLoading").show();
                $("#reviewAjaxFormLoading").hide();
              } 
           }).submit();
  		});
  		
  		$(".ico_edit_review_photo_close_btn").click(function(){
        var e=$(this);
        var id=$(this).attr('data-file-hash');
        $.get("<?php echo base_url('AjaxValidation'); ?>/?do=rmv_photowithattach&id="+id,function(data){
          if(data=='true'){
            $(".review_photo_ids").find('input[value='+id+']').remove();
            e.parent().remove();
          }
        })
      });
      
      $(".update_photo_btn").click(function(){
        $("#update_review_photo").click();
      });
    var $form = $('.update_review_photoAjaxForm');
    $('form').delegate('#update_review_photo', 'change', function(){
        $form.ajaxForm({
          clearForm: true,
          url: '<?php echo base_url("reviewphoto"); ?>',
          beforeSubmit:function(){
            $('#review_photo_loading').show();
          },
          success:function(responseText, statusText, xhr, $form){
            var obj=$.parseJSON(responseText);
            if(obj.view=='true'){
              $.each( obj.img, function( key, value ) {
                $('.update_review_photo_list ul').append(value);
              });
              $(".review_photo_ids").append(obj.inputids);
            }
            $('#review_photo_loading').hide();
          }
        }).submit();
    });
	$('#customModal').on('hidden.bs.modal', function (e) {
		window.location.reload(); 
  });
    
});
</script>
<div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="loginModalLabel">Edit Review</h4>
        </div>
        <div class="modal-body">
            <div class="nopadding">
                <div class="nopadding">
                    <div class="write_reviews_rating">
					<div class="msg error-message alert alert-warning" style="display: none;"></div>    
					<div class="msg msg-message alert alert-success" style="display: none;"></div> 
                        <div class="rating-a">        
						<div class="rating-top-row">
						  <div class="rate-txt">your rating</div>
						  <div class="rate-number"><?= isset($edit->points) && $edit->points>0 ?$edit->points:'' ?></div>
						  <div class="rate-icon">
						  <input type="hidden" name="rating_id" id="rating_id" value="<?= isset($edit->rating_id)?$edit->rating_id:''  ?>">
						  <span ikon="X" class="ikon rating_close_ico clear clear_rating <?= isset($edit->points) && $edit->points>0 ?'':'hidden' ?> <?= isset($already_reviewed) && $already_reviewed?"review_attached":"" ?>"></span>
						  <img width="16" height="11" class="loading" src="<?php echo base_url('images/lloading.gif'); ?>"/>
						  </div>
						  </div>
                        <select id="bar-rating2" class="bar-rating edit-bar-rating" name="rating">
                            <?php
                              for($i=1;$i<=5;$i+=.5){
                                $num=number_format($i, 1, '.', '');
                            ?>
                              <option value="<?php echo $num; ?>" ><?php echo $num; ?></option>
                            <?php    
                              }
                            ?>
                          </select>
                        <div class="review_rating">  </div>   
                        </div>        
                    </div>
                </div>
            </div>
        <div class="nopadding">
                <div class="write_reviews_text_area">
                    <form action="<?php echo base_url('updatereview'); ?>" method="post" class="updatereviewAjaxForm" >
                        <input type="hidden" name="source_id" value="<?= $this->agarumlib->encrypt_data($edit->source_id) ?>" id="source_id">
                        <input type="hidden" name="points" id="user_points" value="<?= isset($edit->points)?$edit->points:''  ?>">
                        <input type="hidden" name="review_id" value="<?php echo $edit->review_id; ?>" class="input_review" />
                        <input type="hidden" name="review_rating" value="<?php echo $edit->points; ?>" class="input_review" />
                        <input type="hidden" name="rating_id" value="<?php echo $edit->rating_id; ?>" class="input_ratingID" />
                        <textarea name="content" class="enter_text required"><?php echo strip_tags($edit->content); ?></textarea>
                    </form>
                </div>
            	<div class="wr_publishreview">
                <button type="button" class="btn btn-primary update_review" id="publishReviewBtn">Save</button>
                </div>
                <!--
				<h2>ATTACHED PHOTOS</h2>
                <div id="review_photo_loading" style="display:none;"><img width="16" height="11" src="<?php echo base_url('images/lloading.gif'); ?>" alt="Loading.. Please Wait."/></div>
                <div class="write_review_photo_list">
                  <ul>   
                   <?php 
                      if($edit->photos) {
                        $photos=explode(",",$photos);
                        }
                        if(isset($photos) && sizeof($photos)>0) {
                        foreach($photos as $image){ ?>
                          <li><img width="60" height="60" src="<?= base_url($image); ?>" alt="Review Image"></li>
                        <?php }
                        }
                    ?>     
                  </ul> 
                </div> -->
            </div>
        </div>
    </div>
</div>