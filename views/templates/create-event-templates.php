<script id="create-event-step1" type="text/x-jsrender">
	<form role="form" id="create-events" action="<?php echo base_url('api/v1/organization/create-events'); ?>" method="post">
		<?php $this->load->view('alerts'); ?>
		<input type="hidden" name="action" value="step1"/>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
					<label for="event-name">Event Name</label>
					<input type="text" name="event_name" class="required" placeholder="Event Name..." id="event-name" />
				</div>
			
				<div class="form-group">
					<label for="description">Description</label>
					<textarea name="description" id="description" placeholder=""></textarea>
				</div>
			</div>
		</div>
		<button type="submit" class="btn submit-button" name="submit" value="next" next-page="2">Next</button>
	</form>
</script>
<script id="create-event-step2" type="text/x-jsrender">
	<form role="form" id="create-events" action="<?php echo base_url('api/v1/organization/event-schedule'); ?>" method="post">
		<?php $this->load->view('alerts'); ?>
		<input type="hidden" name="action" value="step2"/>
		<input type="hidden" name="event_id" value="{{:event_id}}"/>
		<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding no-right-padding">
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-left-padding">
				<div class="form-group">
					<label for="from-date">Start Date</label>
					<input type="text" name="from_date" class="required" placeholder="From Date..." id="from-date" value="<?=date("d/m/Y")?>" />
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 no-left-padding">
				<div class="form-group">
					<label for="from-time">Start Time</label>
					<input type="text" name="from_time" class="required" placeholder="From Time..." id="from-time" value="<?=date("h:00 A")?>">
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1 no-left-padding text-align-vertical-middle">
				<label for="to-time">&nbsp;</label>
				<strong>&nbsp;&nbsp;&nbsp;To</strong>
			</div>
			<!--<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 no-left-padding">
				<div class="form-group">
					<input type="text" name="to_date" placeholder="To Date..." id="to-date" value="<?=date("d/m/Y")?>">
				</div>
			</div>-->
			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 no-left-padding">
				<div class="form-group">
					<label for="to-time">End Time</label>
					<input type="text" name="to_time" placeholder="To Time..." id="to-time" value="<?=date("h")+1 ?>:00 <?=date("A") ?>">
				</div>
			</div>
			<!--<div class="link col-xs-12 col-sm-12 col-md-2 col-lg-2 no-left-padding text-align-vertical-middle" data-toggle="modal" data-target="#TimeZoneModal">
				Time Zone
			</div>-->
		</div>
		<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding">
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 no-left-padding">
				<label>Event Type</label>
				<input type="radio" name="type" value="FULLDAY" id="one-time-event" class="event_schedule_type" checked/>&nbsp;&nbsp;One Time Event
			</div>
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 no-left-padding">
				<label>&nbsp;</label>
				<input type="radio" name="type" value="PARTIAL" class="event_schedule_type repetitive"/>&nbsp;&nbsp;Repetitive
			</div>
			<br /><br />
		</div>
		<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-left-padding" id="repetive-summary">
		</div>
		<div class="multi-line-form-group">
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
				<div class="form-group">
					<label for="importance">Importance</label>
					<select name="importance" id="importance">
						<option value="">Please select</option>
						<option value="Low">Low</option>
						<option value="Medium">Medium</option>
						<option value="High">High</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
				<div class="form-group">
					<label for="location">Location</label>
					<input type="text" name="tlocation" class="typeahead required" id="tlocation" autocomplete="off" placeholder="Your location" application="location" />
					<div class="selected-list"><ul></ul></div>
				</div>
			</div>
		</div>
		<button type="submit" class="btn submit-button" name="submit" value="next">Next</button>
		<button type="submit" class="btn" name="submit" value="cancel">Cancel</button>
	</form>
</script>
<script id="create-event-step3" type="text/x-jsrender">
	<form role="form" id="create-events-need" action="<?php echo base_url('api/v1/organization/update-events'); ?>" method="post">
		<?php $this->load->view('alerts'); ?>
		<input type="hidden" name="action" value="step3"/>
		<input type="hidden" name="event_id" value="{{:event_id}}"/>
		<div class="form-group" id="domain-activity">
			<label for="domain">Domain of activity</label>
			<select name="domain" id="domain-of-activity">
				<option value="">Please select</option>
				<?php 
					if(isset($event_domain)){ 
						foreach($event_domain as $domain){
				?>
							<option value="<?=$this->alil_lib->encrypt_data($domain->id)?>"><?=$domain->name?></option>
				<?php
						}
					}
				?>
			</select>
		</div>
		<div class="form-group">
			<label for="what-you-need">Do you know what you need?</label>
			<select name="what_you_need" id="what-you-need">
				<option value="Yes">Yes</option>
				<option value="No" selected>No</option>
			</select>
		</div>
		<div class="form-group hidden" id="nature-of-help">
			<div class="multi-line-form-group">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-left-padding">
					<div class="form-group">
						<label for="need-volunteers">Need Volunteers in following areas</label>
						<input type="text" class="typeahead" name="" id="skills" autocomplete="off" placeholder="Need Volunteers in following areas" application="skills" />
						<div class="selected-list"><ul></ul></div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 no-right-padding">
					<div class="form-group">
						<label for="need-following-items">Need following items</label>
						<input type="text" class="typeahead" name="" id="items" autocomplete="off" placeholder="Need following items" application="items" />
						<div class="selected-list"><ul></ul></div>
					</div>
				</div>
			</div>
		</div>
		<button type="submit" class="btn submit-button" name="submit" value="next">Save</button>
		<button type="submit" class="btn" name="submit" value="cancel">Cancel</button>
	</form>
</script>