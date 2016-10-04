<div class="alert alert-success hidden">&nbsp;</div>

<div class="alert alert-info hidden">&nbsp;</div>

<div class="alert alert-warning hidden">&nbsp;</div>

<div class="alert alert-danger hidden">&nbsp;</div>
<?php if($this->session->flashdata('show_success_login_popup') && $this->session->flashdata('show_success_login_popup')===true){ ?>
	<div class="alert alert-success flashdata"><?php echo $this->session->flashdata('verification_msg'); ?></div>
<?php } ?>
<?php if($this->session->flashdata('show_verfy_popup') && $this->session->flashdata('show_verfy_popup')===true){ ?>
	<div class="alert alert-info flashdata"><?php echo $this->session->flashdata('verification_msg'); ?></div>
<?php } ?>