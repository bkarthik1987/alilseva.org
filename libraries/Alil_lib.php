<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Hashids/HashGenerator.php";
require_once "Hashids/Hashids.php";

class Alil_lib {
	function verifyUserLogin($status){
		$CI =& get_instance();
		if($status){
			if($CI->session->userdata('active_user_loggedin')){
				redirect(base_url());
			}	
		}else{
			if(!$CI->session->userdata('active_user_loggedin')){
				redirect(base_url());
			}	
		}
	}
	function base64clean($base64string){
		$base64string = str_replace(array('=','+','/'),'',$base64string);
		return $base64string;
	}	 
	function encrypt_data($string) {
		if (isset($string) || !empty($string)) {
			$string = trim($string);
			$string = base64_encode(@serialize($string));   
			return $this->base64clean($string);
		}
	}
	function decrypt_data($string) {
		if (isset($string) || !empty($string)) {
		   $string = trim($string);
           return @unserialize(base64_decode($string));
		}
	}
	function encodeHashData($string){
		$CI =& get_instance();
		$encrypt_value = '';
		if($string){
			echo $string;
			$hashids = new Hashids\Hashids($CI->config->item('HASH_ID_SALT'), 12, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz');
			$encrypt_value = $hashids->encode_hex($string);
		}
		return $encrypt_value;
	}
	function decodeHashData($string){
		$decrypt_value = '';
		$CI =& get_instance();
		if($string){
			$hashids = new Hashids\Hashids($CI->config->item('HASH_ID_SALT'), 12, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz');
			$decrypt_value = $hashids->decode_hex($string);
		}
		return $decrypt_value;
	}
	function SMTPMAILCONFIG(){
				$config = Array(
					'protocol' => 'smtp',
                    'smtp_host' => 'smtp.googlemail.com',
					'smtp_crypto' => 'ssl',
                    'smtp_port' => 465,
                    'smtp_user' => 'aliluseava@gmail.com',
                    'smtp_pass' => '@liluSeva',
                    'mailtype'  => 'html', 
                    'charset'   => 'iso-8859-1',
					'newline'	=>	'\r\n'
                );
				/*$config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'tls://email-smtp.us-east-1.amazonaws.com',
                    'smtp_port' => 465,
                    'smtp_user' => 'AKIAJDSP6PJGKKL7VDXA',
                    'smtp_pass' => 'AoIiThc0Y4P9C81QeF5owLf4jKa+AKTMWHG6qaaTDGVh',
                    'mailtype'  => 'html', 
                    'charset'   => 'iso-8859-1'
                );*/
		return $config;
	}
	function sendEmailNotification($email, $subject, $msg){
		$CI=&get_instance();
		$config=$this->SMTPMAILCONFIG();
		$CI->load->library('email', $config);
		$CI->email->set_newline("\r\n");
		$CI->email->from(TO_FROM, "Alilu");
		$CI->email->to($email);
		$CI->email->subject($subject);
		$CI->email->message($msg);
		if($CI->email->send()){
			//printr($CI->email->print_debugger());
			return true;
		}else{
			//printr($CI->email->print_debugger());
			return false;
		}
	}
	function setEncryptValue($array_data, $rows, $object=false){
		if(!empty($array_data) && $rows){
			foreach($array_data as $key=>$list){
				foreach($rows as $row){
					if($object)
						$array_data[$key]->$row=$this->encrypt_data($list->$row);
					else
						$array_data[$key][$row]=$this->encrypt_data($list[$row]);
				}	
			}	
		}
		return $array_data;
	}
	function makeShortName($str,$casechange=true){
	
		$src=array(" and "," ", "?", "&", "=", "/", "-", ".", "'", '"'," / "," - ","---","  ","   ","\n","\t","~","!","@","#","$","%","^","&","*","(",")","_","+","|","\\"," , ",", ",",");
		$rep=array("-","-", "", "", "", "-", "-", "-", "", "","-","-","-","-","-","-","-","","","","","","","","","","","","","","","","-","-","-");
		$str=urlencode(($casechange?strtolower(str_replace($src, $rep, $str)):str_replace($src, $rep, $str)));
		$str=str_replace(array('---','--'),array('-','-'), $str);
		return $str;
	}
	function makeEventUrl($event, $tab, $source_type){
		$url=base_url();
		if($source_type=='event' && !empty($event)){
			$url=$event->event_short_name."/".$tab."/".$this->encrypt_data($event->event_id);
		}else{
			$url=$event->event_short_name."/".$tab."/".$this->encrypt_data($event->id);
		}
		return $url;
	}
	function resizeImage($remoteImage,$maxwidth,$maxheight, $ext){
		$imagepath=$remoteImage;
		$imagedata=getimagesize($imagepath);
		$imgwidth=$imagedata[0];
		$imgheight=$imagedata[1];
		//$ext='.jpg';
		$shrink=1;
		/*if($imgwidth > $maxwidth){
			$shrink=$maxwidth/$imgwidth;
		}
		if($shrink!=1){*/
		  $output_height=$maxheight;
		  $output_width=$maxwidth;
		/*}else{
		  $output_height=$imgheight;
		  $output_width=$imgwidth;
		}*/
		if( $output_height > $maxheight ){
			$shrink = $maxheight / $output_height ;
			$output_width = $shrink * $output_width;
			$output_height = $maxheight;
		} 

		switch($ext){
			case ".gif":
				$src_image = @imagecreatefromgif($imagepath);
				$dest_image = @imagecreatetruecolor ($output_width, $output_height);
				imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $output_width, $output_height, $imgwidth, $imgheight);
				imagegif($dest_image, $imagepath, 100);
			break;
			case ".jpg":
				$src_image = @imagecreatefromjpeg($imagepath);
				if($src_image===false)
				  return 0;
				$dest_image = @imagecreatetruecolor ($output_width, $output_height);
				imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $output_width, $output_height, $imgwidth, $imgheight);
				imagejpeg($dest_image, $imagepath, 100);
			break;
			case ".jpeg":
				$src_image = @imagecreatefromjpeg($imagepath);
				$dest_image = @imagecreatetruecolor ($output_width, $output_height);
				imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $output_width, $output_height, $imgwidth, $imgheight);
				imagejpeg($dest_image, $imagepath, 100);
			break;
			case ".png":
				$src_image = @imagecreatefrompng($imagepath);
				$dest_image = @imagecreatetruecolor ($output_width, $output_height);
				imagecopyresampled($dest_image, $src_image, 0, 0, 0, 0, $output_width, $output_height, $imgwidth, $imgheight);
				imagepng($dest_image, $imagepath, 5);
			break;
		}
		 return 1;
	}
}
?>