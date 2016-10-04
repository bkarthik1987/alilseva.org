<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/Alil_Rest.php';

class Event extends AlilRest {
	var $_response_data;
	public function __construct(){
		parent::__construct();
		$this->load->model('mevents');
	}
	public function _remap($method, $arguments = []){
		if($this->urlTokenizer($method))
			$method=$this->urlTokenizer($method);
		
		$uri_method= $method . '_' . $this->request->method;
		if(!in_array($method, array('location','listing'))){
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
	function location_get(){
		$this->load->model('mgeneral');
		if($this->get('state_id')){
			$state_id=$this->alil_lib->decrypt_data($this->get('state_id'));
			$location=$this->mgeneral->getLocalityByStateID($state_id);
			if(!empty($location)){
				$location=$this->alil_lib->setEncryptValue($location, array('id'), true);
			}else{
				$location=array();
			}
			$this->_response_data['location_list']=$location;
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['error_code']=1403;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function listing_get(){
		$data=array();
		if($this->get('location_id')){
			$location_id=$this->alil_lib->decrypt_data($this->get('location_id'));
			$data['location']=$location_id;
		}
		if($this->get('category_id')){
			$category_id=$this->alil_lib->decrypt_data($this->get('category_id'));
			$data['domain']=$category_id;
		}
		if($this->get('type')){
			$type=$this->get('type');
			if($type=='repeat'){
				$event_type='REPITIVE';
			}else{
				$event_type='ONETIME';
			}
			$data['type']=$event_type;
		}
		$events=$this->mevents->getEventsListBySourceID($data);
		if(!empty($events)){
			if(!empty($events)){
				foreach($events as $key=>$item){
					$events[$key]->event_base_url=$this->alil_lib->makeEventUrl($item, "info", "event");
				}
			}
			$events=$this->alil_lib->setEncryptValue($events, array('event_id'), true);
			$this->_response_data['event_list']=$events;
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['error_code']=1403;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function subscribe_post(){
		$user_id=$this->_user['user_id'];
		$event_id=$this->post('event_id')?$this->alil_lib->decrypt_data($this->post('event_id')):"";
		if($user_id!='' && $event_id!=''){
			$status=$this->mevents->subscribeEvent($user_id, $event_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1427;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function volunteers_get(){
		$event_id=$this->get('event_id')?$this->alil_lib->decrypt_data($this->get('event_id')):"";
		$volunteers=$this->mevents->eventVolunteers($event_id);
		if(!empty($volunteers)){
			$volunteers=$this->alil_lib->setEncryptValue($volunteers, array('id','event_id','user_id'), true);
			$this->_response_data['volunteers_list']=$volunteers;
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['error_code']=1403;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function createTask_post(){
		$task_start_date=$task_end_date="";
		$user_id=$this->_user['user_id'];
		$event_id=$this->alil_lib->decrypt_data($this->post('event_id'));
		$task_name=$this->post('task_name');
		$start_date=$this->post('start_date');
		if($start_date){
			$sdate=$start_date;
			$sdate = str_replace('/', '-', $sdate);
			$task_start_date=date('Y-m-d', strtotime($sdate));
		}
		$end_date=$this->post('end_date');
		if($end_date){
			$edate=$end_date;
			$edate = str_replace('/', '-', $edate);
			$task_end_date=date('Y-m-d', strtotime($edate));
		}
		$estimate_hours=$this->post('estimate_hours');
		$hour_spent=$this->post('hour_spent');
		if($this->_user['role']=='ORGANIZATION')
			$status="APPROVED";
		else
			$status="PENDING";
		$percentage=0;
		if($event_id!='' && $task_name!='' && $start_date!='' && $end_date!='' && $estimate_hours!=''){
			$status=$this->mevents->createEventTask($event_id, $task_name, $task_start_date, $task_end_date, $estimate_hours,
													$hour_spent,$status, $percentage, $user_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function createSubTask_post(){
		$task_start_date=$task_end_date="";
		$user_id=$this->_user['user_id'];
		$event_id=$this->alil_lib->decrypt_data($this->post('event_id'));
		$task_id=$this->alil_lib->decrypt_data($this->post('task_id'));
		$task_name=$this->post('task_name');
		$start_date=$this->post('start_date');
		if($start_date){
			$sdate=$start_date;
			$sdate = str_replace('/', '-', $sdate);
			$task_start_date=date('Y-m-d', strtotime($sdate));
		}
		$end_date=$this->post('end_date');
		if($end_date){
			$edate=$end_date;
			$edate = str_replace('/', '-', $edate);
			$task_end_date=date('Y-m-d', strtotime($edate));
		}
		$estimate_hours=$this->post('estimate_hours');
		$hour_spent=$this->post('hour_spent');
		if($this->_user['role']=='ORGANIZATION')
			$status="APPROVED";
		else
			$status="PENDING";
		$percentage=0;
		
		if($event_id!='' && $task_name!='' && $start_date!='' && $end_date!='' && $estimate_hours!=''){
			$event_task_id=$this->mevents->createEventSubTask($event_id, $task_id, $task_name, $task_start_date, $task_end_date, $estimate_hours,
														$hour_spent,$status, $percentage, $user_id);
			if($event_task_id){
				$status=$event_task_id;
				/*Event task Volunteers map start here*/
				$volunteer_maps=array();
				if($this->_user['role']=='ORGANIZATION'){
					$volunteers=$this->post('volunteers');
					if($volunteers && !empty($volunteers)){
						foreach($volunteers as $key=>$volunteer_id){
							$volunteer_maps[$key]['active']=1;
							$volunteer_maps[$key]['created_by']=$user_id;
							$volunteer_maps[$key]['creation_date']=date('Y-m-d H:i:s');
							$volunteer_maps[$key]['event_task_id']=$event_task_id;
							$volunteer_maps[$key]['volunteer_id']=$this->alil_lib->decrypt_data($volunteer_id);
						}
					}
				}else if($this->_user['role']=='VOLUNTEER'){
					if($this->post('assign_to_me') && $this->post('assign_to_me')=='Yes'){
						$volunteer_maps[0]['active']=1;
						$volunteer_maps[0]['created_by']=$user_id;
						$volunteer_maps[0]['creation_date']=date('Y-m-d H:i:s');
						$volunteer_maps[0]['event_task_id']=$event_task_id;
						$volunteer_maps[0]['volunteer_id']=$user_id;
					}
				}
				if(!empty($volunteer_maps))
					$status=$this->eventTaskVolunteerMap($volunteer_maps);
				/*Event task Volunteers map end here*/
				
				if($status)
					$this->_response_data['status']='SUCCESS';
				else{
					$this->_response_data['error_code']=1408;
					$this->_response_data['status']='FAILED';
				}
			}else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function eventVolunteerMap_post(){
		$status=false;
		$user_id=$this->_user['user_id'];
		$event_task_id=$this->alil_lib->decrypt_data($this->post('event_task_id'));
		if($this->_user['role']=='ORGANIZATION'){
			$volunteers=$this->post('volunteers');
			if($volunteers && !empty($volunteers)){
				foreach($volunteers as $key=>$volunteer_id){
					$volunteer_maps[$key]['active']=1;
					$volunteer_maps[$key]['created_by']=$user_id;
					$volunteer_maps[$key]['creation_date']=date('Y-m-d H:i:s');
					$volunteer_maps[$key]['event_task_id']=$event_task_id;
					$volunteer_maps[$key]['volunteer_id']=$this->alil_lib->decrypt_data($volunteer_id);
				}
				if(!empty($volunteer_maps))
					$status=$this->eventTaskVolunteerMap($volunteer_maps);
			}
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function eventTaskVolunteerMap($volunteer_maps){
		if(!empty($volunteer_maps)){
			$status=$this->mevents->createEventTaskVolunteerMap($volunteer_maps);
			if($status)
				return true;
			else{
				return false;
			}
		}else{
			return false;
		}
	}
	function approveTask_post(){
		$event_task_id=$this->post('event_task_id')?$this->alil_lib->decrypt_data($this->post('event_task_id')):"";
		$user_id=$this->_user['user_id'];
		if($user_id!='' && $event_task_id!=''){
			$status=$this->mevents->approveEventTask($event_task_id, $user_id);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function updateTask_put(){
		$task_start_date=$task_end_date="";
		$user_id=$this->_user['user_id'];
		$event_task_id=$this->alil_lib->decrypt_data($this->put('task_id'));
		$task_name=$this->put('task_name');
		$start_date=$this->put('start_date');
		if($start_date){
			$sdate=$start_date;
			$sdate = str_replace('/', '-', $sdate);
			$task_start_date=date('Y-m-d', strtotime($sdate));
		}
		$end_date=$this->put('end_date');
		if($end_date){
			$edate=$end_date;
			$edate = str_replace('/', '-', $edate);
			$task_end_date=date('Y-m-d', strtotime($edate));
		}
		$estimate_hours=$this->put('estimate_hours');
		$hour_spent=$this->put('hour_spent');
		$status=$this->put('status')?strtoupper($this->put('status')):null;
		$percentage=$this->put('percentage')?$this->put('percentage'):null;
		if($event_task_id!='' && $task_name!='' && $start_date!='' && $end_date!='' && $estimate_hours!=''){
			$status=$this->mevents->updateEventTask($event_task_id, $task_name, $task_start_date, $task_end_date, $estimate_hours,
													$hour_spent,$status, $percentage, $user_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['error_code']=1430;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function updateSubTask_put(){
		$task_start_date=$task_end_date="";
		$user_id=$this->_user['user_id'];
		$event_task_id=$this->alil_lib->decrypt_data($this->put('task_id'));
		$task_name=$this->put('task_name');
		$start_date=$this->put('start_date');
		if($start_date){
			$sdate=$start_date;
			$sdate = str_replace('/', '-', $sdate);
			$task_start_date=date('Y-m-d', strtotime($sdate));
		}
		$end_date=$this->put('end_date');
		if($end_date){
			$edate=$end_date;
			$edate = str_replace('/', '-', $edate);
			$task_end_date=date('Y-m-d', strtotime($edate));
		}
		$estimate_hours=$this->put('estimate_hours');
		$hour_spent=$this->put('hour_spent');
		$status=$this->put('status')?strtoupper($this->put('status')):null;
		$percentage=$this->put('percentage')?$this->put('percentage'):null;
		if($event_task_id!=''){
			$status=$this->mevents->updateEventTask($event_task_id, $task_name, $task_start_date, $task_end_date, 
													$estimate_hours, $hour_spent, $status, $percentage, $user_id);
			if($status){
				/*Event task Volunteers map start here*/
				$volunteer_maps=array();
				if($this->_user['role']=='ORGANIZATION'){
					$volunteers=$this->put('volunteers');
					if($volunteers && !empty($volunteers)){
						foreach($volunteers as $key=>$volunteer_id){
							$volunteer_maps[$key]['active']=1;
							$volunteer_maps[$key]['created_by']=$user_id;
							$volunteer_maps[$key]['creation_date']=date('Y-m-d H:i:s');
							$volunteer_maps[$key]['event_task_id']=$event_task_id;
							$volunteer_maps[$key]['volunteer_id']=$this->alil_lib->decrypt_data($volunteer_id);
						}
					}
				}else if($this->_user['role']=='VOLUNTEER'){
					if($this->put('assign_to_me') && $this->put('assign_to_me')=='Yes'){
						$volunteer_maps[0]['active']=1;
						$volunteer_maps[0]['created_by']=$user_id;
						$volunteer_maps[0]['creation_date']=date('Y-m-d H:i:s');
						$volunteer_maps[0]['event_task_id']=$event_task_id;
						$volunteer_maps[0]['volunteer_id']=$user_id;
					}else if($this->put('assign_to_me') && $this->put('assign_to_me')=='No'){
						$status=$this->mevents->volunteerSubscribe(array(), $event_task_id, $user_id, false);
					}
				}
				if(!empty($volunteer_maps))
					$status=$this->eventTaskVolunteerMap($volunteer_maps);
				/*Event task Volunteers map end here*/
				if($status)
					$this->_response_data['status']='SUCCESS';
				else{
					$this->_response_data['error_code']=1430;
					$this->_response_data['status']='FAILED';
				}
			}else{
				$this->_response_data['error_code']=1430;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function removeTask_delete(){
		$event_task_id=$this->delete('event_task_id')?$this->alil_lib->decrypt_data($this->delete('event_task_id')):"";
		$user_id=$this->_user['user_id'];
		if($user_id!='' && $event_task_id!=''){
			$status=$this->mevents->removeEventTask($event_task_id, $user_id, true);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function removeSubTask_delete(){
		$event_task_id=$this->delete('event_task_id')?$this->alil_lib->decrypt_data($this->delete('event_task_id')):"";
		$user_id=$this->_user['user_id'];
		if($user_id!='' && $event_task_id!=''){
			$status=$this->mevents->removeEventTask($event_task_id, $user_id);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function removeVolunteer_delete(){
		$volunteer_id=$this->delete('volunteer_id')?$this->alil_lib->decrypt_data($this->delete('volunteer_id')):"";
		$event_task_id=$this->delete('event_task_id')?$this->alil_lib->decrypt_data($this->delete('event_task_id')):"";
		$user_id=$this->_user['user_id'];
		if($user_id!='' && $event_task_id!='' && $volunteer_id!=''){
			$status=$this->mevents->removeVolunteer($event_task_id ,$volunteer_id, $user_id);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['error_code']=1408;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1407;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function subscribed_delete(){
		$user_id=$this->_user['user_id'];
		$event_task_id=$this->alil_lib->decrypt_data($this->delete('event_task_id'));
		if($event_task_id!=''){
			
			$volunteer_maps[0]['active']=1;
			$volunteer_maps[0]['created_by']=$user_id;
			$volunteer_maps[0]['creation_date']=date('Y-m-d H:i:s');
			$volunteer_maps[0]['event_task_id']=$event_task_id;
			$volunteer_maps[0]['volunteer_id']=$user_id;
			
			$status=$this->mevents->volunteerSubscribe($volunteer_maps, $event_task_id ,$user_id, true);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['error_code']=1430;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1403;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function unsubscribed_delete(){
		$user_id=$this->_user['user_id'];
		$event_task_id=$this->alil_lib->decrypt_data($this->delete('event_task_id'));
		if($event_task_id!=''){
			$status=$this->mevents->volunteerSubscribe(array(), $event_task_id, $user_id, false);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['error_code']=1430;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1403;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function sendMessage_post(){
		$user_id=$this->_user['user_id'];
		$content=$this->post('content');
		$event_id=$this->post('event_id')?$this->alil_lib->decrypt_data($this->post('event_id')):null;
		if($user_id && $content && $content!='' && $event_id){
			$event_detail=$this->mevents->getEventDetailByID($event_id);
			$user_info=$this->mgeneral->getUserInfo($user_id);
			if(!empty($event_detail)){
				$data['mail']['content']=$content;
				$data['mail']['user_name']=$event_detail->user_name;
				$data['mail']['volunteer']=$user_info->username;
				$msg=$this->load->view('mail-template/send-message',$data,true);
				$email_address=$event_detail->email_address;
				$subject=$event_detail->event_name." Events";
				$status=$this->alil_lib->sendEmailNotification($email_address, $subject, $msg);
				if($status){
					$this->_response_data['status']='SUCCESS';
				}else{
					$this->_response_data['error_code']=1438;
					$this->_response_data['status']='FAILED';
				}
			}else{
				$this->_response_data['error_code']=1437;
				$this->_response_data['status']='FAILED';
			}
		}else{
			$this->_response_data['error_code']=1436;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function uploadEventPhotos_post(){
		$this->load->model('morganization');
		$user_id=$this->_user['user_id'];
		$event_id=$this->alil_lib->decrypt_data($this->post('event_id'));
		$cover_photo=$this->post('cover_image');
		if($cover_photo=='Yes')
			$cover_photo=true;
		else
			$cover_photo=false;
		//Process File Upload
		if($user_id && $event_id && isset($_FILES['photos']) && $_FILES['photos']['name']!=''){
			$name=$_FILES['photos']['name'];
			$type=$_FILES['photos']['type'];
			$tmp_name= $_FILES['photos']['tmp_name'];
			$error=$_FILES['photos']['error'];
			$size=$_FILES['photos']['size'];
			$target_dir="uploads/1/".$event_id."/".$user_id;
			//Check and Create folder to upload the file
			if($error == UPLOAD_ERR_OK){
				if(verifyUploadDirectory($target_dir)){
					$tmp=explode(".",$name);
					$ext=array_pop($tmp);
					$file_name=md5($event_id."|".$user_id);
					$upload_path="./$target_dir/$file_name.$ext";
					
					$upload_status = $this->savePhotos($name, $type, $tmp_name, $error, $size, $file_name, $target_dir, $cover_photo);
					if($upload_status['status']=='S'){
						$return_status=$this->morganization->saveEventPhoto($event_id, $user_id, $upload_status['url'], $cover_photo, $ext);
					}
					if(isset($return_status['status']) && $return_status['status']){
						$this->_response_data['status']='SUCCESS';
						$this->_response_data['photo_url']=$upload_status['url'];
					}else{
						$this->_response_data['error_code']=1407;
						$this->_response_data['status']='FAILED';
					}
				}
			}		
		}else{
			$this->_response_data['error_code']=1439;
			$this->_response_data['status']='FAILED';
		}
		$this->buildResponse();
	}
	function removePhoto_delete(){
		$user_id=$this->_user['user_id'];
		$photo_id = $this->alil_lib->decrypt_data($this->delete('photo_id'));
		if($photo_id){
			if($this->mevents->removePhotos($photo_id, $user_id)){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']='1440';
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']='1403';
		}
		$this->buildResponse();
		
	}
	function savePhotos($name, $type, $tmp_name, $error, $size, $file_name, $source_path, $cover){
		$target_dir				=	$source_path."/l"; 
		$medium_target_dir		=	$source_path."/m";
		$thumb_target_dir		=	$source_path."/t";
		if($error == UPLOAD_ERR_OK){
			if(verifyUploadDirectory($target_dir)){
				$tmp=explode(".",$name);
				$ext=array_pop($tmp);
				$large_file="./$target_dir/$file_name.$ext";
				$thumb_file="./$thumb_target_dir/$file_name.$ext";
				$medium_file="./$medium_target_dir/$file_name.$ext";
				$i=1;
				while(file_exists($large_file)){
					$large_file="./$target_dir/$file_name-$i.$ext";
					$medium_file="./$medium_target_dir/$file_name-$i.$ext";
					$thumb_file="./$thumb_target_dir/$file_name-$i.$ext";
					++$i;
				}
				if(move_uploaded_file($tmp_name, $large_file)){
					if($cover){
						if(verifyUploadDirectory($medium_target_dir)){
							if (copy($large_file, $medium_file)) {
								$this->alil_lib->resizeImage($medium_file, 263, 126, ".".$ext);
							}
						}
					}
					if(verifyUploadDirectory($thumb_target_dir)){
						if (copy($large_file, $thumb_file)) {
							$this->alil_lib->resizeImage($thumb_file, 100,100, ".".$ext);
						}
					}
						
					$uploaded_url=substr($large_file,1);
					
					return array('status'=>'S','url'=>$uploaded_url,'ext'=> $ext);
				}
			}
		}
	
		return array('status'=>'E','url'=>'','ext'=> '');
	
	}
}
?>