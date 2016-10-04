<!-- Contact Us -->
<div class="contact-us-container">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 contact-form wow fadeInLeft">
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th class="col-lg-4">Name</th>
								<th class="col-lg-4">Email Address</th>
								<th class="col-lg-3">Phone Number</th>
								<th class="col-lg-1 text-center">Approve</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								if(!empty($attestation)){
									foreach($attestation as $item){
							?>
									<tr>
										<td><?=$item->username?></td>
										<td><?=$item->emailid?></td>
										<td><?=$item->primary_number?$item->primary_number:"-"?></td>
										<td>
											<button type="button" class="btn btn-secondary approve-btn" role="button" data-hash-text="<?=$item->username?>" data-hash="<?=$this->alil_lib->encrypt_data($item->id)?>" onClick="Alil.script.approveOrganization(this)">Approve</button>
										</td>	
									</tr>
							<?php
									}
								}else{
							?>
								<tr><td colspan="4">No need to approval</td></tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</div>  
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 contact-address wow fadeInUp">
				<h3>We Are Here</h3>
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d497699.99741367646!2d77.35073181656826!3d12.953847712434039!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae1670c9b44e6d%3A0xf8dfc3e8517e4fe0!2sBengaluru%2C+Karnataka+560001!5e0!3m2!1sen!2sin!4v1469178827204" width="100%" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
				<h3>Address</h3>
				<p>Alil Selva <br> 10100, Bangalore, Karnataka, Indai</p>
				<p>Phone: 0039 333 12 68 347</p>
			</div>
		</div>
	</div>
</div>