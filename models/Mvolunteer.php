<?php
class Mvolunteer extends CI_Model{
    
	function __construct(){
		parent::__construct();
	}
	function createVolunteerUser($data, $role){
		$this->load->model('mgeneral');
		$confirm=generateString(15);
		$password = generateString(8);
		$encrypt_password=generatePasswordHash($password);
		$createdata = array(
		   'username' => $data['email_address'],
		   'password' =>  $encrypt_password,
		   'role'	  => $role,
		   'status'=> 'NEW',
		   'activation_key'=> $confirm,
		   'active'=> 0,
		   'creation_date'=>date('Y-m-d H:i:s')
		);
		$query['status'] = $this->db->insert('user',$createdata);
		$user_id=$this->db->insert_id();
		if($user_id){
			$query['user_id']=$user_id;
			/*User Location start here*/
			if(isset($data['location']) && $data['location']!='' && is_numeric($this->alil_lib->decrypt_data($data['location']))){
				$locality_createdata = array(
					'user_id' => $user_id,
					'locality_id' =>  $this->alil_lib->decrypt_data($data['location']),
					'active'=>1,
					'created_by'=>$user_id,
					'creation_date'=>date('Y-m-d H:i:s')
				);
				$status=$this->mgeneral->createUserLocation($locality_createdata);
			}
			/*User Location end here*/
			$handle=$this->alil_lib->makeShortName($data['name']);
			$contact_data=array(
				'contact_name' => $data['name'],
				'gender'=> $data['gender'],
				'email_address'=> isset($data['email_address'])?$data['email_address']:"",
				'age'=> isset($data['age'])?$data['age']:"",
				'primary_number'=> isset($data['primary_number'])?$data['primary_number']:"",
				'area'=> isset($data['area'])?$data['area']:"",
				'education'=> isset($data['education'])?$data['education']:"",
				'profession'=> isset($data['profession'])?$data['profession']:"",
				'handle'=> $handle
			);
			$status=$this->mgeneral->createUserContact($user_id, 'PRIMARY', $contact_data);
			if($status){
				$query['status'] = true;
			}else{
				$query['status'] = false;
			}
		}else{
			$query['status'] = false;
		}
		if($query['status']){
			$data['mail']['confirm_link']='<a href="'.base_url('confirm').'/'.$this->alil_lib->encrypt_data($user_id).'/'.$this->alil_lib->encrypt_data($confirm).'">Confirm</a>';
			$data['mail']['copy_link']=base_url('confirm').'/'.$this->alil_lib->encrypt_data($user_id).'/'.$this->alil_lib->encrypt_data($confirm);
			$data['mail']['vcode']=$confirm;
			$data['mail']['username']=$data['email_address'];
			$data['mail']['password']=$password;
			$msg=$this->load->view('mail-template/signup',$data,true);
			$email_address=$data['email_address'];
			$subject='Alil Verification email';
			$status=$this->alil_lib->sendEmailNotification($email_address, $subject, $msg);
		}
		return $query;
	}
	function updateVolunteerUser($data, $user_id, $contact_id){
		$this->load->model('mgeneral');
		if($user_id){
			/*User Location start here*/
			if(isset($data['location']) && $data['location']!='' && is_numeric($this->alil_lib->decrypt_data($data['location']))){
				$location_id=$this->alil_lib->decrypt_data($data['location']);
				$user_location=$this->mgeneral->getUserLocation($user_id);
				if(!empty($user_location)){
					$locality_update_data = array(
						'locality_id' =>  $location_id,
						'active'=>1,
						'updated_by'=>$user_id,
						'updation_date'=>date('Y-m-d H:i:s')
					);
					$status=$this->mgeneral->updateUserLocation($locality_update_data, $user_location->id);
				}else{
					$locality_createdata = array(
						'user_id' => $user_id,
						'locality_id' =>  $location_id,
						'active'=>1,
						'created_by'=>$user_id,
						'creation_date'=>date('Y-m-d H:i:s')
					);
					$status=$this->mgeneral->createUserLocation($locality_createdata);
				}
			}
			/*User Location end here*/
			$contact_data=array(
				'contact_name' => $data['name'],
				'gender'=> $data['gender'],
				'email_address'=> isset($data['email_address'])?$data['email_address']:"",
				'age'=> isset($data['age'])?$data['age']:"",
				'primary_number'=> isset($data['primary_number'])?$data['primary_number']:"",
				'area'=> isset($data['area'])?$data['area']:"",
				'education'=> isset($data['education'])?$data['education']:"",
				'profession'=> isset($data['profession'])?$data['profession']:""
			);
			return $this->mgeneral->updateUserContact($user_id, $contact_id, 'PRIMARY', $contact_data);
		}else{
			return false;
		}
	}
	function createVolunteerpreference($data, $user_id){
		$availabilty=$this->config->item('availabilty');
		$location_preference=$this->config->item('location_preference');
		$createdata = array(
		   'user_id' => $user_id,
		   'ability' =>  isset($data['ability'])?$data['ability']:"",
		   'acheivments' =>  isset($data['acheivments'])?$data['acheivments']:"",
		   'interests' =>  isset($data['interests'])?$data['interests']:"",
		   'availabilty' =>  isset($data['availabilty'])?$availabilty[$data['availabilty']]:"",
		   'location_preference' =>  isset($data['location_preference'])?$location_preference[$data['location_preference']]:"",
		   'active'=>1,
		   'created_by'=>$user_id,
		   'creation_date'=>date('Y-m-d H:i:s')
		);
		$status=$this->db->insert('volunteer_preference',$createdata);
		return $status;
	}
	function createUserVolunteerSkills($user_id, $skill_id){
		$skills_detail=$this->getUserIndividualSkills($user_id, $skill_id);
		if(!empty($skills_detail)){
			$update_data=array(
				'active'=>1,
				'updated_by'=>$user_id,
				'updation_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where('id', $skills_detail->id);
			if(!$this->db->update('volunteer_skills', $update_data)){
				return false;
			}
		}else{
			$data=array(
					'user_id' => $user_id,
					'skill_id'=> $skill_id,
					'active'  => 1,
					'created_by'=>$user_id,
					'creation_date'=> date('Y-m-d H:i:s')
				);
			if(!$this->db->insert('volunteer_skills',$data)){
				return false;
			}
		}
		return true;
		/*return $this->db->insert_batch('volunteer_skills',$data);*/
	}
	function createUserVolunteerServices($user_id, $service_id){
		$service_detail=$this->getUserIndividualServices($user_id, $service_id);
		if(!empty($service_detail)){
			$update_data=array(
				'active'=>1,
				'updated_by'=>$user_id,
				'updation_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where('id', $service_detail->id);
			if(!$this->db->update('volunteer_services', $update_data)){
				return false;
			}
		}else{
			$data=array(
					'user_id' => $user_id,
					'service_id'=> $service_id,
					'active'  => 1,
					'created_by'=>$user_id,
					'creation_date'=> date('Y-m-d H:i:s')
				);
			if(!$this->db->insert('volunteer_services',$data)){
				return false;
			}
		}
		return true;
		/*return $this->db->insert_batch('volunteer_services',$data);*/
	}
	function createUserSocialMedia($data){
		if(!empty($data)){
			foreach($data as $item){
				$social_media=$this->getUserIndividualSocialMedia($item['user_id'], $item['name']);
				if(!empty($social_media)){
					$update_data=array(
						'handle'=>$item['handle'],
						'active'=>1,
						'updated_by'=>$item['user_id'],
						'updation_date'=>date('Y-m-d H:i:s')
					);
					$this->db->where('id', $social_media->id);
					if(!$this->db->update('user_socialmedia', $update_data)){
						return false;
					}
				}else{
					if(!$this->db->insert('user_socialmedia',$item)){
						return false;
					}
				}
			}
			return true;
		}
		/*return $this->db->insert_batch('user_socialmedia',$data);*/
	}
	function createUserActivities($user_id, $activity_id){
		$activity_detail=$this->getVolunteerIndividualActivites($user_id, $activity_id);
		if(!empty($activity_detail)){
			$update_data=array(
				'active'=>1,
				'updated_by'=>$user_id,
				'updation_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where('id', $activity_detail->id);
			if(!$this->db->update('volunteer_activity_preference', $update_data)){
				return false;
			}
		}else{
			$data=array(
					'user_id' => $user_id,
					'activity_id'=> $activity_id,
					'active'  => 1,
					'created_by'=>$user_id,
					'creation_date'=> date('Y-m-d H:i:s')
				);
			if(!$this->db->insert('volunteer_activity_preference',$data)){
				return false;
			}
		}
		return true;
		/*return $this->db->insert_batch('volunteer_activity_preference',$data);*/
	}
	function createNewActivities($data){
		if($this->db->insert('activities',$data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	function updateSocialMedia($data, $user_id, $primary=false){
		$status= false;
		$this->db->select("*")->from("user_socialmedia")->where('user_id',$user_id);
		if($primary){
			$this->db->where('name','PRIMARY');
		}
		$setting=$this->db->get()->first_row();
		if(!empty($setting)){
			$this->db->where('user_id', $user_id);
			if($this->db->update('user_socialmedia', $data)){
				$status= true;
			}
		}else{
			if($this->db->insert('user_socialmedia',$data)){
				$status= true;
			}
		}
		return $status;
	}
	function updateVolunteerPreference($data, $user_id){
		$status= false;
		$row=$this->db->select("*")->from("volunteer_preference")->where('user_id',$user_id)->get()->first_row();
		if(!empty($row)){
			$this->db->where('user_id', $user_id);
			if($this->db->update('volunteer_preference', $data)){
				$status= true;
			}
		}else{
			if($this->db->insert('volunteer_preference',$data)){
				$status= true;
			}
		}
		return $status;
	}
	function getVolunteerIndividualActivites($user_id, $activity_id){
		$this->db->select("id, user_id, activity_id")->from("volunteer_activity_preference")->where('user_id',$user_id);
		$this->db->where('activity_id', $activity_id);
		return $this->db->get()->first_row();
	}
	function getUserIndividualSkills($user_id, $skill_id){
		$this->db->select("id, user_id, skill_id")->from("volunteer_skills")->where('user_id',$user_id);
		$this->db->where('skill_id', $skill_id);
		return $this->db->get()->first_row();
	}
	function getUserIndividualServices($user_id, $service_id){
		$this->db->select("id, user_id, service_id")->from("volunteer_services")->where('user_id',$user_id);
		$this->db->where('service_id', $service_id);
		return $this->db->get()->first_row();
	}
	function getUserIndividualSocialMedia($user_id, $media_name){
		$this->db->select("id, user_id, name, handle")->from("user_socialmedia")->where('user_id',$user_id);
		$this->db->where('name', $media_name);
		return $this->db->get()->first_row();
	}
	function getUserSocialMedia($user_id){
		$this->db->select("id, user_id, name, handle")->from("user_socialmedia")->where('user_id',$user_id);
		$this->db->where('active',1);
		return $this->db->get()->result();
	}
	function getVolunteerPreference($user_id){
		$this->db->select("id, user_id, ability, acheivments, interests, availabilty, location_preference, communicate_by");
		$this->db->from("volunteer_preference")->where('user_id',$user_id);
		$this->db->where('active',1);
		return $this->db->get()->first_row();
	}
	function getVolunteerSkills($user_id){
		$this->db->select("volunteer_skills.id, volunteer_skills.user_id, volunteer_skills.skill_id, skills.name as skill_name");
		$this->db->from("volunteer_skills");
		$this->db->join('skills','skills.id=volunteer_skills.skill_id AND skills.active=1');
		$this->db->where('volunteer_skills.user_id',$user_id);
		$this->db->where('volunteer_skills.active',1);
		return $this->db->get()->result();
	}
	function getVolunteerServices($user_id){
		$this->db->select("volunteer_services.id, volunteer_services.user_id, volunteer_services.service_id, services.name as service_name");
		$this->db->from("volunteer_services");
		$this->db->join('services','services.id=volunteer_services.service_id AND services.active=1');
		$this->db->where('volunteer_services.user_id',$user_id);
		$this->db->where('volunteer_services.active',1);
		return $this->db->get()->result();
	}
	function getvolunteerActivityPreference($user_id){
		$this->db->select("activity_preference.id, activity_preference.user_id, activity_preference.activity_id, activities.name as activity_name");
		$this->db->from("volunteer_activity_preference as activity_preference");
		$this->db->join('activities','activities.id=activity_preference.activity_id AND activities.active=1');
		$this->db->where('activity_preference.user_id',$user_id);
		$this->db->where('activity_preference.active',1);
		return $this->db->get()->result();
	}
	function removeVolunteerSkill($volunteer_skill_id, $user_id){
		$update_data=array(
			'active'=>0,
			'updated_by'=> $user_id,
			'updation_date'=>date('Y-m-d H:i:s')
		);
		$this->db->where('id', $volunteer_skill_id);
		return $this->db->update('volunteer_skills', $update_data);
	}
	function removeVolunteerService($volunteer_service_id, $user_id){
		$update_data=array(
			'active'=>0,
			'updated_by'=> $user_id,
			'updation_date'=>date('Y-m-d H:i:s')
		);
		$this->db->where('id', $volunteer_service_id);
		return $this->db->update('volunteer_services', $update_data);
	}
	function getVolunteerList(){
		$this->db->select("user.id as user_id, locality.name as location_name, contact.name as user_name, 
						contact.handle as user_handle, photo.url as user_cover_photo
						");
		$this->db->from("user");
		$this->db->join('user_location','user_location.user_id=user.id AND user_location.active=1','left');
		$this->db->join('locality','locality.id=user_location.locality_id AND locality.active=1','left');
		$this->db->join('contact_source','contact_source.user_id=user.id AND contact_source.contact_type="PRIMARY" AND contact_source.active=1');
		$this->db->join('contact','contact_source.contact_id=contact.id AND contact.active=1');
		$this->db->join("photo_attach","photo_attach.attach_id=user.id AND photo_attach.attach_type='USER' AND photo_attach.cover=1 AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		$this->db->where('user.active',1);
		$this->db->where('user.role','VOLUNTEER');
		$this->db->order_by('user.id','ASC');
		return $this->db->get()->result();
	}
	
}

?>
