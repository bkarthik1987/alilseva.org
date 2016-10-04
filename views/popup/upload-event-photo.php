<div class="modal fade" id="eventPhotomodal" tabindex="-1" role="dialog" aria-labelledby="eventPhotomodal">
	<div class="modal-dialog modal-md" role="document">
	   <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Upload Event Photos</h4>
			</div>
			<div class="modal-body text-left">
				<form method="post" class="ajaxPhotoUploadSubmit" action="<?= base_url('api/v1/event/upload-event-photos') ?>" enctype="multipart/form-data" application="<?=$this->session->userdata('active_user_loggedin')?"upload":"login"?>">
					<?php $this->load->view('alerts'); ?>
					<input type="hidden" name="event_id" value="<?=$this->alil_lib->encrypt_data($event_detail->event_id)?>" />
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input type="file" class="file" id="photos" name="photos" />
						</div>
					</div><div class="row">&nbsp;</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<input type="checkbox" class="" id="cover" name="cover_image" value="Yes" /> Set Cover Photo
						</div>
					</div><div class="row">&nbsp;</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
							<button class="btn btn-primary btn-sm" type="button" data-dismiss="modal">Close</button>
							<button class="btn btn-primary btn-sm submit-button" type="submit">Save</button>
						</div>
					</div>
				</form>
		  </div>
	   </div>
	</div>
 </div>