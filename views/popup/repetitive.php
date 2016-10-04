<div id="RepetitiveModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-md">
    <!-- Modal content-->
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Repetitive</h4>
		</div>
		<div class="modal-body loginmodal-container">
			<form action="" id="repetitiveForm" method="post">
				<div class="show-single-timezone">
					<br />
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left label-bold">Repeats:</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<select name="repeat" id="repeat">
								<option value="DAILY" title="Daily">Daily</option>
								<option value="WEEKLY" title="Weekly">Weekly</option>
								<option value="MONTHLY" title="Monthly">Monthly</option>
								<option value="YEARLY" title="Yearly">Yearly</option>
							</select>
						</div>
					</div>
					<div class="row no-left-padding show_repeat_days hidden">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left label-bold">Repeat on:</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">
							<div class="weekly hidden">
								<input type="checkbox" name="repeat_on_weekly" value="SUN" class="repeat_on_weekly"/>&nbsp;S&nbsp;
								<input type="checkbox" name="repeat_on_weekly" value="MON" class="repeat_on_weekly"/>&nbsp;M&nbsp;
								<input type="checkbox" name="repeat_on_weekly" value="TUS" class="repeat_on_weekly"/>&nbsp;T&nbsp;
								<input type="checkbox" name="repeat_on_weekly" value="WED" class="repeat_on_weekly"/>&nbsp;W&nbsp;
								<input type="checkbox" name="repeat_on_weekly" value="THU" class="repeat_on_weekly"/>&nbsp;T&nbsp;
								<input type="checkbox" name="repeat_on_weekly" value="FRI" class="repeat_on_weekly"/>&nbsp;F&nbsp;
								<input type="checkbox" name="repeat_on_weekly" value="SAT" class="repeat_on_weekly"/>&nbsp;S&nbsp;
							</div>
							<div class="monthly hidden">
								<select name="repeat_on_monthly" id="repeat_on_monthly">
									<?php 
										for($i=1;$i<=31;$i++){ 
									?>
										<option value="<?=$i?>"><?=$i?></option>
									<?php	
										} 
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="row no-left-padding"><br />
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left label-bold">Starts on:</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">
							<input type="text" autocomplete="off" name="start_date" value="<?=date("d/m/Y")?>" id="repeat-on-date" class="repeat-on" readonly/>
						</div>
					</div>
					<div class="row no-left-padding">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left label-bold">Ends:</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-left">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding">
								<input type="radio" name="ends_on" value="NEVER" class="never ends_on repeat_on" checked/>&nbsp;Never&nbsp;<br />
							</div>	
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
								<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-left no-left-padding">
									<input type="radio" name="ends_on" value="AFTER" class="ends_on repeat_on"/>&nbsp;After&nbsp;
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-left no-left-padding no-right-padding occurrences">
									<input type="text" autocomplete="off" name="completion" value="" minlength="" maxlength="8" class="after_show number completion repeat_on" size="2"/>occurrences&nbsp;<br />
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left no-left-padding no-right-padding">
								<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-left no-left-padding">
									<input type="radio" name="ends_on" value="ON" class="ends_on repeat_on"/>&nbsp;On&nbsp;
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-left no-left-padding no-right-padding occurrences">
									<input type="text" autocomplete="off" name="completion" value="" id="completion-date" class="on_show completion repeat_on" size="2"/>&nbsp;<br />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<button type="button" class="btn blue-btn done" id="done" name="submit" data-dismiss="modal">Done</button>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<button type="button" class="btn blue-btn cancel" id="cancel" name="submit" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</form>
		</div>
    </div>
  </div>
</div>