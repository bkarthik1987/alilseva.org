<?php $this->load->view('popup/time-zone') ?>
<?php $this->load->view('popup/repetitive') ?>
<div class="contact-us-container">
	<div class="container">
		<div class="row">
			<div id="create-events-form" class="col-xs-12 col-sm-7 col-md-7 col-lg-7 col-lg-offset-3 col-md-offset-3 col-sm-offset-3 contact-form wow fadeInLeft create-events-form">
				<div class="text-center"><i class="fa fa-spinner fa-spin"></i></div>
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