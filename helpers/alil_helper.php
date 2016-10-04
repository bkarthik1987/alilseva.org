<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------
/**
 * CodeIgniter Date Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		
 * @link		
 */
// ------------------------------------------------------------------------
/**
 * Get Formated Date and time
 *
 * Returns time() or its GMT equivalent based on the config file preference
 *
 * @access	public
 * @return	integer
 */
	function printr($str,$exit=false){
		echo "<pre>";
			print_r($str);
		echo "</pre>";
		if($exit)
			exit;  
	}
	function generatePasswordHash($password){
		// A higher "cost" is more secure but consumes more processing power
		$cost = 10;
		// Create a random salt
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		// Prefix information about the hash so PHP knows how to verify it later.
		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		// Hash the password with the salt
		$hash = crypt($password, $salt);
		return $hash;
	}
	function generateString($length = 10) {
		// return "abc123";
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	function verifyUploadDirectory($path){
		$dir=explode("/",$path);
		$dir_path=".";
		foreach($dir as $d){
			$dir_path.="/$d";
			if(!is_dir($dir_path)){
				mkdir($dir_path, "755");
			} 
		}
		return is_dir("./".$path);
	}
	function truncate($text, $chars = 25) {
		$original_text=$text;
		$text = $text." ";
		$text = substr($text,0,$chars);
		$text = substr($text,0,strrpos($text,' '));
		if(strlen($original_text)<$chars)
			$text = $text;
		else	
			$text = $text."...";
		return $text;
	}
/* End of file alil_helper.php */
/* Location: ./application/helpers/alil_helper.php */