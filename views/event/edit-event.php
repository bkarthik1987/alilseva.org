<div class="contact-us-container edit-events">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 contact-form wow fadeInLeft">
				<form role="form" class="ajaxPutForm" action="<?php echo base_url('api/v1/organization/update-events'); ?>" method="post" application="update-event" id="update-event">
					<?php $this->load->view('alerts'); ?>
					<input type="hidden" name="event_id" value="<?= $this->alil_lib->encrypt_data($event_detail->event_id) ?>" />
					<input type="hidden" name="event_schedule_id" value="<?= $this->alil_lib->encrypt_data($event_detail->event_schedule_id) ?>" />
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<label for="event-name">Event Name</label>
								<input type="text" name="event_name" value="<?= $event_detail->event_name ?>" placeholder="Event Name..." id="event-name">
							</div>
							<div class="form-group">
								<label for="description">Description</label>
								<textarea name="description" id="description"><?= $event_detail->event_description ?></textarea>
							</div>
							<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding">
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 no-left-padding">
									<input type="radio" name="type" value="FULLDAY" id="one-time-event" class="event_schedule_type" <?=$event_detail->event_type=='ONETIME'?'checked':''?>/>&nbsp;&nbsp;One Time Event
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 no-left-padding">
									<input type="radio" name="type" value="PARTIAL" class="event_schedule_type edit-repetitive" <?=$event_detail->event_type=='REPITIVE'?'checked':''?>/>&nbsp;&nbsp;Repetitive
								</div>
							</div>
							<div class="form-group repetitive-opions <?=$event_detail->event_type=='ONETIME'?'hidden':''?>" >
								<?php if($event_detail->repetitive_events_id){ ?>
									<input type="hidden" name="repetitive_events_id" value="<?=$this->alil_lib->encrypt_data($event_detail->repetitive_events_id)?>">
								<?php } ?>
								<label for="">Repetitive Events</label>
								<select name="repetitive" id="repeat">
									<option value="DAILY" title="Daily" <?=$event_detail->repetitive=='DAILY'?'selected':''?>>Daily</option>
									<option value="WEEKLY" title="Weekly" <?=$event_detail->repetitive=='WEEKLY'?'selected':''?>>Weekly</option>
									<option value="MONTHLY" title="Monthly" <?=$event_detail->repetitive=='MONTHLY'?'selected':''?>>Monthly</option>
									<option value="YEARLY" title="Yearly" <?=$event_detail->repetitive=='YEARLY'?'selected':''?>>Yearly</option>
								</select>
							</div>
							<div class="form-group show_repeat_days <?=$event_detail->event_type=='ONETIME'?'hidden':''?>" id="repetitive-events">
								<div class="row no-left-padding show_repeat_days <?=$event_detail->event_type=='ONETIME'?'hidden':''?>">
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">Repeat on:</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">
										<div class="weekly <?=$event_detail->repetitive=='WEEKLY'?'':'hidden'?>">
											<input type="checkbox" name="repeat_on_weekly" value="SUN" <?=$event_detail->repeat_on=='SUN'?'checked':''?> class="repeat_on_weekly"/>&nbsp;S&nbsp;
											<input type="checkbox" name="repeat_on_weekly" value="MON" <?=$event_detail->repeat_on=='MON'?'checked':''?> class="repeat_on_weekly"/>&nbsp;M&nbsp;
											<input type="checkbox" name="repeat_on_weekly" value="TUS" <?=$event_detail->repeat_on=='TUS'?'checked':''?> class="repeat_on_weekly"/>&nbsp;T&nbsp;
											<input type="checkbox" name="repeat_on_weekly" value="WED" <?=$event_detail->repeat_on=='WED'?'checked':''?> class="repeat_on_weekly"/>&nbsp;W&nbsp;
											<input type="checkbox" name="repeat_on_weekly" value="THU" <?=$event_detail->repeat_on=='THU'?'checked':''?> class="repeat_on_weekly"/>&nbsp;T&nbsp;
											<input type="checkbox" name="repeat_on_weekly" value="FRI" <?=$event_detail->repeat_on=='FRI'?'checked':''?> class="repeat_on_weekly"/>&nbsp;F&nbsp;
											<input type="checkbox" name="repeat_on_weekly" value="SAT" <?=$event_detail->repeat_on=='SAT'?'checked':''?> class="repeat_on_weekly"/>&nbsp;S&nbsp;
										</div>
										<div class="monthly <?=$event_detail->repetitive=='MONTHLY'?'':'hidden'?>">
											<select name="repeat_on_monthly" id="repeat_on_monthly">
												<?php 
													for($i=1;$i<=31;$i++){ 
												?>
													<option value="<?=$i?>" <?=$event_detail->repeat_on==$i?'selected':''?>><?=$i?></option>
												<?php	
													} 
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="multi-line-form-group">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
									<div class="form-group">
										<label for="from-date">From Date</label>
										<div class="input-append date form_datetime" id="from-date">
											<input type="text" readonly class="custom-datepicker-input" value="<?=date("d/m/Y", strtotime($event_detail->start_time))?>" name="from_date" placeholder="From Date...">
											<span class="add-on">
												<i class="fa fa-calendar date-icons custom_datepicker_icons" aria-hidden="true"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-right-padding">
									<div class="form-group">
										<label for="to-date">To Date</label>
										<div class="input-append date form_datetime" id="from-time">
											<input type="text" class="sm-input"  name="from_time" class="required" placeholder="From Time..." value="<?=date("h:i A", strtotime($event_detail->start_time))?>">
											<span class="add-on">
												<i class="fa fa-calendar date-icons custom_datepicker_icons" aria-hidden="true"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-right-padding">
									<div class="form-group">
										<label for="to-date">&nbsp;</label>
										<div class="input-append date form_datetime" id="to-time">
											<input type="text" class="sm-input" name="to_time" placeholder="To Time..." value="<?=date("h:i A", strtotime($event_detail->end_time))?>">
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
										<label for="to-date">Importance </label>
										<select name="importance" id="importance">
											<option value="">Please select</option>
											<option value="LOW" <?=$event_detail->event_importance=='LOW'?'selected':''?>>Low</option>
											<option value="MEDIUM" <?=$event_detail->event_importance=='MEDIUM'?'selected':''?>>Medium</option>
											<option value="HIGH" <?=$event_detail->event_importance=='HIGH'?'selected':''?>>High</option>
										</select>
									</div>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
									<div class="form-group">
										<label for="location">Location</label>
										<input type="text" name="tlocation" class="typeahead required" value="<?= $event_detail->country_name.','.$event_detail->district_name.','.$event_detail->location_name ?>" id="tlocation" autocomplete="off" placeholder="Your location" application="location" />
										<div class="selected-list"><ul>
											<input type="hidden" name="location" value="<?= $this->alil_lib->encrypt_data($event_detail->location_id) ?>" />
										</ul></div>
									</div>
								</div>
							</div>
							<div class="form-group <?=$event_detail->event_domain_id==false?'hidden':'' ?>" id="domain-activity">
								<label for="domain">Domain of activity</label>
								<select name="domain" id="domain-of-activity">
									<option value="">Please select</option>
									<?php
										if(isset($event_domain)){
											foreach($event_domain as $domain){
									?>
												<option value="<?=$this->alil_lib->encrypt_data($domain->id)?>" <?=$event_detail->event_domain_id==$domain->id?'selected':''?>><?=$domain->name?></option>
									<?php
											}
										}
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="what-you-need">Do you know what you need?</label>
								<select name="what_you_need" id="what-you-need">
									<option value="Yes" <?php if($event_detail->item_id && $event_detail->skill_id){ echo 'selected'; } ?>>Yes</option>
									<option value="No" <?php if(!$event_detail->item_id && !$event_detail->skill_id){ echo 'selected'; } ?>>No</option>
								</select>
							</div>
							<div class="form-group <?php if(!$event_detail->item_id && !$event_detail->skill_id){ echo 'hidden'; } ?>" id="nature-of-help">
								<label for="nature-of-help"><strong>Nature of help required</strong></label>
								<div class="multi-line-form-group">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
										<div class="form-group">
											<label for="need-volunteers">Need Volunteers in following areas</label>
											<input type="text" class="typeahead" name="" id="skills" autocomplete="off" placeholder="Need Volunteers in following areas" application="skills" />
											<?php if($event_detail->volunteers_need_id){ ?>
												<input type="hidden" name="volunteers_need_id" value="<?=$this->alil_lib->encrypt_data($event_detail->volunteers_need_id)?>">
											<?php } ?>
											<div class="selected-list">
												<ul>
													<?php 
														if(isset($event_skills)){ 
															foreach($event_skills as $event_skill){
													?>
														<li data-hash="<?=$this->alil_lib->encrypt_data($event_skill->skill_id)?>"><?=$event_skill->skill_name?>
															<i class="fa fa-times-circle links delete-skills" data-hash="<?=$this->alil_lib->encrypt_data($event_skill->id)?>" application="live" aria-hidden="true"></i>
															<input type="hidden" name="skills[]" value="<?=$this->alil_lib->encrypt_data($event_skill->skill_id)?>">
														</li>
													<?php
															}
														}
													?>
												</ul>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
										<div class="form-group">
											<label for="need-following-items">Need following items</label>
											<input type="text" class="typeahead" name="" id="items" autocomplete="off" placeholder="Need following items" application="items" />
											<?php if($event_detail->items_need_id){ ?>
												<input type="hidden" name="items[]" value="<?=$this->alil_lib->encrypt_data($event_detail->items_need_id)?>">
											<?php } ?>
											<div class="selected-list">
												<ul>
													<?php 
														if(isset($event_items)){ 
															foreach($event_items as $event_item){
													?>
														<li data-hash="<?=$this->alil_lib->encrypt_data($event_item->id)?>"><?=$event_item->item_name?>
															<i class="fa fa-times-circle links delete-items" data-hash="<?=$this->alil_lib->encrypt_data($event_item->id)?>" application="live" aria-hidden="true"></i>
															<input type="hidden" name="items[]" value="<?=$this->alil_lib->encrypt_data($event_item->item_id)?>">
														</li>
													<?php
															}
														}
													?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding text-center">
						<button type="submit" class="btn submit-button">Save</button>
						<button type="button" class="btn" onClick="window.location.href='<?=base_url($this->alil_lib->makeEventUrl($event_detail, "info", "event"))?>'">Back</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var locality=$.parseJSON('<?=$locality?>');
var skills=$.parseJSON('<?=$skills?>');
var items=$.parseJSON('<?=$items?>');
Alil.script.initialize(skills, [], locality, [], items);
$(window).bind("load", function() {
	var data = [];
	var htmlOutput = $("#create-event-step1").html();
	$("#create-events-form").html(htmlOutput);
	$("#create-events").validate();
});
</script>