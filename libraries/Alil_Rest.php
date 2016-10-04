<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 */
class AlilRest extends REST_Controller {
	public $_user;
    function __construct(){
        parent::__construct();
		$this->lang->load('api_status_code', 'english');
    }
	
	protected function verifyAccess(){
		$access_token=array();
		$authorization = $this->head('Authorization');
		$authorized = false;
		$token_expired=false;
		if($authorization){
			$this->load->model('mgeneral');
			$sessoin_data=$this->mgeneral->checkAuthorizationPermission($authorization);
			if(empty($sessoin_data)){
				$token_expired=true;
			}else{
				$expire_time=$sessoin_data->last_activity + (600 * 60);
				if($expire_time >= time()){
					$this->mgeneral->updateLastActivity($authorization);
					$authorized = true;
				}else{
					$token_expired=true;
					$authorized = false;
				}
				$access_token=$sessoin_data->access_token;
			}
			
		}
		if($authorized){
			$this->setUserInfo($access_token);
			return;
		}else if($token_expired){
			$this->sendTokenExpire();
		}else{
			$this->sendUnauthorized();
		}
	}
	function setUserInfo($access_token){
		$user_details=explode('|',$this->alil_lib->decrypt_data($access_token));
		if(sizeof($user_details)==3){
			$this->_user = array('user_id'=>isset($user_details[0])?$user_details[0]:"",'username'=>isset($user_details[1])?$user_details[1]:"",'role'=>isset($user_details[2])?$user_details[2]:"");
		}else{
			$this->_user = array('user_id'=>null,'username'=>null,'role'=>null);
		}
	}
	function sendUnauthorized(){
		$this->response(array('status'=>'FAILED','error_code'=>'1401','message'=>$this->lang->line('error_1401')),self::HTTP_UNAUTHORIZED);
	}
	function sendTokenExpire(){
		$this->response(array('status'=>'FAILED','error_code'=>'1400','message'=>$this->lang->line('error_1400')));
	}
}