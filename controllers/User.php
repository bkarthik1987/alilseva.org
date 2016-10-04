<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->data['home_page']=false;
		$this->data['top_container_style1']=true;
		$this->data['title']='Alil';
		$this->data['top_container_style3']=false;
		$this->load->model('mgeneral');
		$this->_reviews_pagination_count=1;
	}
	function urlTokenizer($method){
		$uri_string=$this->uri->uri_string();
		$total_segments=$this->uri->total_segments();
		$uri_data=true;
		$segments=$this->uri->segment_array();
		switch($total_segments){
			case 3:
				$uri_data=$method;
			break;
			case 2:
				$uri_data=$method;
			break;	
			case 1:
				$single_segment_urls=array('dashboard');
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
			case 'confirm':
				$this->verifyActivationCode();
			break;
			case 'detail':
				$this->detail();
			break;
			default:
				$data=$this->data;
				if(method_exists($this,$method)){
					call_user_func(array($this, $method));
					return;
				}
			break;
		}	
		
	}
	public function verifyActivationCode(){
		$data=$this->data;
		$data['title']="Verify Code Activation";
		$user_id=$this->input->post('user_id')?$this->input->post('user_id'):$this->uri->segment(2);
		$code=$this->input->post('vcode')?$this->input->post('vcode'):$this->uri->segment(3);
		$status=$this->mgeneral->verifyActivationCode($this->alil_lib->decrypt_data($user_id), $this->alil_lib->decrypt_data($code));
		if($status===true){
			$this->session->set_flashdata('verification_msg', 'You account have been activated successfully!.');
			$this->session->set_flashdata('show_success_login_popup',true);
		}else if($status=='already'){
			$this->session->set_flashdata('show_success_login_popup',true);
			$this->session->set_flashdata('verification_msg', 'Your account has been already activated.');
		}else{
			$this->session->set_flashdata('show_verfy_popup',true);
			$this->session->set_flashdata('user_id',$user_id);
			$this->session->set_flashdata('verification_msg', 'Invalid Verification Code');
		}
		redirect(base_url());
	}
	public function detail(){
		$uri_data=$this->data['uri_data'];
		$user_handle=$this->uri->segment(2);
		$user_detail=$this->mgeneral->getUserDetailByhandles($user_handle);
		if(empty($user_detail)){
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
		$this->load->model('mrating');
		$this->load->model('mreview');
		$this->load->model('mevents');
		$this->load->model('mvolunteer');
		$data['title']='John';
		$data['meta_description']='John';
		$data['meta_keywords']='John';
		
		if(!$this->uri->segment(2) && !empty($user_detail))
			redirect(base_url);
		
		$loggedin_user_id=$this->session->userdata('user_id')?$this->alil_lib->decrypt_data($this->session->userdata('user_id')):null;
		$data['user_detail']=$user_detail;
		$data['source_type']='user';
		$user_id=$this->alil_lib->encrypt_data($user_detail->id);
		$data['user_id']=$user_id;
		$data['source_id']=$user_id;
		$data['source_type']='user';
		$data['submit_to']=base_url('api/v1/user/rating');
		$data['user_rate']=$this->mrating->getUserRating($user_detail->id, $loggedin_user_id, 'USER');
		$data['already_reviewed']=$this->mreview->isAlreadyReviewed($loggedin_user_id, $user_detail->id, "USER");
		$data['rating']=$this->mrating->getRating($user_detail->id, "USER");
		if($data['rating'])
		  $data['rating_description']=$this->mrating->getRatingDescription($data['rating']->points);
		else
		  $data['rating_description']="No Ratings";
		
		$data['reviews']=$this->mreview->getReviews($user_detail->id, 'USER', 0, $this->_reviews_pagination_count);
		$data['reviews_count']=$this->mreview->getReviewsCount($user_detail->id, 'USER');
		
		if($data['reviews_count']>$this->_reviews_pagination_count)
			$data['reviews_pagination']=true;
		else
			$data['reviews_pagination']=false;
		
		$current_events=$this->mevents->getMyVolunteerDashboard('ongoing', $this->alil_lib->decrypt_data($user_id), "VOLUNTEER");
		$data['current_events']=$current_events;
		/*User information*/
		$preference=$this->mvolunteer->getVolunteerPreference($user_detail->id);
		if(!empty($preference)){
			$preference->id=$this->alil_lib->encrypt_data($preference->id);
			$preference->user_id=$this->alil_lib->encrypt_data($preference->user_id);
		}
		$data['availabilty']=$this->config->item('availabilty');
		$data['location_preference']=$this->config->item('location_preference');
		$data['preference_detail']=$preference;
		
		$activity_preference=$this->mvolunteer->getvolunteerActivityPreference($user_detail->id);
		$data['activity_preference']=$activity_preference;
		$user_skills=$this->mvolunteer->getVolunteerSkills($user_detail->id);
		$user_skills_list=array();
		$data['user_skills_list']=array();
		$data['user_services_list']=array();
		if(!empty($user_skills)){
			$user_skills=$this->alil_lib->setEncryptValue($user_skills, array('id','user_id','skill_id'), true);
			foreach($user_skills as $skill){
				$user_skills_list[]=$skill->skill_name;
			}				
			if(!empty($user_skills_list))
				$data['user_skills_list']=implode(", ",$user_skills_list);
		}
		$user_services=$this->mvolunteer->getVolunteerServices($user_detail->id);
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
		/*User information*/
		$data['xtemplates'][] = 'templates/general-templates';
		$data['content']=$this->load->view('user/detail-info-new',$data,true);
		$this->load->view('main',$data);
	}
	public function dashboard(){
		$this->alil_lib->verifyUserLogin(false);
		$this->load->model('mevents');
		$data=$this->data;
		$data['title']='Dashboard';
		$data['meta_description']='Dashboard';
		$data['meta_keywords']='Dashboard';
		$uri_data=$this->data['uri_data'];
		$user_id=$this->alil_lib->decrypt_data($this->session->userdata('user_id'));
		if($this->uri->segment(3) && $this->uri->segment(3)==='volunteer'){
			$skills_events=$this->mevents->getMyVolunteerDashboard('skills', $user_id, "VOLUNTEER");
			$data['skills_events']=$skills_events;
			
			$current_events=$this->mevents->getMyVolunteerDashboard('ongoing', $user_id, "VOLUNTEER");
			$data['current_events']=$current_events;
			$completed_events=$this->mevents->getMyVolunteerDashboard('completed', $user_id, "ORGANIZATION");
			$data['completed_events']=$completed_events;
			
			$friends_events=$this->mevents->getMyVolunteerDashboard('locality', $user_id, "VOLUNTEER");
			$data['friends_events']=$friends_events;
			$data['content']=$this->load->view('user/volunteer-dashboard',$data,true);
		}else{
			$current_events=$this->mevents->getEventsList('ongoing', $user_id, "ORGANIZATION");
			$data['current_events']=$current_events;
			$completed_events=$this->mevents->getEventsList('completed', $user_id, "ORGANIZATION");
			$data['completed_events']=$completed_events;
			$data['content']=$this->load->view('user/organization-dashboard',$data,true);
		}
		$this->load->view('main',$data);
	}
}
?>