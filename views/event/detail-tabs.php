<div class="detail-page-container">
	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<ul class="nav navbar-nav">
				<li class="<?=$uri_data === 'info'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($event_detail, "info", "event"))?>">Info</a>
				</li>
				<li class="<?=$uri_data === 'break-down'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($event_detail, "break-down", "event"))?>">Break Down</a>
				</li>
				<li class="<?=$uri_data === 'volunteers'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($event_detail, "volunteers", "event"))?>">Volunteers</a>
				</li>
				<li class="<?=$uri_data === 'photos'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($event_detail, "photos", "event"))?>">Photos</a>
				</li>
				<li class="<?=$uri_data === 'review'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($event_detail, "review", "event"))?>">Review</a>
				</li>
				<li class="<?=$uri_data === 'map'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($event_detail, "map", "event"))?>">Map</a>
				</li>
			</ul>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 subscribe-button no-right-padding text-right">
			<?php if($this->session->userdata('role')==='VOLUNTEER'){ ?>
				<button type="submit" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#sendMessageModal">Send Message</button>
				<?php $this->load->view('popup/send-message'); ?>
			<?php } ?>
			<?php if($authorization_permission && $this->session->userdata('role')==='ORGANIZATION' && $uri_data == 'info' && $organization_permission){ ?>
				<button type="submit" class="btn btn-primary btn-sm" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "edit", "event"))?>'">Edit</button>
			<?php } ?>
			<?php if($authorization_permission && $uri_data === 'break-down' && $this->session->userdata('active_user_loggedin')){ ?>
				<button type="submit" class="btn btn-primary btn-sm" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "create-task", "event"))?>'">Create Task</button>
			<?php } ?>
			<?php if($uri_data !== 'break-down' && $this->session->userdata('role')!=='ORGANIZATION' && $event_subscribed===false){ ?>
				<button type="button" class="btn btn-primary btn-sm <?php if($this->session->userdata('active_user_loggedin')){ ?>event-subscribe<?php } ?>" data-hash="<?=$this->alil_lib->encrypt_data($event_detail->event_id)?>" data-user-name="<?=$this->session->userdata('username')?>" data-event-name="<?=$event_detail->event_name?>" <?php if(!$this->session->userdata('active_user_loggedin')){ ?>data-toggle="modal" data-target="#siginModal" <?php } ?> >Subscribe</button>
			<?php } ?>
		</div>
	</div>
</div>	