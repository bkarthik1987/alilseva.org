<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/Alil_Rest.php';

class Volunteer extends AlilRest {
	var $_response_data;
	public function __construct(){
		parent::__construct();
		$this->load->model('mvolunteer');
		
	}
	public function _remap($method, $arguments = []){
		if($this->urlTokenizer($method))
			$method=$this->urlTokenizer($method);
		
		$uri_method= $method . '_' . $this->request->method;
		if(!in_array($method,array('signup'))){
			parent::verifyAccess();
		}
		if(method_exists($this,$uri_method)){
			call_user_func_array([$this, $uri_method], $arguments);
			return;
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1402;
			$this->buildResponse();
		}
	}
	function buildResponse(){
		if(isset($this->_response_data['error_code']) && $this->_response_data['error_code']){
			$this->_response_data['message']=$this->lang->line('error_'.$this->_response_data['error_code']);
		}
		$response_code = self::HTTP_OK;
		if(isset($this->_response_data['error_code']) && $this->_response_data['error_code'] == 1401){
			$response_code = self::HTTP_UNAUTHORIZED;
		}
		$this->response($this->_response_data, $response_code);
	}
	function urlTokenizer($method){
		$uri_method=str_replace(" ", '', lcfirst(ucwords(str_replace("-"," ",$method))));
		return $uri_method;
	}
	function signup_post(){
		$this->load->model('mgeneral');
		$this->load->model('mvolunteer');
		$role=$this->post("role")?$this->post("role"):"VOLUNTEER";
		$general_info=$this->post();
		if($general_info && !empty($general_info)){
			$user_data=$general_info;
			$status=$this->mgeneral->checkEmailAddress($user_data['email_address']);
			if(!empty($status)){
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1403;
			}else{
				$return=$this->mvolunteer->createVolunteerUser($user_data, $role);
				if($return['status']){
					$user_id=$return['user_id'];
					$this->_user['user_id']=$user_id;
					$this->_response_data['status']='SUCCESS';
					$this->_response_data['user_id']=$this->alil_lib->encrypt_data($user_id);
					//Process File Upload
					if(isset($_FILES['userfile']) && $_FILES['userfile']['name']!=''){
						$name=$_FILES['userfile']['name'];
						$type=$_FILES['userfile']['type'];
						$tmp_name= $_FILES['userfile']['tmp_name'];
						$error=$_FILES['userfile']['error'];
						$size=$_FILES['userfile']['size'];
						$target_dir="photos/volunteer/".$user_id;
						//Check and Create folder to upload the file
						if($error == UPLOAD_ERR_OK){
							if(verifyUploadDirectory($target_dir)){
								$tmp=explode(".",$name);
								$ext=array_pop($tmp);
								$file_name=md5($user_data['email_address']);
								$upload_path="./$target_dir/$file_name.$ext";
								$i=1;
								while(file_exists($upload_path)){
									$upload_path="./$target_dir/$file_name-$i.$ext";
									++$i;
								}
								move_uploaded_file($tmp_name, $upload_path);
								$uploaded_url=substr($upload_path,1);
								$return_status=$this->mgeneral->saveImage($user_id, $uploaded_url, $ext);
								if($return_status['status'])
									$this->_response_data['profile_url']=$uploaded_url;
							}
						}				
					}
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1408;
				}
				/*$user_id=2;
				$this->_user['user_id']=$user_id;
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['user_id']=$this->alil_lib->encrypt_data($user_id);*/
			}
			
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function volunteerPreference_post(){
		$status=false;
		$preference_info=$this->post();
		$user_id=$this->_user['user_id'];
		
		if(!empty($preference_info) && $user_id!=''){
			$user_data=$preference_info;
			$user_data['active']=1;
			$user_data['created_by']=$user_id;
			$status=$this->mvolunteer->createVolunteerpreference($user_data, $user_id);
		}
		if($status){
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		
		
		$this->buildResponse();
	}
	function skills_post(){
		$status=false;
		$progress_status=true;
		$data=array();
		$skills=$this->post("skills");
		$user_id=$this->_user['user_id'];
		if(isset($skills) && !empty($skills)){
			foreach($skills as $skill){
				if(!is_numeric($this->alil_lib->decrypt_data($skill))){
					$progress_status=false;
					break;
				}	
			}
		}
		if(!$progress_status){
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1421;
		}else{
			if(!empty($skills) && $user_id!='' && $progress_status){
				foreach($skills as $key=>$skill){
					$skill_id=$this->alil_lib->decrypt_data($skill);
					$status=$this->mvolunteer->createUserVolunteerSkills($user_id, $skill_id);
				}
				if($status){
					$this->_response_data['status']='SUCCESS';
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1411;
				}
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1403;
			}
		}
		
		$this->buildResponse();
		
	}
	function services_post(){
		$status=false;
		$progress_status=true;
		$data=array();
		$services=$this->post("services");
		$user_id=$this->_user['user_id'];
		if(isset($services) && !empty($services)){
			foreach($services as $service){
				if(!is_numeric($this->alil_lib->decrypt_data($service))){
					$progress_status=false;
					break;
				}	
			}
		}
		if(!$progress_status){
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1420;
		}else{
			if(!empty($services) && $user_id!=''){
				foreach($services as $key=>$service){
					$service_id=$this->alil_lib->decrypt_data($service);
					$status=$this->mvolunteer->createUserVolunteerServices($user_id, $service_id);	
				}
				if($status){
					$this->_response_data['status']='SUCCESS';
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1412;
				}
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1403;
			}
		}
		$this->buildResponse();
	}
	function socialMedia_post(){
		$status=false;
		$i_status=false;
		$data=array();
		$media_list=array(0=>'LINKEDIN',1=>'TWITTER',2=>'FACEBOOK',3=>'GITHUB');
		$socialmedia=$this->post("socialmedia");
		$user_id=$this->_user['user_id'];
		foreach ($socialmedia as $key => $value) {
			$value = trim($value);
			if(!empty($value)){
				$i_status=true;
				break;
			}
		}
		if(!empty($socialmedia) && $user_id!='' && $i_status){
			$i=0;
			foreach($socialmedia as $key=>$value){
				if($value!=''){
					$data[$i]['user_id']=$user_id;
					$data[$i]['name']=$media_list[$key];
					$data[$i]['handle']=$value;
					$data[$i]['active']=1;
					$data[$i]['created_by']=$user_id;
					$data[$i]['creation_date']=date('Y-m-d H:i:s');
					$i++;
				}
			}
			$status=$this->mvolunteer->createUserSocialMedia($data);	
		}
		if($status){
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1427;
		}
		$this->buildResponse();
	}
	function activities_post(){
		$status=false;
		$data=array();
		$activities=$this->post("activities");
		$user_id=$this->_user['user_id'];
		if(!empty($activities) && $user_id!=''){
			$i=0;
			foreach($activities as $key=>$value){
				$activity_id=$this->alil_lib->decrypt_data($value);
				$status=$this->mvolunteer->createUserActivities($user_id, $activity_id);	
			}
			if($status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['user_id']=$this->alil_lib->encrypt_data($user_id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1413;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		
		$this->buildResponse();
	} 
	function newActivities_post(){
		$activities=$this->post('activites');
		$user_id=$this->_user['user_id'];
		if($activities!='' && $user_id!=''){
			$new_data['created_by']=$user_id;
			$new_data['active']=1;
			$new_data['creation_date']=date('Y-m-d H:i:s');
			$new_data['name']=$activities;
			$activity_id=$this->mvolunteer->createNewActivities($new_data);	
			if($activity_id){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['activity_list']=array('activity_id'=>$this->alil_lib->encrypt_data($activity_id),'name'=>$activities);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1413;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}	
		$this->buildResponse();
	}
	function settings_post(){
		$status=false;
		$data=array();
		$communicate_by=$this->post('communicate_by');
		$primary=$this->post('primary');
		$user_id=$this->_user['user_id'];
		if($user_id!=''){
			$data=array(
				'user_id' => $user_id,
				'name'	  => 'PRIMARY',
				'handle'  => $primary,
				'created_by'  => $user_id,
				'creation_date'  => date("Y-m-d H:i:s"),
			);
			$status=$this->mvolunteer->updateSocialMedia($data, $user_id, true);
			if($communicate_by!=''){
				$vdata=array(
					'user_id'		 => $user_id,
					'communicate_by' => $communicate_by,
					'created_by'  => $user_id,
					'creation_date'  => date("Y-m-d H:i:s"),
				);
				$this->mvolunteer->updateVolunteerPreference($vdata, $user_id);
			}
		}
		if($status){
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function volunteerPreference_put(){
		$status=false;
		$i_status=false;
		$user_data=array();
		$preference_info=$this->put();
		$user_id=$this->_user['user_id'];
		if(!empty($preference_info) && $user_id!=''){
			$user_data['user_id']=$user_id;
			$user_data['active']=1;
			$user_data['created_by']=$user_id;
			$user_data['creation_date']=date('Y-m-d H:i:s');
			if(isset($preference_info['availabilty']) && $preference_info['availabilty']!=''){
				$user_data['availabilty']=$preference_info['availabilty'];
				$i_status=true;
			}
			if(isset($preference_info['location_preference']) && $preference_info['location_preference']!=''){
				$user_data['location_preference']=$preference_info['location_preference'];
				$i_status=true;
			}
			if(isset($preference_info['ability']) && $preference_info['ability']!=''){
				$user_data['ability']=$preference_info['ability'];
				$i_status=true;
			}
			if(isset($preference_info['acheivments']) && $preference_info['acheivments']!=''){
				$user_data['acheivments']=$preference_info['acheivments'];
				$i_status=true;
			}
			if(isset($preference_info['interests']) && $preference_info['interests']!=''){
				$user_data['interests']=$preference_info['interests'];
				$i_status=true;
			}
			if(isset($preference_info['communicate_by']) && $preference_info['communicate_by']!=''){
				$user_data['communicate_by']=$preference_info['communicate_by'];
				$i_status=true;
			}
			$user_data['updated_by']=$user_id;
			$user_data['updation_date']=date("Y-m-d H:i:s");
			if($i_status)
				$status=$this->mvolunteer->updateVolunteerPreference($user_data, $user_id);
		}
		if($status){
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1435;
		}
		$this->buildResponse();
	}
	function volunteerPreference_get(){
		$this->load->model('mvolunteer');
		$user_id=$this->_user['user_id'];
		if($user_id){
			$preference=$this->mvolunteer->getVolunteerPreference($user_id);
			$this->_response_data['status']='SUCCESS';
			if(!empty($preference)){
				$preference->id=$this->alil_lib->encrypt_data($preference->id);
				$preference->user_id=$this->alil_lib->encrypt_data($preference->user_id);
				$this->_response_data['volunteer_preference']=$preference;
			}else{
				$this->_response_data['volunteer_preference']=array();
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function volunteerSkills_get(){
		$this->load->model('mvolunteer');
		$user_id=$this->_user['user_id'];
		if($user_id){
			$skills=$this->mvolunteer->getVolunteerSkills($user_id);
			$this->_response_data['status']='SUCCESS';
			if(!empty($skills)){
				$skills=$this->alil_lib->setEncryptValue($skills, array('id','user_id','skill_id'), true);
				$this->_response_data['skills']=$skills;
			}else{
				$this->_response_data['skills']=array();
			}	
			
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function volunteerServices_get(){
		$this->load->model('mvolunteer');
		$user_id=$this->_user['user_id'];
		if($user_id){
			$services=$this->mvolunteer->getVolunteerServices($user_id);
			$this->_response_data['status']='SUCCESS';
			if(!empty($services)){
				$services=$this->alil_lib->setEncryptValue($services, array('id','user_id','service_id'), true);
				$this->_response_data['services']=$services;
			}else{
				$this->_response_data['services']=array();
			}
			
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function removeVolunteerSkill_delete(){
		$user_id=$this->_user['user_id'];
		$volunteer_skill_id=$this->alil_lib->decrypt_data($this->delete('volunteer_skill_id'));
		if($volunteer_skill_id!=''){
			$status=$this->mvolunteer->removeVolunteerSkill($volunteer_skill_id, $user_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function removeVolunteerService_delete(){
		$user_id=$this->_user['user_id'];
		$volunteer_service_id=$this->alil_lib->decrypt_data($this->delete('volunteer_service_id'));
		if($volunteer_service_id!=''){
			$status=$this->mvolunteer->removeVolunteerService($volunteer_service_id, $user_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function volunteerActivityPreference_get(){
		$this->load->model('mvolunteer');
		$user_id=$this->_user['user_id'];
		if($user_id){
			$activities=$this->mvolunteer->getvolunteerActivityPreference($user_id);
			$this->_response_data['status']='SUCCESS';
			if(!empty($activities)){
				$activities=$this->alil_lib->setEncryptValue($activities, array('id','user_id','activity_id'), true);
				$this->_response_data['activites']=$activities;
			}else{
				$this->_response_data['activites']=array();
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
}
?>