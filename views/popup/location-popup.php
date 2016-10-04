<div id="locationModal" class="modal" role="dialog">
  <div class="modal-dialog  modal-md">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Location</h4>
			<select name="" id="filter-state-list">
				<option value="">Select State</option>
				<?php
					if(!empty($states)){
						foreach($states as $state){
				?>
						<option value="<?=$state->id?>" <?=$state->id==$this->alil_lib->encrypt_data($default_state)?"selected":""?> ><?=$state->name?></option>
				<?php			
						}
					}
				?>
			</select>
		</div>
		<div class="modal-body">
			<div class="row text-left event-filtering" id="location-filter">
				<?php
					if(!empty($locations)){
						foreach($locations as $location){
				?>
						<div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 filter" application="location" application-target="#filtering-location-name" data-hash="<?=$location->id?>" data-hash-text="<?=$location->name?>"><a href="javascript:void(0);"><?=$location->name?></a></div>
				<?php			
						}
					}else{
				?>
						<div class="text-center">Information will updated soon</div>
				<?php		
					}
				?>
			</div>	
		</div>
    </div>
  </div>
</div>