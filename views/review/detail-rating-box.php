<span class="rating-text">
	<div class="vv_good"><?= isset($source->points)&&$source->points>0?$rating_description:'No Ratings' ?> </div>
	<div>Based on  <span  property="reviewCount"><?=isset($source->votes)?$source->votes:0 ?></span> votes</div>
</span>
<span class="rating <?= isset($source->points)?$this->alil_lib->makeShortName($this->mrating->getRatingDescription($source->points))."_rated":'default_rated' ?> rate-number"><?= isset($source->points)&&$source->points>0?$source->points:"-"?></span>