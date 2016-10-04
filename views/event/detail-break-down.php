<?php $this->load->view('event/detail-top-header') ?>
<div class="event">
	<?php $this->load->view('event/detail-tabs') ?>
	<div class="about-us-container">
		<div class="container event-details">
			<div class="row text-left">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="row">
						<div class="col-sm-12 wow fadeInLeft">
							<div class="list-group break-down-list-group">
								<?php
									if(isset($task_list) && !empty($task_list)){
										foreach($task_list as $mkey=>$item){
											$user_id=$this->alil_lib->decrypt_data($this->session->userdata('user_id'));
								?>
											<div class="list-group-item break-down-list-pheading breakdown-<?=$mkey?>">
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
													<h4 class="list-group-item-heading break-down-list-heading">
														<?=$item['main_task']->task_name ?>
													</h4>
												</div>	
												<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
													<?php if($this->session->userdata('active_user_loggedin') && $authorization_permission){ ?>
														<button type="submit" class="btn btn-primary btn-sm" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "create-sub-task", "event"))?>?task=<?=$this->alil_lib->encrypt_data($item['main_task']->id)?>'">Create Sub Task</button>
														<?php if($this->session->userdata('role')==='ORGANIZATION' && $item['main_task']->status=='PENDING'){ ?>
															<button type="submit" class="btn btn-primary btn-sm" onClick="Alil.eventScript.taskApproval(this)" data-hash="<?=$this->alil_lib->encrypt_data($item['main_task']->id)?>">Approve</button>
														<?php } ?>
														<?php if($this->session->userdata('role')==='ORGANIZATION'){ ?>
														<button type="submit" class="btn btn-primary btn-sm" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "update-task", "event"))?>?task=<?=$this->alil_lib->encrypt_data($item['main_task']->id)?>'">Edit</button>
														<button type="submit" class="btn btn-primary btn-sm" onClick="Alil.eventScript.removeTask(this)" data-hash-target="breakdown-<?=$mkey?>" data-hash="<?=$this->alil_lib->encrypt_data($item['main_task']->id)?>">Delete</button>
														<?php } ?>
													<?php } ?>
												</div>
											</div>
								<?php			
											if(isset($item['sub_task']) && !empty($item['sub_task'])){
												foreach($item['sub_task'] as $key=>$sub_item){
													$sub_item->event_short_name=$event_detail->event_short_name;
								?>
													<div class="list-group-item break-down-list-group-item breakdown-<?=$mkey?> breakdown-sub-<?=$key?>">
														<div class="row">
															<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
																<div class="list-group-item-text sno"><?=$key+1?></div>
																<div class="list-group-item-text text">
																	<a href="<?=base_url($this->alil_lib->makeEventUrl($sub_item, $this->alil_lib->makeShortName($sub_item->task_name)."/timeline", "task"))?>"><?=$sub_item->task_name?></a>
																</div>
															</div>
															<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 break-down-progress-col">
																<div class="progress">
																  <div class="progress-bar" role="progressbar" aria-valuenow="<?=$sub_item->percentage?>"
																  aria-valuemin="0" aria-valuemax="100" style="width:<?=$sub_item->percentage?>%">
																	<span class="sr-only"><?=$sub_item->percentage?>% Complete</span>
																  </div>
																</div>
															</div>
															<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
																<div class="list-group-item-text links">
																	<?php
																		if($sub_item->volunteer_names!=''){
																			$volunteer_ids=explode(",",$sub_item->volunteer_ids);
																			$volunteer_names=explode(",",$sub_item->volunteer_names);
																			$volunteer_handles=explode(",",$sub_item->volunteer_handles);
																			foreach($volunteer_names as $key=>$vnames){
																				if($key<=4){
																	?>
																			<a href="<?=base_url("user/".trim($volunteer_handles[$key]))?>" class="breakdown-volunteers-list" title="<?=$vnames?>">
																				<i class="fa fa-user icons" aria-hidden="true"></i>
																			</a>	
																	<?php
																				}else{
																					echo ".....";
																				}
																			}
																		}
																	?>
																</div>
															</div>
															<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 action text-right">
																<?php if($this->session->userdata('active_user_loggedin') && $authorization_permission){ ?>
																	<?php if($sub_item->status=='PENDING' && $this->session->userdata('role')=='ORGANIZATION'){ ?>
																		<i class="fa fa-check-circle-o icons" title="Approve" aria-hidden="true" onClick="Alil.eventScript.taskApproval(this)" data-hash="<?=$this->alil_lib->encrypt_data($sub_item->id)?>"></i>
																	<?php } ?>
																	<?php if($this->session->userdata('role')=='VOLUNTEER' && $sub_item->volunteer_ids!='' && in_array($user_id,$volunteer_ids)){ ?>
																		<i class="fa fa-sign-out icons" title="Unsubscribe" aria-hidden="true" onClick="Alil.eventScript.unsubscribed(this)" data-hash="<?=$this->alil_lib->encrypt_data($sub_item->id)?>"></i>
																		<i class="fa fa-pencil-square-o icons" title="Edit" aria-hidden="true" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "update-sub-task", "event"))?>?task=<?=$this->alil_lib->encrypt_data($sub_item->id)?>'"></i>
																	<?php }else if($this->session->userdata('role')=='VOLUNTEER'){ ?>
																		<i class="fa fa-sign-in icons" title="Subscribe" aria-hidden="true" onClick="Alil.eventScript.subscribed(this)" data-hash="<?=$this->alil_lib->encrypt_data($sub_item->id)?>"></i>
																	<?php }else if($this->session->userdata('role')=='ORGANIZATION'){ ?>
																		<i class="fa fa-pencil-square-o icons" title="Edit" aria-hidden="true" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "update-sub-task", "event"))?>?task=<?=$this->alil_lib->encrypt_data($sub_item->id)?>'"></i>
																	<?php } ?>
																	
																	<?php if($this->session->userdata('role')=='ORGANIZATION'){ ?>
																		<i class="fa fa-trash icons" title="Delete" aria-hidden="true" onClick="Alil.eventScript.removeSubTask(this)" data-hash-target="breakdown-sub-<?=$key?>" data-hash="<?=$this->alil_lib->encrypt_data($sub_item->id)?>"></i>
																	<?php } ?>
																<?php } ?>
															</div>
														</div>
													</div>
								<?php
												}
											}
										}
									}else{
								?>
										<div class="list-group-item break-down-list-pheading text-center">Information will be updated soon</div>
								<?php
									}
								?>
								<!--first-->
							</div>
						</div>
					</div>
					<!--#Related Events-->
					<?php $this->load->view('event/related-events') ?>
					<!--#Related Events-->
				</div>
			</div>
		</div>
	</div>
</div>