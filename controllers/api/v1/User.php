<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/Alil_Rest.php';

class User extends AlilRest {
	var $_response_data;
	public function __construct(){
		parent::__construct();
		$this->load->model('mgeneral');
		$this->_reviews_pagination_count=1;
	}
	public function _remap($method, $arguments = []){
		if($this->urlTokenizer($method))
			$method=$this->urlTokenizer($method);
		
		$uri_method= $method . '_' . $this->request->method;
		if(!in_array($method, array('attestation','loadReviews','checkEmail','resendVerificationCode','forgotPassword','logout','login','verifyCode'))){
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
	public function login_post(){
		$username = $this->post('username');
		$password = $this->post('password');
		if($username!='' && $password!=''){
			$rows=$this->mgeneral->verfyUser($username, $password);
			if(!empty($rows) && isset($rows->status) && $rows->status=='ACTIVE'){
				$token_id=$this->loginHook($rows);
				if($token_id){
					$this->_response_data['status']='SUCCESS';
					$this->_response_data['access_token']=$token_id;
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1407;
				}
			}else if(isset($rows->status) && $rows->status=='VERIFIED' && $rows->role=='ORGANIZATION'){
				$token_id=$this->loginHook($rows);
				if($token_id){
					$this->_response_data['status']='SUCCESS';
					$this->_response_data['access_token']=$token_id;
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1407;
				}
				/*$this->_response_data['status']='WARNING';
				$this->_response_data['error_code']=1510;*/
			}else if(isset($rows->status) && $rows->status=='NEW'){
				$this->_response_data['status']='WARNING';
				$this->_response_data['error_code']=1414;
				$this->_response_data['user_id']=$this->alil_lib->encrypt_data($rows->id);
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1404;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	public function loginHook($user){
		$user_id = $user->id;
		$user_name = $user->username;
		$role = $user->role;
		if(($role=='ORGANIZATION' && ($user->status=='ACTIVE' || $user->status=='VERIFIED')) || ($role!='ORGANIZATION' && $user->status=='ACTIVE')){
			$access_token=$this->alil_lib->encrypt_data($user_id."|".$user_name."|".$role);
			$user_data=array('user_name'=>$user_name,'role'=>$role);
			$token_id=$this->mgeneral->sessoinRegenerate($access_token, $user_data);
			if($token_id){
				$user=$this->mgeneral->getUserInfo($user_id);
				$this->session->set_userdata('active_user_loggedin',true);
				$this->session->set_userdata('role',$role);
				$this->session->set_userdata('username',$user->username);
				$this->session->set_userdata('status',$user->status);
				$this->session->set_userdata('user_id',$this->alil_lib->encrypt_data($user_id));
				return $token_id;
			}else{
				return false;
			}
		}else if($role=='ORGANIZATION' && $user->status=='VERIFIED'){
			return 'wating_attestation';
		}else{
			return false;
		}
	}
	public function verifyCode_post(){
		$user_id=$this->alil_lib->decrypt_data($this->post('user_id'));
		$code=$this->post('vcode');
		$user=$this->mgeneral->getUserInfo($user_id);
		if($code!='' && $user_id){
			if($user->role=='ORGANIZATION'){
				$status=$this->mgeneral->verifyActivationCode($user_id, $code, true);
				if($status)
					$user->status='VERIFIED';
			}else{
				$status=$this->mgeneral->verifyActivationCode($user_id, $code);
				$user->status='ACTIVE';
			}
			if($status===true){
				$token_id=$this->loginHook($user);
				if($token_id=='wating_attestation'){
					$this->_response_data['status']='WARNING';
					$this->_response_data['error_code']=1510;
				}else if($token_id){
					$this->_response_data['status']='SUCCESS';
					$this->_response_data['access_token']=$token_id;
					$this->_response_data['user_id']=$this->alil_lib->encrypt_data($user_id);
					$this->_response_data['error_code']=1416;
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1407;
				}
			}else if($status=='already'){
				$this->_response_data['status']='WARNING';
				$this->_response_data['error_code']=1415;
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1418;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1418;
		}
		$this->buildResponse();
	}
	public function logout_get(){
		$status=false;
		if($this->head('Authorization')){
			$status=$this->mgeneral->deactiveSessoin($this->head('Authorization'));
		}
		if($status){
			$this->session->sess_destroy();
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		
		$this->buildResponse();
	}
	public function forgotPassword_post(){
		$status=false;
		$email_address=$this->post("email");
		$user=$this->mgeneral->checkEmailAddress($email_address);
		if(!empty($user)){
			$user_id= $user->id;
			$user_name= $user->username;
			$status=$this->mgeneral->passwordRegenerate($user_id, $user_name, $email_address);
			if($status){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['error_code']=1423;
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1422;
		}
		$this->buildResponse();
	}
	public function resendVerificationCode_post(){
		$status=false;
		$user_id=$this->alil_lib->decrypt_data($this->post("user_id"));
		$user=$this->mgeneral->getUserInfo($user_id);
		if($user_id!='' && !empty($user)){
			$status=$this->mgeneral->resendVerificationCode($user_id, $user->emailid);
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
	function updatePassword_put(){
		$current_password=$this->put("current_password");
		$new_password=$this->put("new_password");
		$confirm_password=$this->put("confirm_password");
		$user_id=$this->_user['user_id'];
		if($user_id){
			if($current_password!=''){
				if($this->mgeneral->verfyOldPassword($user_id, $current_password)){
					if($current_password==$new_password){
						$this->_response_data['status']='FAILED';
						$this->_response_data['error_code']=1426;
					}else if($new_password!='' && $confirm_password!='' && $new_password==$confirm_password){
						$status=$this->mgeneral->updatePassword($user_id, $new_password);
						if($status){
							$this->_response_data['status']='SUCCESS';
						}else{
							$this->_response_data['status']='FAILED';
							$this->_response_data['error_code']=1408;
						}
					}else{
						$this->_response_data['status']='FAILED';
						$this->_response_data['error_code']=1425;
					}
				}else{
					$this->_response_data['status']='FAILED';
					$this->_response_data['error_code']=1424;
				}
				
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1424;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function checkEmail_get(){
		$email_address=$this->get('primary_email_address')?$this->get('primary_email_address'):$this->get('email_address');
		if($email_address){
			$status=$this->mgeneral->checkEmailAddress($email_address);
			if(!empty($status)){
				echo 'false';
			}else{
				echo 'true';
			}
		}else{
			echo 'true';
		}
	}
	function userDetail_get(){
		$user_id=$this->_user['user_id'];
		if($user_id){
			$user_info=$this->mgeneral->getUserInfo($user_id);
			if(!empty($user_info)){
				$user_info->id=$this->alil_lib->encrypt_data($user_info->id);
				$user_info->contact_id=$this->alil_lib->encrypt_data($user_info->contact_id);
				if($user_info->location_id!=''){
					$user_info->location_id=$this->alil_lib->encrypt_data($user_info->location_id);
				}
			}
			$this->_response_data['status']='SUCCESS';
			$this->_response_data['user']=$user_info;
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function updateUser_post(){
		$this->load->model('mgeneral');
		$this->load->model('mvolunteer');
		$user_id=$this->_user['user_id'];
		$general_info=$this->post();
		$contact_id=$this->post('contact_id')?$this->alil_lib->decrypt_data($this->post('contact_id')):"";
		if($general_info && !empty($general_info) && $contact_id){
			$status=$this->mvolunteer->updateVolunteerUser($general_info, $user_id, $contact_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
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
							$file_name=md5($general_info['email_address']);
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
				$this->_response_data['error_code']=1430;
			}
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function socialMedia_get(){
		$this->load->model('mvolunteer');
		$user_id=$this->_user['user_id'];
		if($user_id){
			$social_media_list['facebook']=array('id'=>'','name'=>'','handle'=>'');
			$social_media_list['twitter']=array('id'=>'','name'=>'','handle'=>'');
			$social_media_list['linkedin']=array('id'=>'','name'=>'','handle'=>'');
			$social_media_list['github']=array('id'=>'','name'=>'','handle'=>'');
			$social_media=$this->mvolunteer->getUserSocialMedia($user_id);
			if(!empty($social_media)){
				foreach($social_media as $item){
					$social_media_list[strtolower($item->name)]['id']=$this->alil_lib->encrypt_data($item->id);
					$social_media_list[strtolower($item->name)]['name']=$item->name;
					$social_media_list[strtolower($item->name)]['handle']=$item->handle;
				}
			}
			$this->_response_data['status']='SUCCESS';
			$this->_response_data['social_media']=$social_media_list;
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function activities_get(){
		$this->load->model('mgeneral');
		$activities=$this->mgeneral->getActivites();
		if(!empty($activities)){
			$activities=$this->alil_lib->setEncryptValue($activities, array('id'), true);
			$this->_response_data['activites_list']=$activities;
		}else{
			$this->_response_data['activites_list']=array();
		}
		$this->_response_data['status']='SUCCESS';
		$this->buildResponse();
	}
	function rating_get(){
		$this->load->model('mrating');
		$type=$this->get('type');
		$user_id=$this->_user['user_id'];
		$source_id = $this->alil_lib->decrypt_data($this->get('source_id'));
		
        $rating=$this->get('rating');
        $rating_id=$this->get('rating_id')!=''?$this->alil_lib->decrypt_data($this->get('rating_id')):NULL;
		if($source_id && $user_id && $type!=''){
			if($rating==0)
				$return=$this->mrating->deleteRating($user_id, $source_id, $rating, $rating_id, strtoupper($type));
			else if(!$rating_id)
				$return=$this->mrating->createRating($user_id, $source_id, $rating, strtoupper($type));
			else if($rating_id)
				$return=$this->mrating->updateRating($user_id, $source_id, $rating, $rating_id, strtoupper($type));
			
			if($return['status']){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['id']=$this->alil_lib->encrypt_data($return['id']);
				
				$this->_response_data['points']=$this->mrating->getRating($source_id, strtoupper($type))->points;
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
		}
        else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function writeReview_post(){
		$this->load->model('mreview');
		$this->load->model('mrating');
		$review=array();
		$login_status=true;
		$user_id=$this->_user['user_id'];
		if(!$user_id){
			$login_status=false;  
		}
		$rating_id=$this->post('rating_id')?$this->alil_lib->decrypt_data($this->post('rating_id')):null;
		$content=$this->post('content');
		$add_photo=$this->post('add_photo');
		$points=$this->post('points');
		$source_id=$this->post('source_id')?$this->alil_lib->decrypt_data($this->post('source_id')):null;
		$source_type=$this->post('source_type')?strtoupper($this->post('source_type')):'USER';
		
		if(!$this->post('rating_id') || !$this->post('review_rating')){
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1431;
		}elseif(!$login_status){
			$this->_response_data['status']='LOGIN_FAILED';
			$this->_response_data['error_code']=1432;
		}else if($content==''){
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1433;
		}else if(sizeof($this->post())>0){
			if($add_photo=='Yes'){
				$photo_id=$this->post('photo_id');
			}else{
				$photo_id="";
			}
			$return=$this->mreview->createReview($user_id, $rating_id, $points, $content, $source_id, $source_type, $add_photo, $photo_id);
			if(!empty($return) && $return['status']){
				$this->_response_data['status']='SUCCESS';
				$this->_response_data['id']=$this->alil_lib->encrypt_data($return['id']);
				$latest_review=$this->mreview->getReviewByRatingID($rating_id);
				if(!empty($latest_review)){
					$latest_review->edit=true;
					$user_info=$this->mgeneral->getUserInfo($user_id);
					$latest_review->user_name=isset($user_info->username)?$user_info->username:"";
					
					$latest_review->rating_description=$this->mrating->getRatingDescription($latest_review->points);
					$latest_review->rating_avg_description=$this->alil_lib->makeShortName($this->mrating->getRatingDescription($latest_review->points))."_rated";
					$latest_review->review_id=$this->alil_lib->encrypt_data($latest_review->review_id);
					$latest_review->rating_id=$this->alil_lib->encrypt_data($latest_review->rating_id);
					if($latest_review->photos!='')
						$latest_review->photos=json_decode($latest_review->photos);
				}
					
				$this->_response_data['reviews'][]=$latest_review;
			}
			else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1434;
			}
		}
		$this->buildResponse();
	}
	function reviewPhoto_post(){
		$user_id=$this->_user['user_id'];
		$source_id=$this->post('source_id');
		if(!$user_id){
			$this->_response_data['status']='LOGIN_FAILED';
			$this->_response_data['error_code']=1434;
		}elseif($_FILES['files']['name'][0]!=''){
			foreach($_FILES['files']['tmp_name'] as $key=>$tmp_name){
			
				$name=$_FILES['files']['name'][$key];
				$type=$_FILES['files']['type'][$key];
				$tmp_name= $_FILES['files']['tmp_name'][$key];
				$error=$_FILES['files']['error'][$key];
				$size=$_FILES['files']['size'][$key];
				$target_dir="uploads/2/".$user_id;
						
				if($error == UPLOAD_ERR_OK){
					if(verifyUploadDirectory($target_dir)){
						$tmp=explode(".",$name);
						$ext=array_pop($tmp);
						$file_name=md5($user_id);
						$upload_path="./$target_dir/$file_name.$ext";
						$i=1;
						while(file_exists($upload_path)){
							$upload_path="./$target_dir/$file_name-$i.$ext";
							++$i;
						}
						move_uploaded_file($tmp_name, $upload_path);
						$uploaded_url=substr($upload_path,1);
						$return_status=$this->mgeneral->createPhoto($user_id, $uploaded_url, $ext);
						if($return_status['status']){
							$this->_response_data['photo'][$key]['url']=$uploaded_url;
							$this->_response_data['photo'][$key]['ids']=$return_status['id'];
						}
					}
				}
				
			}
			$this->_response_data['status']='SUCCESS';
		}
		$this->buildResponse();
	}
	function removePhoto_delete(){
		$photo_id=$this->delete('id');
		if($photo_id){
			$status=$this->mgeneral->deletePhoto($photo_id);
			if($status){
				$this->_response_data['status']='SUCCESS';
			}else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1403;
			}
				
        }
		$this->buildResponse();
	}
	function loadReviews_get(){
		$this->load->model('mreview');
		$this->load->model('mrating');
		$offset=$this->get('offset');
        $source_id=$this->get('source_id')?$this->alil_lib->decrypt_data($this->get('source_id')):null;
        $source_type=$this->get('source_type')?$this->get('source_type'):"EVENT";
		$user_id=$this->get('user_id')?$this->alil_lib->decrypt_data($this->get('user_id')):null;
		if(!$offset || !$source_id){
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
        }else{
			$offset=($offset*$this->_reviews_pagination_count);
			$limit=$this->_reviews_pagination_count;
			$reviews=$this->mreview->getReviews($source_id, strtoupper($source_type), $offset, $limit);
			if(!empty($reviews)){
				foreach($reviews as $key=>$review){
					$reviews[$key]->edit=false;
					$reviews[$key]->rating_description=$this->mrating->getRatingDescription($review->points);
					$reviews[$key]->rating_avg_description=$this->alil_lib->makeShortName($this->mrating->getRatingDescription($review->points))."_rated";
					$reviews[$key]->review_id=$this->alil_lib->encrypt_data($review->review_id);
					$reviews[$key]->rating_id=$this->alil_lib->encrypt_data($review->rating_id);
					if($review->photos!='')
						$reviews[$key]->photos=json_decode($review->photos);
					if(isset($review->user_id) && $review->user_id==$user_id)
						$reviews[$key]->edit=true;
				}
			}
			
			$remaining_rows=$this->mreview->getReviewsCount($source_id, strtoupper($source_type))-(($offset+1)*$this->_reviews_pagination_count);
			
			$this->_response_data['status']='SUCCESS';
			$this->_response_data['reviews']=$reviews;
			$this->_response_data['remaining_rows']=$remaining_rows;
			
		}
		$this->buildResponse();
	}
	function review_get(){
		$this->load->model('mreview');
		$user_id=$this->_user['user_id'];
		$review_id=$this->get('review_id')?$this->alil_lib->decrypt_data($this->get('review_id')):null;
		if($review_id){
			 $review=$this->mreview->getReviewByID($review_id);
			 if(!empty($review)){
				 $review->rating_id=$this->alil_lib->encrypt_data($review->rating_id);
				 $review->review_id=$this->alil_lib->encrypt_data($review->review_id);
				 $review->source_id=$this->alil_lib->encrypt_data($review->source_id);
				 $review->user_id=$this->alil_lib->encrypt_data($review->user_id);
			 }
			 $this->_response_data['status']='SUCCESS';
			 $this->_response_data['review']=$review;
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		
		$this->buildResponse();
	}
	function updateReview_put(){
		$this->load->model('mreview');
		$review=array();
		$user_id=$this->_user['user_id'];
		$content=$this->put('content');
		$rating_id=$this->alil_lib->decrypt_data($this->put('rating_id'));
		$review_id=$this->alil_lib->decrypt_data($this->put('review_id'));
		$photos=$this->put('photo_id');
		if(empty($photos))
			$photos=array();      
		
		$points=$this->put('points');
		$source_id=$this->alil_lib->decrypt_data($this->put('source_id'));
		$source_type=$this->put('source_type');
		
		$status=$this->mreview->updateReview($user_id, $rating_id, $review_id, $content, $points, $source_id, strtoupper($source_type), $photos);
		if($status){
			$this->_response_data['status']='SUCCESS';
		}else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1407;
		}
		$this->buildResponse();
	}
	function removeReview_delete(){
		$this->load->model('mreview');
		$user_id=$this->_user['user_id'];	
		$review_id=$this->alil_lib->decrypt_data($this->delete('review_id'));
        $rating_id=$this->alil_lib->decrypt_data($this->delete('rating_id'));
        $source_id=$this->alil_lib->decrypt_data($this->delete('source_id'));
		$source_type=strtoupper($this->delete('source_type'));
        if($review_id && $rating_id){
			$status=$this->mreview->deleteReview($user_id, $review_id, $rating_id, $source_id, $source_type);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
				$this->_response_data['status']='FAILED';
				$this->_response_data['error_code']=1407;
			}
        }else{
			$this->_response_data['status']='FAILED';
			$this->_response_data['error_code']=1403;
		}
		$this->buildResponse();
	}
	function attestation_get(){
		$attestation=$this->mgeneral->getAttestation();
		$this->_response_data['status']='SUCCESS';
		$this->_response_data['attestation_list']=$attestation;
		$this->buildResponse();
	}
	function approveOrganization_post(){
		$user_id=$this->_user['user_id'];
		$organization_id=$this->post('organization_id')?$this->alil_lib->decrypt_data($this->post('organization_id')):null;
		if($user_id && $organization_id){
			$status=$this->mgeneral->activateOrganizationUser($user_id, $organization_id);
			if($status)
				$this->_response_data['status']='SUCCESS';
			else{
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