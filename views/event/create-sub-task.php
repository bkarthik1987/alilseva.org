<div class="contact-us-container">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 contact-form wow fadeInLeft">
				<form role="form" action="<?php echo base_url('api/v1/event/create-sub-task'); ?>" method="post" class="ajaxForm" application="create-sub-task">
					<?php $this->load->view('alerts'); ?>
					<input type="hidden" name="event_id" value="<?=$this->alil_lib->encrypt_data($event_detail->event_id)?>" />
					<input type="hidden" name="task_id" value="<?=$this->alil_lib->encrypt_data($task_detail->id)?>" />
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="task_name">Task Name&nbsp;:&nbsp;&nbsp;<strong><?=$task_detail->task_name?></strong></label>
							</div>
							<div class="form-group">
								<label>Sub Task Name</label>
								<input type="text" name="task_name" id="task_name" class="required letterswithspaceonly" value="" placeholder="Task Name" />
							</div>
							<div class="multi-line-form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
									<div class="form-group">
										<label for="from-date">Expected Start date</label>
										<div class="input-append date form_datetime" id="from-date">
											<input type="text" readonly class="custom-datepicker-input required" value="" name="start_date" placeholder="From Date...">
											<span class="add-on">
												<i class="fa fa-calendar date-icons custom_datepicker_icons" aria-hidden="true"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
									<div class="form-group">
										<label for="to-date">Expected End date</label>
										<div class="input-append date form_datetime" id="to-date">
											<input type="text" readonly class="custom-datepicker-input required" value="" name="end_date" placeholder="To Date...">
											<span class="add-on">
												<i class="fa fa-calendar date-icons custom_datepicker_icons" aria-hidden="true"></i>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="multi-line-form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
									<div class="form-group">
										<label for="estimate-hours">Estimated Hours </label>
										<input type="text" name="estimate_hours" id="estimate-hours" class="required number" maxlength="2" value="" placeholder="Estimated Hours" />
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
									<div class="form-group">
										<label for="hour-spent">Hours Spent</label>
										<input type="text" name="hour_spent" id="hour-spent" class="number" maxlength="2" value="" placeholder="Hours Spent" />
									</div>
								</div>
							</div>
							<?php if($this->session->userdata('role')==='ORGANIZATION'){ ?>
							<div class="form-group">
								<label for="volunteers">Volunteers</label>
								<input type="text" name="" class="utypeahead" id="volunteers" autocomplete="off" placeholder="Assign volunteers for this task" application="volunteers" data-hash="<?=$this->alil_lib->encrypt_data($event_detail->event_id)?>"/>
								<div class="selected-list volunteer-action"><ul></ul></div>
							</div>
							<?php }else{ ?>
							<div class="form-group">
								<input type="checkbox" name="assign_to_me" value="Yes" /> &nbsp; Subscribe
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding text-center">
						<button type="submit" class="btn submit-button">Save</button>
						<button type="button" class="btn" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "break-down", "event"))?>'">Back</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>