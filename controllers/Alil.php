<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alil extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('mgeneral');
		$this->data['home_page']=false;
		$this->data['top_container_style1']=true;
		$this->data['title']='Alil';
		$this->data['top_container_style3']=false;
		$this->load->model('mgeneral');
		$this->data['states']=$this->alil_lib->setEncryptValue($this->mgeneral->getStates(), array('id'), true);
		$this->data['default_state']=1;
		$location=$this->mgeneral->getLocalityByStateID($this->data['default_state']);
		if(!empty($location)){
			$location=$this->alil_lib->setEncryptValue($location, array('id'), true);
		}else{
			$location=array();
		}
		$this->data['locations']=$location;
		
		$this->data['xtemplates'][] = 'templates/general-templates';
		$this->_reviews_pagination_count=1;
	}
	function urlTokenizer($method){
		$uri_string=$this->uri->uri_string();
		$total_segments=$this->uri->total_segments();
		$uri_data=true;
		$segments=$this->uri->segment_array();
		switch($total_segments){
			case 7:
				$uri_data=$this->uri->segment(7);
			break;
			case 6:
				$this->load->model('mevents');
				$uri_data=$this->uri->segment(5);
				$event_task_id=$this->alil_lib->decrypt_data($this->uri->segment(6));
				$task_detail=$this->mevents->getEventIndividualSubTask($event_task_id);
				if($task_detail->task_name=='' && $task_detail->id=='' && $task_detail->event_id=='' && $task_detail->main_task_id=='' && $task_detail->event_name==''){
					$task_detail=array();
				}
				$this->data['task_detail']=$task_detail;
			break;
			case 5:
				$uri_data=$this->uri->segment(4);
				$event_id=$this->alil_lib->decrypt_data($this->uri->segment(5));
				$this->load->model('mevents');
				$this->data['event_detail']=$this->mevents->getEventDetailByID($event_id);
				
				if(!empty($this->data['event_detail']))
					$this->data['event_detail']->event_base_url=$this->data['event_detail']->event_short_name."/".$this->alil_lib->encrypt_data($this->data['event_detail']->event_id);
				if($uri_data=='create-sub-task' || $uri_data=='update-task'){
					$task_id=$this->alil_lib->decrypt_data($this->input->get('task'));
					$this->data['task_detail']=$this->mevents->getEventIndividualTask($task_id);
				}else if($uri_data=='update-sub-task'){
					$task_id=$this->alil_lib->decrypt_data($this->input->get('task'));
					$this->data['task_detail']=$this->mevents->getEventIndividualSubTask($task_id);
				}
				$this->load->model('mrating');
				$this->load->model('mreview');
				if($this->session->userdata('active_user_loggedin'))
					$loggedin_user_id=$this->session->userdata('user_id')?$this->alil_lib->decrypt_data($this->session->userdata('user_id')):null;
				else
					$loggedin_user_id=null;
				
				$this->data['user_rate']=$this->mrating->getUserRating($event_id, $loggedin_user_id, 'EVENT');
				$this->data['already_reviewed']=$this->mreview->isAlreadyReviewed($loggedin_user_id, $event_id, "EVENT");
				$this->data['rating']=$this->mrating->getRating($event_id, "EVENT");
				$this->data['source_type']='event';
				$this->data['event_id']=$this->alil_lib->encrypt_data($event_id);
				$this->data['submit_to']=base_url('api/v1/user/rating');
				if($this->data['rating'])
					$this->data['rating_description']=$this->mrating->getRatingDescription($this->data['rating']->points);
				else
					$this->data['rating_description']="No Ratings";
				
				$this->data['reviews']=$this->mreview->getReviews($event_id, 'EVENT', 0, $this->_reviews_pagination_count);
		
				$this->data['reviews_count']=$this->mreview->getReviewsCount($event_id, 'EVENT');
				if($this->data['reviews_count']>$this->_reviews_pagination_count)
					$this->data['reviews_pagination']=true;
				else
					$this->data['reviews_pagination']=false;
				$this->data['source_id']=$this->alil_lib->encrypt_data($event_id);
				if(!empty($this->data['event_detail'])){
					$event_type=$this->data['event_detail']->event_type;
					$event_id=$this->data['event_detail']->event_id;
					$event_location=$this->data['event_detail']->location_id;
					$this->data['related_events']=$this->mevents->getReleatedEventsList($event_type, $event_location, $event_id, 4);
					$this->data['event_photos']=$this->mevents->getEventPhotos($event_id);
					if($uri_data=='edit'){
						$this->data['event_skills']=$this->mevents->getEventSkills($event_id);
						$this->data['event_items']=$this->mevents->getEventItems($event_id);
					}
				}				
			break;
			case 4:
				$uri_data=$this->uri->segment(4);
			break;	
			case 3:
				$uri_data=$this->uri->segment(3);
			break;	
			case 2:
				$uri_data=$method;
			break;	
			case 1:
				$single_segment_urls=array('change-password','volunteer-register','organization-register','signup','contact-us','about-us','events','create-events','attestation','profile','users');
				if(!in_array($method,$single_segment_urls))
					$uri_data='';
				else
					$uri_data=$method;
			break;
			case 0:
				$uri_data=true;
			break;
			default:
				$uri_data='';
		}
		
		if($uri_data==''){
			$this->data['uri_data']=false;
			$data=$this->data;
			$data['title']="Page not Found";
			$data['content']=$this->load->view("static/404",$data,true);
			$this->load->view('main',$data);
			http_response_code(404);
			return true;
		}
		else{
			$this->data['uri_data']=$uri_data;
		}
	}
	public function _remap($method){
		if($this->urlTokenizer($method))
			return false;
		switch($method){
			case 'break_down_create':
				$this->createTask();
			break;
			case 'change-password':
				if(!$this->session->userdata('active_user_loggedin')){
					redirect(base_url());
				}	
				$data=$this->data;
				$data['title']='Change Password';
				$data['meta_description']='Change Password';
				$data['meta_keywords']='Change Password';
				$data['content']=$this->load->view('user/change-password',$data,true);
				$this->load->view('main',$data);
			break;
			case 'edit_event':
				$this->editEvent();
			break;
			case 'volunteer-register':
				$this->volunteeRegister();
			break;
			case 'organization-register':
				$this->organizationRegister();
			break;
			case 'create-events':
				$this->createEvents();
			break;
			case 'create_task':
				$this->createTask();
			break;
			case 'events':
				$this->listing();
			break;
			case 'event_detail':
				$this->data['top_container_style1']=false;
				$this->eventDetails();
			break;
			case 'attestation':
				$this->attestation();
			break;
			case 'users':
				$this->Volunteers();
			break;
			case 'profile':
				$this->profile();
			break;
			case 'about-us':
				$data=$this->data;
				$data['title']='About Us';
				$data['meta_description']='About Us';
				$data['meta_keywords']='About Us';
				$data['content']=$this->load->view('static/about-us',$data,true);
				$this->load->view('main',$data);
			break;
			case 'contact-us':
				$data=$this->data;
				$data['title']='Contact US';
				$data['meta_description']='Contact US';
				$data['meta_keywords']='Contact US';
				$data['content']=$this->load->view('static/contact-us',$data,true);
				$this->load->view('main',$data);
			break;
			default:
				$data=$this->data;
				if(method_exists($this,$method)){
					call_user_func(array($this, $method));
					return;
				}else{
					$this->load->model('mevents');
					$events=$this->mevents->getEventsList('ongoing', false, "ORGANIZATION", 4);
					$data['events']=$events;
					$data['title']=$this->data['title'];
					$data['meta_description']='Alil';
					$data['meta_keywords']='Alil';
					$data['home_page']=true;
					$data['content']=$this->load->view('home',$data,true);
					$this->load->view('main',$data);
				}
			break;
		}
	}
	public function Volunteers(){
		$data=$this->data;
		$data['title']='Volunteers';
		$data['meta_description']='Volunteers';
		$data['meta_keywords']='Volunteers';
		$this->load->model('mvolunteer');
		$volunteers=$this->mvolunteer->getVolunteerList();
		$data['volunteers']=$volunteers;
		$data['content']=$this->load->view('user/volunteers-listing',$data,true);
		$this->load->view('main',$data);
	}
	public function profile(){
		$this->alil_lib->verifyUserLogin(false);
		$data=$this->data;
		$user_id=$this->alil_lib->decrypt_data($this->session->userdata('user_id'));
		if(!$user_id || $user_id==''){
			redirect(base_url());
		}
		$data['top_container_style3']=true;
		$data['title']='Profile';
		$data['meta_description']='Profile';
		$data['meta_keywords']='Profile';
		
		$this->load->model('mgeneral');
		$this->load->model('mvolunteer');
		$user_detail=$this->mgeneral->getUserInfo($user_id);
		if($user_detail->location_id!=''){
			$user_detail->location_id=$this->alil_lib->encrypt_data($user_detail->location_id);
		}
		$data['user_detail']=$user_detail;
		
		if($this->session->userdata('role')=='ORGANIZATION'){
			$this->load->model('morganization');
			$user_organization=$this->morganization->getUserOrganization($user_id);
			$data['user_organization']=$user_organization;
			$locality=$this->alil_lib->setEncryptValue($this->mgeneral->getLocality(), array('id'), true);
			$data['locality']=json_encode($locality);
			$data['content']=$this->load->view('user/organization-profile',$data,true);
			
		}else{
			$skills=$this->alil_lib->setEncryptValue($this->mgeneral->getSkills(), array('id'), true);
			$data['skills']=json_encode($skills);
			$services=$this->alil_lib->setEncryptValue($this->mgeneral->getServices(), array('id'), true);
			$data['services']=json_encode($services);
			$locality=$this->alil_lib->setEncryptValue($this->mgeneral->getLocality(), array('id'), true);
			$data['locality']=json_encode($locality);
			$activites=$this->alil_lib->setEncryptValue($this->mgeneral->getActivites(), array('id'), true);
			$data['activites']=json_encode($activites);
			$preference=$this->mvolunteer->getVolunteerPreference($user_id);
			if(!empty($preference)){
				$preference->id=$this->alil_lib->encrypt_data($preference->id);
				$preference->user_id=$this->alil_lib->encrypt_data($preference->user_id);
			}
			$data['availabilty']=$this->config->item('availabilty');
			$data['location_preference']=$this->config->item('location_preference');
			$data['preference_detail']=$preference;
			$activity_preference=$this->mvolunteer->getvolunteerActivityPreference($user_id);
			$data['activity_preference']=$activity_preference;
			$user_skills=$this->mvolunteer->getVolunteerSkills($user_id);
			$user_skills_list=array();
			if(!empty($user_skills)){
				$user_skills=$this->alil_lib->setEncryptValue($user_skills, array('id','user_id','skill_id'), true);
				foreach($user_skills as $skill){
					$user_skills_list[]=$skill->skill_name;
				}				
				if(!empty($user_skills_list))
					$data['user_skills_list']=implode(", ",$user_skills_list);
			}
			$data['user_skills']=$user_skills;
			$user_services=$this->mvolunteer->getVolunteerServices($user_id);
			$user_services_list=array();
			if(!empty($user_services)){
				$user_services=$this->alil_lib->setEncryptValue($user_services, array('id','user_id','service_id'), true);
				foreach($user_services as $user_service){
					$user_services_list[]=$user_service->service_name;
				}				
				if(!empty($user_services_list))
					$data['user_services_list']=implode(", ",$user_services_list);
			}
			$data['user_services']=$user_services;
			$data['content']=$this->load->view('user/profile',$data,true);
		}
		
		
		$this->load->view('main',$data);
	}
	public function createTask(){
		$this->alil_lib->verifyUserLogin(false);
		$event_detail=$this->data['event_detail'];
		if(empty($event_detail)){
			redirect(base_url());
		}
		$type=$this->uri->segment(5);
		$data=$this->data;
		if($data['uri_data']=='create-sub-task' && isset($data['main_task']) && !empty($data['main_task']))
			$data['title']='Create '.$data['main_task']->task_name.' Sub Task';
		else
			$data['title']='Create Task';
		$data['meta_description']='Create Task';
		$data['meta_keywords']='Create Task';
		$uri_data=$this->data['uri_data'];
		if($data['uri_data']=='create-sub-task'){
			$volunteers=$this->mevents->eventVolunteers($event_detail->event_id);
			$data['content']=$this->load->view('event/create-sub-task',$data,true);
		}else{
			$data['content']=$this->load->view('event/create-task',$data,true);
		}
		$this->load->view('main',$data);
	}
	public function updateTask(){
		$this->alil_lib->verifyUserLogin(false);
		$event_detail=$this->data['event_detail'];
		if(empty($event_detail)){
			redirect(base_url());
		}
		$type=$this->uri->segment(5);
		$data=$this->data;
		if($data['uri_data']=='create-sub-task' && isset($data['main_task']) && !empty($data['main_task']))
			$data['title']='Create '.$data['main_task']->task_name.' Sub Task';
		else
			$data['title']='Update Task';
		
		$data['meta_description']='Create Task';
		$data['meta_keywords']='Create Task';
		$uri_data=$this->data['uri_data'];
		
		$data['permissoin_access']=false;
		if($data['uri_data']=='update-sub-task'){
			$volunteers=$this->mevents->eventVolunteers($event_detail->event_id);
			if($this->session->userdata('role')==='ORGANIZATION'){
				$data['permissoin_access']=true;
			}else{
				if(isset($data['task_detail']->volunteer_ids)){
					$volunteer_ids=explode(",",$data['task_detail']->volunteer_ids);
					if(in_array($this->alil_lib->decrypt_data($this->session->userdata('user_id')),$volunteer_ids)){
						$data['permissoin_access']=true;
					}
				}
			}
			$data['content']=$this->load->view('event/edit-sub-task',$data,true);
		}else{
			$data['content']=$this->load->view('event/edit-task',$data,true);
		}
		
		$this->load->view('main',$data);
	}
	public function editEvent(){
		$this->alil_lib->verifyUserLogin(false);
		$event_detail=$this->data['event_detail'];
		if(empty($event_detail)){
			$this->data['uri_data']=false;
			$data=$this->data;
			$data['title']="Page not Found";
			$data['content']=$this->load->view("static/404",$data,true);
			$this->load->view('main',$data);
			http_response_code(404);
			return true;
		}
		$type=$this->uri->segment(5);
		$data=$this->data;
		//$data['top_container_style1']=false;
		$data['title']='Update Event';
		$data['meta_description']='Update Event';
		$data['meta_keywords']='Update Event';
		$uri_data=$this->data['uri_data'];
		$data['event_detail']=$event_detail;
		
		$locality=$this->alil_lib->setEncryptValue($this->mgeneral->getLocality(), array('id'), true);
		$data['locality']=json_encode($locality);
		$skills=$this->alil_lib->setEncryptValue($this->mgeneral->getSkills(), array('id'), true);
		$data['skills']=json_encode($skills);
		$items=$this->alil_lib->setEncryptValue($this->mgeneral->getItems(), array('id'), true);
		$data['items']=json_encode($items);
		$event_domain=$this->mgeneral->getEventDomain();
		$data['event_domain']=$event_domain;
		
		
		$data['content']=$this->load->view('event/edit-event',$data,true);
		$this->load->view('main',$data);
	}
	public function attestation(){
		if($this->session->userdata('active_user_loggedin') && $this->session->userdata('role')!='ORGANIZATION'){
			redirect(base_url());
		}else if(!$this->session->userdata('active_user_loggedin')){
			redirect(base_url());
		}
		if($this->session->userdata('role')==='ORGANIZATION' && $this->session->userdata('status')==='VERIFIED'){
			redirect(base_url());
		}
		$data=$this->data;
		$data['title']='Attestation';
		$data['meta_description']='Attestation';
		$data['meta_keywords']='Attestation';
		$uri_data=$this->data['uri_data'];
		$attestation=$this->mgeneral->getAttestation();
		$data['attestation']=$attestation;
		$data['content']=$this->load->view('attestation',$data,true);
		$this->load->view('main',$data);
	}
	public function createEvents(){
		$this->alil_lib->verifyUserLogin(false);
		if($this->session->userdata('role')==='ORGANIZATION' && $this->session->userdata('status')==='VERIFIED'){
			redirect(base_url());
		}
		$data=$this->data;
		$data['title']='Create New Events';
		$data['meta_description']='Create New Events';
		$data['meta_keywords']='Create New Events';
		$this->load->model('mgeneral');
		$locality=$this->alil_lib->setEncryptValue($this->mgeneral->getLocality(), array('id'), true);
		$data['locality']=json_encode($locality);
		$skills=$this->alil_lib->setEncryptValue($this->mgeneral->getSkills(), array('id'), true);
		$data['skills']=json_encode($skills);
		$items=$this->alil_lib->setEncryptValue($this->mgeneral->getItems(), array('id'), true);
		$data['items']=json_encode($items);
		$event_domain=$this->mgeneral->getEventDomain();
		$data['event_domain']=$event_domain;
		
		$uri_data=$this->data['uri_data'];
		$data['xtemplates'][] = 'templates/create-event-templates';
		$data['content']=$this->load->view('event/create-events',$data,true);
		$this->load->view('main',$data);
	}
	
	public function eventDetails(){
		$event_detail=$this->data['event_detail'];
		if(empty($event_detail)){
			$this->data['uri_data']=false;
			$data=$this->data;
			$data['title']="Page not Found";
			$data['content']=$this->load->view("static/404",$data,true);
			$this->load->view('main',$data);
			http_response_code(404);
			return true;
		}
		$data=$this->data;
		$data['organization_permission']=false;
		$data['authorization_permission']=false;
		$data['event_subscribed']=false;
		$data['title']='BRIGADE SHOWCASE 2016';
		$data['meta_description']='Event Details';
		$data['meta_keywords']='Event Details';
		$uri_data=$this->data['uri_data'];
		$data['event_detail']=$event_detail;
		$event_id=$event_detail->event_id;
		if($this->session->userdata('active_user_loggedin')){
			$user_id=$this->alil_lib->decrypt_data($this->session->userdata('user_id'));
			$event_subscribe=$this->mevents->checkEventSubscribe($user_id, $event_id);
			if(!empty($event_subscribe)){
				$data['event_subscribed']=true;
			}
			if($this->session->userdata('role')=='ORGANIZATION'){
				$event_organization_user_id=$event_detail->user_id;
				if($event_organization_user_id==$user_id){
					$data['authorization_permission']=true;
					$data['organization_permission']=true;
				}
			}
			$volunteers=$this->mevents->eventVolunteers($event_id);
			if(!empty($volunteers)){
				$volunteer_ids=array_map(function($item){ return $item->user_id;}, $volunteers);
				if(in_array($user_id,$volunteer_ids))
					$data['authorization_permission']=true;
			}
		}
		if($uri_data==='volunteers'){
			$volunteers=$this->mevents->eventVolunteers($event_id);
			$data['volunteers']=$volunteers;
		}else if($uri_data==='break-down'){
			$event_task=$this->mevents->getEventTaskList($event_id);
			$task_list=array();
			if(!empty($event_task)){
				foreach($event_task as $item){
					if($item->main_task_id==0){
						$task_list[$item->id]['main_task']=$item;
					}else{
						$task_list[$item->main_task_id]['sub_task'][]=$item;
					}
				}
			}
			$data['task_list']=$task_list;
		}
		$data['content']=$this->load->view('event/detail-'.$uri_data,$data,true);
		$this->load->view('main',$data);
	}
	public function breakDownDetails(){
		$task_detail=$this->data['task_detail'];
		if(empty($task_detail)){
			$this->data['uri_data']=false;
			$data=$this->data;
			$data['title']="Page not Found";
			$data['content']=$this->load->view("static/404",$data,true);
			$this->load->view('main',$data);
			http_response_code(404);
			return true;
		}
		$this->data['top_container_style1']=false;
		$data=$this->data;
		$data['organization_permission']=false;
		$data['authorization_permission']=false;
		$data['event_subscribed']=false;
		$data['title']='BRIGADE SHOWCASE 2016';
		$data['meta_description']='Event Details';
		$data['meta_keywords']='Event Details';
		$data['permissoin_access']=false;
		$volunteers=$this->mevents->eventVolunteers($task_detail->event_id);
		$uri_data=$this->data['uri_data'];
		if($this->session->userdata('role')==='ORGANIZATION'){
			$data['permissoin_access']=true;
		}else{
			if(isset($data['task_detail']->volunteer_ids)){
				$volunteer_ids=explode(",",$data['task_detail']->volunteer_ids);
				if(in_array($this->alil_lib->decrypt_data($this->session->userdata('user_id')),$volunteer_ids)){
					$data['permissoin_access']=true;
				}
			}
		}
		if($this->session->userdata('active_user_loggedin')){
			$user_id=$this->alil_lib->decrypt_data($this->session->userdata('user_id'));
			if($this->session->userdata('role')=='ORGANIZATION'){
				$event_organization_user_id=$task_detail->event_user_id;
				if($event_organization_user_id==$user_id){
					$data['authorization_permission']=true;
					$data['organization_permission']=true;
				}
			}
		}
		if($uri_data==='incharge'){
			$volunteers=$this->mevents->taskVolunteers($task_detail->id);
			$data['volunteers']=$volunteers;
		}
		$data['content']=$this->load->view('event/break-down-detail-'.$uri_data,$data,true);
		$this->load->view('main',$data);
	}
	public function listing(){
		$this->load->model('mevents');
		$data=$this->data;
		$data['title']='Event List';
		$data['meta_description']='Event List';
		$data['meta_keywords']='Event List';
		$data['event_domain']=$this->alil_lib->setEncryptValue($this->mgeneral->getEventDomain(), array('id'), true);
		$events=$this->mevents->getEventsList();
		$data['events']=$events;
		
		$data['content']=$this->load->view('event/listing',$data,true);
		$this->load->view('main',$data);
	}
	public function signup(){
		if($this->session->userdata('active_user_loggedin')){
			redirect(base_url());
		}
		$data=$this->data;
		$data['top_container_style3']=true;
		$data['title']='Signup';
		$data['content']=$this->load->view('signup',$data,true);
		$this->load->view('main',$data);
	}
	public function volunteeRegister(){
		/*if($this->session->userdata('active_user_loggedin')){
			redirect(base_url());
		}	*/
		$this->load->model('mgeneral');
		$user_data=$this->session->userdata('volunteer_register');
		$data=$this->data;
		$data['top_container_style3']=true;
		$skills=$this->alil_lib->setEncryptValue($this->mgeneral->getSkills(), array('id'), true);
		$data['skills']=json_encode($skills);
		$services=$this->alil_lib->setEncryptValue($this->mgeneral->getServices(), array('id'), true);
		$data['services']=json_encode($services);
		$locality=$this->alil_lib->setEncryptValue($this->mgeneral->getLocality(), array('id'), true);
		$data['locality']=json_encode($locality);
		$activites=$this->alil_lib->setEncryptValue($this->mgeneral->getActivites(), array('id'), true);
		$data['activites']=json_encode($activites);
		
		$data['xtemplates'][] = 'templates/volunteer-register-templates';
		$data['title']='Volunteer Register';
		$data['content']=$this->load->view('user/volunteer-register',$data,true);
		$this->load->view('main',$data);
	}
	public function organizationRegister(){
		$this->alil_lib->verifyUserLogin(true);
		$data=$this->data;
		$data['top_container_style3']=true;
		$data['title']='Organization Registration';
		$this->load->model('mgeneral');
		$locality=$this->alil_lib->setEncryptValue($this->mgeneral->getLocality(), array('id'), true);
		$data['locality']=json_encode($locality);
		$data['content']=$this->load->view('user/organization-register',$data,true);
		$this->load->view('main',$data);
	}
}
?>