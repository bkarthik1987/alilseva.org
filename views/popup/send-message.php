<!--Forget Modal-->
<div id="sendMessageModal" class="modal" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Send Message</h4>
		</div>
		<div class="modal-body loginmodal-container text-left">
			<form action="<?php echo base_url('api/v1/event/send-message'); ?>" id="ajaxForm" class="ajaxForm" method="post" application="send-event-message">
				<input type="hidden" name="event_id" value="<?=$this->alil_lib->encrypt_data($event_detail->event_id)?>" />
				<?php $this->load->view('alerts'); ?>
				<textarea name="content"  placeholder="Type a content for organization"></textarea>
				<input type="submit" class="login loginmodal-submit submit-button" value="Submit">
			</form>
		</div>
    </div>
  </div>
</div>