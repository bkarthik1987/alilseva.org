<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['host_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['host_url'] .= "://".$_SERVER['HTTP_HOST'];
$config['base_url'] = $config['host_url'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);

$config['HASH_ID_SALT'] = 'Fg$4#df!Afj785F';
$config['HASH_ALPHABET'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';

$config['availabilty'] = array("FULLDAY"=>"Full day","WEEKEND"=>"2 to 4 hours over weekend","WEEKDAYS"=>"2 to 4 hours on weekdays 6PM to 8PM");
$config['location_preference'] = array("MY_LOCATION"=>"My Location","20KM"=>"With in 20kms","ANY_DISTANCE"=>"Any distance");

/* End of file alil_config.php */
/* Location: ./application/config/alil_config.php */
