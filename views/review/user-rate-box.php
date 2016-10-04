<div class="rating-a">
	<div class="rating-top-row">
		<div class="rate-icon">
			<input type="hidden" name="rating_id" id="rating_id" value="<?= isset($user_rate->id)?$this->alil_lib->encrypt_data($user_rate->id):''  ?>">
			<div class="user-rate-number">
				<span class="rate-number nomargin"><?= isset($user_rate->points)&&$user_rate->points>0?$user_rate->points:"-"?></span>
				<i class="fa fa-times rating_close_ico clear clear_rating <?= isset($user_rate->points) && $user_rate->points>0 ?'':'hidden' ?> <?= isset($already_reviewed) && $already_reviewed?"review_attached":"" ?>" aria-hidden="true"></i>
			</div>
			<div class="rating-loading hidden">Loading....</div>
		</div>
	</div>
    <select id="bar-rating1" class="bar-rating" name="rating">
		<?php
			for($i=1;$i<=5;$i+=.5){
				$num=number_format($i, 1, '.', '');
		?>
				<option class="option<?php echo $num; ?>" value="<?php echo $num; ?>" ><?php echo $num; ?></option>
		<?php	}	?>
    </select>
</div>