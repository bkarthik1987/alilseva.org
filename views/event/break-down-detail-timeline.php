<?php $this->load->view('event/break-down-detail-top-header') ?>
<div class="event">
	<?php $this->load->view('event/break-down-detail-tabs') ?>
	<div class="about-us-container">
		<div class="container event-details">
			<div class="row text-left">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 contact-form">
					<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 about-us-text wow fadeInLeft">
						<form role="form" action="<?php echo base_url('api/v1/event/update-sub-task'); ?>" method="put" class="ajaxPutForm" application="update-sub-task">
						<?php $this->load->view('alerts'); ?>
						<input type="hidden" name="task_id" value="<?=$this->alil_lib->encrypt_data($task_detail->id)?>" />
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="form-group">
										<label for="task_name" class="col-xs-3 no-left-padding">Task Name&nbsp;:&nbsp;&nbsp;</label>
										<strong><?=$task_detail->main_task_name?></strong>
									</div>
									<div class="form-group">
										<label class="col-xs-3 no-left-padding">Sub Task Name&nbsp;:&nbsp;&nbsp;</label>
										<?php
											if($this->session->userdata('role')=='VOLUNTEER'){ 
												echo '<strong>'.$task_detail->task_name.'</strong>';
											} 
										?>
										<?php if($this->session->userdata('role')=='ORGANIZATION'){ ?>
											<input type="text" name="task_name" id="task_name" class="required letterswithspaceonly" value="<?=$task_detail->task_name?>" placeholder="Task Name" />
										<?php } ?>
									</div>
									<div class="multi-line-form-group">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
											<div class="form-group">
												<label for="from-date" class="col-xs-6 no-left-padding">Expected Start date&nbsp;:&nbsp;&nbsp;</label>
												<?php 
													if($this->session->userdata('role')=='VOLUNTEER'){ 
														echo '<strong>'.date("d/m/Y",strtotime($task_detail->start_date)).'</strong>';
													} 
												?>
												<?php if($this->session->userdata('role')=='ORGANIZATION'){ ?>
												<input type="text" readonly class="custom-datepicker-input required" value="<?=date("d/m/Y",strtotime($task_detail->start_date))?>" name="start_date" placeholder="From Date..." id="from-date">
												<i class="fa fa-calendar date-icons custom_datepicker_icons" aria-hidden="true"></i>
												<?php } ?>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
											<div class="form-group">
												<label for="to-date" class="col-xs-6 no-left-padding">Expected End date&nbsp;:&nbsp;&nbsp;</label>
												<?php 
													if($this->session->userdata('role')=='VOLUNTEER'){ 
														echo '<strong>'.date("d/m/Y",strtotime($task_detail->end_date)).'</strong>';
													} 
												?>
												<?php if($this->session->userdata('role')=='ORGANIZATION'){ ?>
												<input type="text" readonly class="custom-datepicker-input required" value="<?=date("d/m/Y",strtotime($task_detail->end_date))?>" name="end_date" placeholder="To Date..." id="to-date">
												<i class="fa fa-calendar date-icons custom_datepicker_icons" aria-hidden="true"></i>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="multi-line-form-group">
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
											<div class="form-group">
												<label for="estimate-hours" class="col-xs-6 no-left-padding">Estimated Hours&nbsp;:&nbsp;&nbsp;</label>
												<?php 
													if($this->session->userdata('role')=='VOLUNTEER'){ 
														echo '<strong>'.$task_detail->estimated_hours.'</strong>';
													} 
												?>
												<?php if($this->session->userdata('role')=='ORGANIZATION'){ ?>
												<input type="text" name="estimate_hours" id="estimate-hours" class="required number" maxlength="2" value="<?=$task_detail->estimated_hours?>" placeholder="Estimated Hours" />
												<?php } ?>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
											<div class="form-group">
												<label for="hour-spent" class="col-xs-6 no-left-padding">Hours Spent&nbsp;:&nbsp;&nbsp;</label>
												<?php 
													if($this->session->userdata('role')=='VOLUNTEER'){ 
														echo '<strong>'.$task_detail->hours_spent.'</strong>';
													} 
												?>
												<?php if($this->session->userdata('role')=='ORGANIZATION'){ ?>
												<input type="text" name="hour_spent" id="hour-spent" class="number" maxlength="2" value="<?=$task_detail->hours_spent?>" placeholder="Hours Spent" />
												<?php } ?>
											</div>
										</div>
									</div>
									<?php if($this->session->userdata('role')==='ORGANIZATION'){ ?>
									<div class="form-group">
										<label for="volunteers">Volunteers</label>
										<input type="text" name="" class="utypeahead" id="volunteers" autocomplete="off" placeholder="Assign volunteers for this task" application="volunteers" data-hash="<?=$this->alil_lib->encrypt_data($task_detail->event_id)?>"/>
										<div class="selected-list volunteer-action">
											<ul>
												<?php
													if(isset($task_detail->volunteer_ids) && $task_detail->volunteer_ids!='' && isset($task_detail->volunteer_names) && $task_detail->volunteer_names!=''){
														$volunteer_ids=explode(",",$task_detail->volunteer_ids);
														$volunteer_names=explode(",",$task_detail->volunteer_names);
														for($i=0;$i<count($volunteer_ids);$i++){
												?>
															<li data-hash="<?=$this->alil_lib->encrypt_data($volunteer_ids[$i])?>"><?=$volunteer_names[$i]?>
																<i class="fa fa-times-circle links volunteer-delete" data-vhash="<?=$this->alil_lib->encrypt_data($volunteer_ids[$i])?>" data-hash="<?=$this->alil_lib->encrypt_data($task_detail->id)?>" aria-hidden="true" application="live"></i>
																<input type="hidden" name="volunteers[]" value="<?=$this->alil_lib->encrypt_data($volunteer_ids[$i])?>">
															</li>
												<?php
														}
													}
												?>
												
												
											</ul>
										</div>
									</div>
									<?php 
										}else{
											if($permissoin_access){
									?>
											<div class="form-group">
												<input type="checkbox" name="assign_to_me" value="No" /> &nbsp; <span>Unsubscribe</span>
											</div>
									<?php		
											}else{
									?>
											<div class="form-group">
												<input type="checkbox" name="assign_to_me" value="Yes" /> &nbsp; Subscribe
											</div>
									<?php	
											}
										}
										if($permissoin_access){
									?>
									<div class="multi-line-form-group show-update-status">
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
								<button type="submit" class="btn">Save</button>
								<button type="button" class="btn" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($task_detail, "break-down", "event"))?>'">Cancel</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>