<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/Alil_Rest.php';

class Organization extends AlilRest {
	var $_response_data;
	public function __construct(){
		parent::__construct();
		$this->load->model('morganization');
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
		$general_info=$this->post();
		if($general_info && !empty($general_info)){
			$user_data=$general_info;
			$status=$this->mgeneral->checkEmailAddress($user_data['primary_email_address']);
			if(!empty($status)){
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1403;
			}else{
				$return=$this->morganization->createOrganizationUser($user_data);
				if($return['status']){
					$user_id=$return['user_id'];
					$this->_response_data['status']='SUCCESS';
					$this->_response_data['error_code']=1417;
					$this->_response_data['user_id']=$this->alil_lib->encrypt_data($user_id);
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1408;
				}
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function updateUser_post(){
		$this->load->model('mgeneral');
		$general_info=$this->post();
		$user_id = $this->_user['user_id'];
		$primary_contact_id = $this->post('contact_id')?$this->alil_lib->decrypt_data($general_info['contact_id']):null;
		$secondary_contact_id = $this->post('secondary_contact_id')?$this->alil_lib->decrypt_data($general_info['secondary_contact_id']):"";
		$organization_id = $this->post('organization_id')?$this->alil_lib->decrypt_data($general_info['organization_id']):null;
			
		if($general_info && !empty($general_info) && $user_id!='' && $primary_contact_id!='' && $organization_id!=''){
			$status=$this->morganization->updateOraganizationUser($general_info, $primary_contact_id, $secondary_contact_id, $organization_id, $user_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
				//Process File Upload
				if(isset($_FILES['userfile']) && $_FILES['userfile']['name']!=''){
					$name=$_FILES['userfile']['name'];
					$type=$_FILES['userfile']['type'];
					$tmp_name= $_FILES['userfile']['tmp_name'];
					$error=$_FILES['userfile']['error'];
					$size=$_FILES['userfile']['size'];
					$target_dir="photos/organization/".$user_id;
					//Check and Create folder to upload the file
					if($error == UPLOAD_ERR_OK){
						if(verifyUploadDirectory($target_dir)){
							$tmp=explode(".",$name);
							$ext=array_pop($tmp);
							$file_name=md5($general_info['primary_email_address']);
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
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function createEvents_post(){
		$user_id=$this->_user['user_id'];
		$event_name=$this->post('event_name');
		$description=$this->post('description')?htmlspecialchars($this->post('description')):"";
		$type=$this->post('type')?strtoupper($this->post('type')):"ONETIME";
		$importance=$this->post('importance')?strtoupper($this->post('importance')):"LOW";
		$location_id=$this->post('location')?$this->post('location'):"";
		$domain_id=$this->post('domain')?$this->alil_lib->decrypt_data($this->post('domain')):"";
		$need=$this->post('need')?strtoupper($this->post('need')):"BOTH";
		$status=$this->post('status')?strtoupper($this->post('status')):"ONGOING";
		if($event_name!=''){
			$event_short_name=$this->alil_lib->makeShortName($event_name);
			$event_id=$this->morganization->createEvents($event_name, $event_short_name, $description, $type, $importance, $location_id, $domain_id, $need, $status, $user_id);
			if($event_id){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['event_id']=$this->alil_lib->encrypt_data($event_id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1428;
		}
		$this->buildResponse();
	}
	function updateEvents_put(){
		$this->load->model('mgeneral');
		$this->load->model('mevents');
		$user_id=$this->_user['user_id'];
		$event_id=$this->put('event_id')?$this->alil_lib->decrypt_data($this->put('event_id')):"";
		$event_detail=$this->mevents->getEventDetailByID($event_id, true);
		$data=array();
		if($this->put('event_name'))
			$data['event_name']=$this->put('event_name');
		if($this->put('description'))
			$data['description']=htmlspecialchars($this->put('description'));
		if($this->put('type'))
			$data['type']=strtoupper($this->put('type'));
		if(isset($data['type']) && $data['type']=='FULLDAY'){
			$data['type']='ONETIME';
		}else{
			if(isset($data['type']))
				$data['type']='REPITIVE';
		}
		if($this->put('importance'))
			$data['importance']=strtoupper($this->put('importance'));
		if($this->put('domain'))
			$data['event_domain_id']=$this->alil_lib->decrypt_data($this->put('domain'));
		if($this->put('location')){
			$data['location_id']=$this->alil_lib->decrypt_data($this->put('location'));
			$location_detail=$this->mgeneral->getLocalityDetail($data['location_id']);
			if(!empty($location_detail)){
				if(!empty($event_detail)){
					$event_short_name=$this->alil_lib->makeShortName($location_detail->state_name)."/".$this->alil_lib->makeShortName($location_detail->name)."/".$this->alil_lib->makeShortName($event_detail->event_name);
					$data['short_name']=$event_short_name;
					$data['active']=1;
				}
			}
		}	
		if($this->put('need'))
			$data['need']=strtoupper($this->put('need'));
		if($this->put('status'))
			$data['status']=strtoupper($this->put('status'));
		
		$data['updated_by']=$user_id;
		$data['updation_date']=date("Y-m-d H:i:s");
		if($event_id!=''){
			if(!empty($data))
				$status=$this->morganization->updateEvents($event_id, $data);
			if(isset($status) && $status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['event_id']=$this->alil_lib->encrypt_data($event_id);
				if(isset($event_detail->event_short_name))
					$this->_response_data['event_base_url']=$this->alil_lib->makeEventUrl($event_detail, "info", "event");
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
				if(isset($event_detail->event_short_name))
					$this->_response_data['event_base_url']=$this->alil_lib->makeEventUrl($event_detail, "info", "event");
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1427;
		}
		$this->buildResponse();
	}
	function createRepetitive_post(){
		$user_id=$this->_user['user_id'];
		$event_id=$this->post('event_id')?$this->alil_lib->decrypt_data($this->post('event_id')):"";
		$repetitive=$this->post('repetitive');
		if($repetitive=='WEEKLY'){
			$repeat_on=$this->post('repeat_on_weekly');
		}else if($repetitive=='MONTHLY'){
			$repeat_on=$this->post('repeat_on_monthly');
		}else{
			$repeat_on='';
		}
		if($this->post('start_date')){
			$sdate=$this->post('start_date');
			$sdate = str_replace('/', '-', $sdate);
			$start_date=date('Y-m-d H:i:s', strtotime($sdate));
		}else{
			$start_date='';
		}
		
		$end_type=$this->post('end_type');
		$completion=$this->post('completion');
		$status="REMAIN";
		if($event_id!='' && $repetitive!=''){
			$i_status=$this->morganization->createRepetitive($event_id, $repetitive, $repeat_on, $start_date, $end_type, $completion, $status, $user_id);
			if($i_status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['event_id']=$this->alil_lib->encrypt_data($event_id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1429;
		}
		$this->buildResponse();
	}
	function updateRepetitive_put(){
		$user_id=$this->_user['user_id'];
		$event_id=$this->put('event_id')?$this->alil_lib->decrypt_data($this->put('event_id')):"";
		$repetitive_events_id=$this->put('repetitive_events_id')?$this->alil_lib->decrypt_data($this->put('repetitive_events_id')):"";
		$repetitive=$this->put('repetitive');
		if($repetitive=='WEEKLY'){
			$repeat_on=$this->put('repeat_on_weekly');
		}else if($repetitive=='MONTHLY'){
			$repeat_on=$this->put('repeat_on_monthly');
		}else{
			$repeat_on='';
		}
		if($this->put('from_date')){
			$sdate=$this->put('from_date');
			$sdate = str_replace('/', '-', $sdate);
			$start_date=date('Y-m-d H:i:s', strtotime($sdate));
		}else{
			$start_date='';
		}
		
		$end_type=$this->put('end_type');
		$completion=$this->put('completion');
		$status="REMAIN";
		if($event_id!='' && $repetitive!='' && $repetitive_events_id!=''){
			$i_status=$this->morganization->updateRepetitive($event_id, $repetitive_events_id, $repetitive, $repeat_on, $start_date, $end_type, $completion, $status, $user_id);
			if($i_status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['event_id']=$this->alil_lib->encrypt_data($event_id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else if($event_id!='' && $repetitive!=''){
			$i_status=$this->morganization->createRepetitive($event_id, $repetitive, $repeat_on, $start_date, $end_type, $completion, $status, $user_id);
			if($i_status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['event_id']=$this->alil_lib->encrypt_data($event_id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1429;
		}
		$this->buildResponse();
	}
	function eventSchedule_post(){
		$user_id=$this->_user['user_id'];
		$event_id=$this->post('event_id')?$this->alil_lib->decrypt_data($this->post('event_id')):"";
		$type=$this->post('type')?strtoupper($this->post('type')):"";
		$start_date=$this->post('from_date');
		$start_time=$this->post('from_time');
		$end_time=$this->post('to_time')?$this->post('to_time'):"";
		$event_edate='';
		$sdate=$start_date." ".$start_time;
		$sdate = str_replace('/', '-', $sdate);
		$event_start_date=date('Y-m-d H:i:s', strtotime($sdate));
		if($start_date){
			$edate=$start_date." ".$end_time;
			$edate = str_replace('/', '-', $edate);
			$event_end_date=date('Y-m-d H:i:s', strtotime($edate));
		}
		if($event_id!='' && $start_time!=''){
			$status=$this->morganization->eventSchedule($event_id, $type, $event_start_date, $event_end_date, $user_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1429;
		}
		$this->buildResponse();
	}
	function eventSchedule_put(){
		$user_id=$this->_user['user_id'];
		$event_schedule_id=$this->put('event_schedule_id')?$this->alil_lib->decrypt_data($this->put('event_schedule_id')):"";
		$type=$this->put('type')?strtoupper($this->put('type')):"";
		$start_date=$this->put('from_date');
		$start_time=$this->put('from_time');
		$end_time=$this->put('to_time')?$this->put('to_time'):"";
		$event_edate='';
		$sdate=$start_date." ".$start_time;
		$sdate = str_replace('/', '-', $sdate);
		$event_start_date=date('Y-m-d H:i:s', strtotime($sdate));
		if($start_date){
			$edate=$start_date." ".$end_time;
			$edate = str_replace('/', '-', $edate);
			$event_end_date=date('Y-m-d H:i:s', strtotime($edate));
		}
		if($event_schedule_id!='' && $start_time!=''){
			$status=$this->morganization->updateEventSchedule($event_schedule_id, $type, $event_start_date, $event_end_date, $user_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1429;
		}
		$this->buildResponse();
	}
	function eventItem_post(){
		$user_id=$this->_user['user_id'];
		$event_id=$this->post('event_id')?$this->alil_lib->decrypt_data($this->post('event_id')):"";
		$items=$this->post('items');
		if($event_id!='' && !empty($items)){
			foreach($items as $key=>$item){
				$item_id=$this->alil_lib->decrypt_data($item);
				$status=$this->morganization->createEventItem($event_id, $item_id, $user_id);
			}
			if($status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['event_id']=$this->alil_lib->encrypt_data($event_id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1427;
		}
		$this->buildResponse();
	}
	function eventSkill_post(){
		$user_id=$this->_user['user_id'];
		$event_id=$this->post('event_id')?$this->alil_lib->decrypt_data($this->post('event_id')):"";
		$skills=$this->post("skills");
		if($event_id!='' && !empty($skills)){
			foreach($skills as $key=>$skill){
				$skill_id=$this->alil_lib->decrypt_data($skill);
				$status=$this->morganization->createEventSkill($event_id, $skill_id, $user_id);
			}
			if($status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['event_id']=$this->alil_lib->encrypt_data($event_id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1427;
		}
		$this->buildResponse();
	}
	function removeEventSkill_delete(){
		$user_id=$this->_user['user_id'];
		$event_skill_id=$this->delete('event_skill_id')?$this->alil_lib->decrypt_data($this->delete('event_skill_id')):"";
		if($event_skill_id && $user_id){
			$status=$this->morganization->removeEventSkill($event_skill_id, $user_id);
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
	function removeEventItems_delete(){
		$user_id=$this->_user['user_id'];
		$event_items_id=$this->delete('event_items_id')?$this->alil_lib->decrypt_data($this->delete('event_items_id')):"";
		if($event_items_id && $user_id){
			$status=$this->morganization->removeEventItems($event_items_id, $user_id);
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
}
?>