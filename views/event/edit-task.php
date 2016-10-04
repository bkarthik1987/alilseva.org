<div class="contact-us-container">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 contact-form wow fadeInLeft">
				<form role="form" action="<?php echo base_url('api/v1/event/update-task'); ?>" method="put" class="ajaxPutForm" application="update-task">
					<?php $this->load->view('alerts'); ?>
					<input type="hidden" name="task_id" value="<?=$this->alil_lib->encrypt_data($task_detail->id)?>" />
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="task_name">Task Name</label>
								<input type="text" name="task_name" id="task_name" class="required letterswithspaceonly" value="<?=$task_detail->task_name?>" placeholder="Task Name" />
							</div>
							<div class="multi-line-form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
									<div class="form-group">
										<label for="from-date">Expected Start date</label>
										<div class="input-append date form_datetime" id="from-date">
											<input type="text" readonly class="custom-datepicker-input required" value="<?=date("d/m/Y",strtotime($task_detail->start_date))?>" name="start_date" placeholder="From Date..." id="from-date">
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
											<input type="text" readonly class="custom-datepicker-input required" value="<?=date("d/m/Y",strtotime($task_detail->end_date))?>" name="end_date" placeholder="To Date..." id="to-date">
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
										<input type="text" name="estimate_hours" id="estimate-hours" class="required number" maxlength="2" value="<?=$task_detail->estimated_hours?>" placeholder="Estimated Hours" />
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
									<div class="form-group">
										<label for="hour-spent">Hours Spent</label>
										<input type="text" name="hour_spent" id="hour-spent" class="number" maxlength="2" value="<?=$task_detail->hours_spent?>" placeholder="Hours Spent" />
									</div>
								</div>
							</div>
							<?php if($this->session->userdata('role')==='ORGANIZATION'){ ?>
							<div class="multi-line-form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
									<div class="form-group">
										<label for="status">Status</label>
										<select name="status" id="status">
											<option value="">Select</option>
											<option value="pending" <?=$task_detail->status=='PENDING'?'selected':''?>>Pending</option>
											<option value="approved" <?=$task_detail->status=='APPROVED'?'selected':''?> >Approved</option>
											<option value="inprogress" <?=$task_detail->status=='INPROGRESS'?'selected':''?>>In Progress</option>
											<option value="completed" <?=$task_detail->status=='COMPLETED'?'selected':''?>>Completed</option>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
									<div class="form-group" id="percentage">
										<label for="location">Current Progress %:</label>
										<input type="text" name="percentage" id="percentage" value="<?=$task_detail->percentage?>" placeholder="Current Progress %" />
									</div>
								</div>
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