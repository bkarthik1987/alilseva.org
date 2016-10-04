<div id="TimeZoneModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Event TIme Zone</h4>
		</div>
		<div class="modal-body loginmodal-container">
			<form>
				<div class="row">
					<div class="col-xs-12 col-sm-1 col-md-1">
						<input type="checkbox" name="name" value="checked" class="separate-timezone"/>
					</div>
					<div class="col-xs-12 col-sm-10 col-md-10 text-left">
						Use separate start and end time zones
					</div>
				</div>
				<div class="show-single-timezone">
					<br />
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<select name="country" id="country">
								<option value="IN">India</option>
								<option value="GY">Guyana</option>
								<option value="HT">Haiti</option>
								<option value="FJ">Fiji</option>
								<option value="EC">Ecuador</option>
								<option value="CL">Chile</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<select name="country" id="country">
								<option value="Asia/Calcutta">(GMT+05:30) India Standard Time</option>
							</select>
						</div>
					</div>
				</div>
				<div class="show-seperate-timezone hidden">
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Event start</legend>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<select name="country" id="country">
									<option value="IN">India</option>
									<option value="GY">Guyana</option>
									<option value="HT">Haiti</option>
									<option value="FJ">Fiji</option>
									<option value="EC">Ecuador</option>
									<option value="CL">Chile</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<select name="country" id="country">
									<option value="Asia/Calcutta">(GMT+05:30) India Standard Time</option>
								</select>
							</div>
						</div>
					</fieldset>
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Event End</legend>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<select name="country" id="country">
									<option value="IN">India</option>
									<option value="GY">Guyana</option>
									<option value="HT">Haiti</option>
									<option value="FJ">Fiji</option>
									<option value="EC">Ecuador</option>
									<option value="CL">Chile</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<select name="country" id="country">
									<option value="Asia/Calcutta">(GMT+05:30) India Standard Time</option>
								</select>
							</div>
						</div>
					</fieldset>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<input type="submit" name="login" class="login loginmodal-submit" value="Done">
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<input type="submit" name="login" class="login loginmodal-submit" value="Cancel" data-dismiss="modal">
					</div>
				</div>
			</form>
		</div>
    </div>
  </div>
</div>