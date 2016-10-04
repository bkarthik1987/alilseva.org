<!-- Page Title -->
<div class="page-title-container event-title-container">
	<div class="container event">
		<div class="col-lg-12 wow fadeIn no-left-padding">
			<ol class="breadcrumb no-left-padding">
			  <li class="breadcrumb-item"><a href="<?=base_url()?>">Home</a></li>
			  <li class="breadcrumb-item"><a href="<?=base_url($this->alil_lib->makeEventUrl($task_detail, "break-down", "event"))?>"><?=$task_detail->main_task_name?></a></li>
			  <li class="breadcrumb-item active"><?=$task_detail->task_name?></li>
			</ol>
		</div>
	</div>	
	<div class="container">
		<div class="row">
			<div class="col-lg-12 wow fadeIn">
				<div class="break-down-sub-title"><?=$task_detail->task_name?></div>
			</div>
		</div>
	</div>
</div>