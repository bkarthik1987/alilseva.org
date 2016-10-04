<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $title ?></title>
		<?php $this->load->view('meta-header'); ?>
    </head>
    <body>
        <?php $this->load->view('navigation'); ?>
		
		<?php if($home_page){ ?>
			<!-- Slider -->
			<div class="slider-2-container">
				<?php $this->load->view('slider'); ?>
			</div>
		<?php }else{ ?>
			<?php if(isset($top_container_style3) && $top_container_style3){ ?>
				<div class="top-underline">&nbsp;</div>	
			<?php }else if(isset($top_container_style1) && $top_container_style1){ ?>
				<!-- Page Title -->
				<div class="page-title-container">
					<div class="container">
						<div class="row">
							<div class="col-sm-12 wow fadeIn">
								<!--<i class="fa fa-envelope"></i>-->
								<h1><?= $title ?></h1>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
        
		<?php echo $content ?>
        <!-- Footer -->
        <footer>
            <?php $this->load->view('footer'); ?>
        </footer>
		<?php $this->load->view('popup/popup') ?>
		<?php $this->load->view('popup/subscribe-popup') ?>
		<?php $this->load->view('popup/tracking-popup') ?>
		<?php $this->load->view('popup/location-popup') ?>
		<?php $this->load->view('popup/confirm-message-modal') ?>
		
    </body>

</html>