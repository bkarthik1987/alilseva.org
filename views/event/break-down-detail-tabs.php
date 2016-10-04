<div class="detail-page-container">
	<div class="container">
		<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
			<ul class="nav navbar-nav">
				<!--<li class="<?=$uri_data === 'info'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($task_detail, $this->alil_lib->makeShortName($task_detail->task_name)."/info", "task"))?>">Info</a>
				</li>-->
				<li  class="<?=$uri_data === 'timeline'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($task_detail, $this->alil_lib->makeShortName($task_detail->task_name)."/timeline", "task"))?>">Timeline</a>
				</li>
				<li  class="<?=$uri_data === 'incharge'?'active':'' ?>">
					<a href="<?=base_url($this->alil_lib->makeEventUrl($task_detail, $this->alil_lib->makeShortName($task_detail->task_name)."/incharge", "task"))?>">Volunteers</a>
				</li>
			</ul>
		</div>	
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 subscribe-button no-right-padding text-right">
			<?php if($uri_data === 'incharge' && $this->session->userdata('role')==='ORGANIZATION' && $organization_permission){ ?>
				<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addVolunteersModal">Add</button>
			<?php } ?>
			<?php if($permissoin_access && $this->session->userdata('role')!='ORGANIZATION'){ ?>
				<button type="button" class="btn btn-primary btn-sm subscribe-btn" onClick="Alil.eventScript.unsubscribed(this)" data-hash="<?=$this->alil_lib->encrypt_data($task_detail->id)?>">Unsubscribe</button>
			<?php }else if($this->session->userdata('role')!='ORGANIZATION'){ ?>
				<button type="button" class="btn btn-primary btn-sm subscribe-btn" onClick="Alil.eventScript.subscribed(this)" data-hash="<?=$this->alil_lib->encrypt_data($task_detail->id)?>">Subscribe</button>
			<?php } ?>
		</div>
	</div>
</div>