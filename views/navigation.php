 <!-- Top menu -->
<nav class="navbar" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
				<a class="navbar-brand" href="<?php echo base_url(); ?>">Alil - a super cool design agency...</a>
		</div>
		
		
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="top-navbar-1">
			<ul class="nav navbar-nav navbar-right">
				<?php if($this->session->userdata('active_user_loggedin')){ ?>
				<li class="dropdown <?php if($uri_data === "profile" || $uri_data === "my-events"){ echo 'active'; } ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000">
						<i class="fa fa-info"></i><br>User Info <span class="caret"></span>
					</a>
					<ul class="dropdown-menu dropdown-menu-left" role="menu">
						<li class="<?=$uri_data === "profile"?'active':'' ?>">
							<a href="<?php echo base_url('profile'); ?>">My Profile</a>
						</li>
						<li class="<?=$uri_data === "change-password"?'active':'' ?>">
							<a href="<?php echo base_url('change-password'); ?>">Change Password</a>
						</li>
						<?php if($this->session->userdata('role')==='VOLUNTEER'){ ?>
						<li class="<?=$uri_data === "my-events"?'active':'' ?>">
							<a href="<?php echo base_url('user/dashboard/volunteer'); ?>">Dashboard</a>
						</li>
						<?php } ?>
						<?php if($this->session->userdata('role')==='ORGANIZATION'){ ?>
							<?php if($this->session->userdata('status')==='ACTIVE'){ ?>
							<li class="<?=$uri_data === "attestation"?'active':'' ?>">
								<a href="<?php echo base_url('attestation'); ?>">Attestation</a>
							</li>
							<?php } ?>
						<li class="<?=$uri_data === "my-events"?'active':'' ?>">
							<a href="<?php echo base_url('user/dashboard/organization'); ?>">Dashboard</a>
						</li>
						<?php } ?>
					</ul>
				</li>
				<?php if($this->session->userdata('role')==='ORGANIZATION' && $this->session->userdata('status')==='ACTIVE'){ ?>
				<li>
					<a href="<?php echo base_url('create-events'); ?>" class="<?=$uri_data === "create-events"?'active':'' ?>">
						<i class="fa fa-pencil"></i><br>Create Events
					</a>
				</li>
				<?php } ?>
				<li>
					<a href="javascript:void(0)" onClick="Alil.util.logout();">
						<i class="fa fa-sign-out"></i><br>Sign Out
					</a>
				</li>
				<?php }else{ ?>
				<li>
					<a href="<?php echo base_url('signup'); ?>" class="<?=$uri_data === "signup"?'active':'' ?>">
						<i class="fa fa-pencil"></i><br>Sign Up
					</a>
				</li>
				<li>
					<a href="javascript:void(0)" data-toggle="modal" data-target="#siginModal">
						<i class="fa fa-sign-in"></i><br>Sign In
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>