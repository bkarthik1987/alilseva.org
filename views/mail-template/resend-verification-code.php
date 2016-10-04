<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<style type="text/css">
body{
  font-family:tahoma;
}
</style>
</head>
<body style="">
   <!-- Begin Wrapper -->
   <div style="margin:0px auto;">
         <!-- End Header -->
		 <!-- Begin content area -->
		 <div style="margin: 0px;padding:10px; line-height:25px;">
			  <p style="font-size:14px;">
				Hi,<br />
				You are just one step away from Alil. Simply verify your email address by clicking <?php echo $mail['confirm_link']; ?> or enter the below verification code.<br>
				<strong><?php echo trim($mail['vcode']); ?></strong>
			  </p>
			  <?php $this->load->view('mail-template/footer'); ?>
		 </div>
		 <!-- End content area -->
   </div>
   <!-- End Wrapper -->
</body>
</html>