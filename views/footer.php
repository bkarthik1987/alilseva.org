<div class="container">
	<div class="row">
		<div class="col-sm-5 footer-box wow fadeInUp">
			<h4>About Us</h4>
			<div class="footer-box-text">
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et. 
					Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.
				</p>
				<p><a href="<?=base_url('about-us')?>">Read more...</a></p>
			</div>
		</div>
		<!--<div class="col-sm-3 footer-box wow fadeInDown">
			<h4>&nbsp;</h4>
			<div class="footer-box-text footer-box-text-subscribe">&nbsp;</div>
		</div>
		<div class="col-sm-3 footer-box wow fadeInUp">
			<h4>&nbsp;</h4>
			<div class="footer-box-text flickr-feed"></div>
		</div>-->
		<div class="col-sm-5 footer-box wow fadeInDown pull-right">
			<h4>Contact Us</h4>
			<div class="footer-box-text footer-box-text-contact">
				<p><i class="fa fa-map-marker"></i> Address: Via Principe Amedeo 9, 10100, Torino, TO, Italy</p>
				<p><i class="fa fa-phone"></i> Phone: 0039 333 12 68 347</p>
				<p><i class="fa fa-user"></i> Skype: Alil_Agency</p>
				<p><i class="fa fa-envelope"></i> Email: <a href="">contact@Alil.co.uk</a></p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 wow fadeIn">
			<div class="footer-border"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-7 footer-copyright wow fadeIn">
			<p>Copyright 2016 Alil - All rights reserved.</p>
		</div>
		<div class="col-sm-5 footer-social wow fadeIn">
			<a href="#"><i class="fa fa-facebook"></i></a>
			<a href="#"><i class="fa fa-dribbble"></i></a>
			<a href="#"><i class="fa fa-twitter"></i></a>
			<a href="#"><i class="fa fa-pinterest"></i></a>
		</div>
	</div>
</div>
<?php 
if(isset($xtemplates) && sizeof($xtemplates)>0){
	foreach($xtemplates as $key => $template) {
		$this->load->view($template);
	}
}
?>