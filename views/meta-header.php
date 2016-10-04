<!-- CSS -->

<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Droid+Sans">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lobster">

<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/animate.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/magnific-popup.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/flexslider/flexslider.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/form-elements.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/media-queries.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/popup.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/common.css'); ?>">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Favicon and touch icons -->
<link rel="shortcut icon" href="<?php echo base_url('assets/ico/favicon.ico'); ?>">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url('assets/ico/apple-touch-icon-144-precomposed.png'); ?>">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url('assets/ico/apple-touch-icon-114-precomposed.png'); ?>">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url('assets/ico/apple-touch-icon-72-precomposed.png'); ?>">
<link rel="apple-touch-icon-precomposed" href="<?php echo base_url('assets/ico/apple-touch-icon-57-precomposed.png'); ?>">
 <!-- Javascript -->
<script type="text/javascript"> 
	var page_data = {};
	var set_localStorage = '';
	var availabilty = JSON.parse('<?=json_encode($this->config->item('availabilty'))?>');
	var location_preference = JSON.parse('<?=json_encode($this->config->item('location_preference'))?>');
</script>
<?php $this->load->view('scripts') ?>
