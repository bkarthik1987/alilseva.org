<?php
class Morganization extends CI_Model{
    
	function __construct(){
		parent::__construct();
	}
	function createOrganizationUser($data){
		$this->load->model('mgeneral');
		$confirm=generateString(15);
		$password = generateString(8);
		$encrypt_password=generatePasswordHash($password);
		$handle=$this->alil_lib->makeShortName($data['primary_contact_name']);
		$createdata = array(
		   'username' => $data['primary_email_address'],
		   'password' =>  $encrypt_password,
		   'role'	  => 'ORGANIZATION',
		   'status'=> 'NEW',
		   'activation_key'=> $confirm,
		   'active'=> 0,
		   'creation_date'=>date('Y-m-d H:i:s')
		);
		$query['status'] = $this->db->insert('user',$createdata);
		$user_id=$this->db->insert_id();
		if($user_id){
			$query['user_id']=$user_id;
			/*Create Primary Contact start here*/
			$primary_data['contact_name']=$data['primary_contact_name'];
			$primary_data['email_address']=$data['primary_email_address'];
			$primary_data['website']=$data['website'];
			$primary_data['primary_number']=$data['primary_number'];
			$primary_data['address']=$data['address'];
			$primary_data['handle']=$handle;
			$this->mgeneral->createUserContact($user_id, 'PRIMARY', $primary_data);
			/*Create Primary Contact end here*/
			/*Create Secondary Contact start here*/
			if($data['secondary_contact_name']!='' || $data['secondary_email_address']!='' || $data['secondary_number']!=''){
				$secondary_data['contact_name']=$data['secondary_contact_name'];
				$secondary_data['email_address']=$data['secondary_email_address'];
				$secondary_data['secondary_number']=$data['secondary_number'];
				$this->mgeneral->createUserContact($user_id, 'SECONDARY', $secondary_data);
			}
			/*Create Secondary Contact end here*/
			$organization_name=$data['organization_name'];
			$organization_type=isset($data['organization_type'])?$data['organization_type']:"";
			$license_number=$data['license_number'];
			$this->createOrganization($user_id, $organization_name, $organization_type, $license_number);
			
			if($data['location']!=''){
				$locality_createdata = array(
					'user_id' => $user_id,
					'locality_id' =>  $this->alil_lib->decrypt_data($data['location']),
					'active'=>1,
					'created_by'=>$user_id,
					'creation_date'=>date('Y-m-d H:i:s')
				);
				$status=$this->mgeneral->createUserLocation($locality_createdata);
			}
		}else{
			$query['status'] = false;
		}
		if($query['status']){
			$data['mail']['confirm_link']='<a href="'.base_url('confirm').'/'.$this->alil_lib->encrypt_data($user_id).'/'.$this->alil_lib->encrypt_data($confirm).'">Confirm</a>';
			$data['mail']['copy_link']=base_url('confirm').'/'.$this->alil_lib->encrypt_data($user_id).'/'.$this->alil_lib->encrypt_data($confirm);
			$data['mail']['vcode']=$confirm;
			$data['mail']['username']=$data['primary_email_address'];
			$data['mail']['password']=$password;
			$msg=$this->load->view('mail-template/signup',$data,true);
			$email_address=$data['primary_email_address'];
			$subject='Alil Verification email';
			$status=$this->alil_lib->sendEmailNotification($email_address, $subject, $msg);
		}
		return $query;
	}
	function updateOraganizationUser($data, $contact_id, $secondary_contact_id, $organization_id, $user_id){
		$this->load->model('mgeneral');
		$status=false;
		if($user_id){
			/*Create Primary Contact start here*/
			$primary_data['contact_name']=$data['primary_contact_name'];
			$primary_data['email_address']=$data['primary_email_address'];
			$primary_data['website']=$data['website'];
			$primary_data['primary_number']=$data['primary_number'];
			$primary_data['address']=$data['address'];
			$status= $this->mgeneral->updateUserContact($user_id, $contact_id, 'PRIMARY', $primary_data);
			/*Create Primary Contact end here*/
			/*Create Secondary Contact start here*/
			if($secondary_contact_id!='' && $secondary_contact_id!=null && ($data['secondary_contact_name']!='' || $data['secondary_email_address']!='' || $data['secondary_number']!='')){
				$secondary_data['contact_name']=$data['secondary_contact_name'];
				$secondary_data['email_address']=$data['secondary_email_address'];
				$secondary_data['secondary_number']=$data['secondary_number'];
				$status=$this->mgeneral->updateUserContact($user_id, $secondary_contact_id, 'SECONDARY', $secondary_data);
			}else if($data['secondary_contact_name']!='' || $data['secondary_email_address']!='' || $data['secondary_number']!=''){
				$secondary_data['contact_name']=$data['secondary_contact_name'];
				$secondary_data['email_address']=$data['secondary_email_address'];
				$secondary_data['secondary_number']=$data['secondary_number'];
				$status=$this->mgeneral->createUserContact($user_id, 'SECONDARY', $secondary_data);
			}
			/*Create Secondary Contact end here*/
			$organization_name=$data['organization_name'];
			$organization_type=isset($data['organization_type'])?$data['organization_type']:"";
			$license_number=$data['license_number'];
			$status=$this->updateOrganization($user_id, $organization_id, $organization_name, $organization_type, $license_number);
			if(isset($data['location']) && $data['location']!=''){
				$this->load->model('mgeneral');
				$user_location=$this->mgeneral->getUserLocation($user_id);
				if(!empty($user_location)){
					$locality_update_data = array(
						'locality_id' => $this->alil_lib->decrypt_data($data['location']),
						'active'=>1,
						'updated_by'=>$user_id,
						'updation_date'=>date('Y-m-d H:i:s')
					);
					$status=$this->mgeneral->updateUserLocation($locality_update_data, $user_location->id);
				}else{
					$locality_createdata = array(
						'user_id' => $user_id,
						'locality_id' =>  $this->alil_lib->decrypt_data($data['location']),
						'active'=>1,
						'created_by'=>$user_id,
						'creation_date'=>date('Y-m-d H:i:s')
					);
					$status=$this->mgeneral->createUserLocation($locality_createdata);
				}
			}
		}
		return $status;
	}
	
	function createOrganization($user_id, $organization_name, $organization_type, $license_number){
		$status = false;
		$data=array(
			'name' => $organization_name,
			'organization_type'=> $organization_type,
			'license_number'=> $license_number,
			'active'=>1,
			'created_by'=>$user_id,
			'creation_date'=>date("Y-m-d H:i:s")
		);
		$this->db->insert('organization',$data);
		$organization_id=$this->db->insert_id();
		if($organization_id){
			$user_organization=array(
				'user_id'=>$user_id,
				'organization_id'=>$organization_id,
				'active'=>1,
				'created_by'=>$user_id,
				'creation_date'=>date("Y-m-d H:i:s")
			);
			$status=$this->db->insert('user_organization',$user_organization);
		}
		return $status;
	}
	function updateOrganization($user_id, $organization_id, $organization_name, $organization_type, $license_number){
		$status = false;
		$data=array(
			'name' => $organization_name,
			'organization_type'=> $organization_type,
			'license_number'=> $license_number,
			'active'=>1,
			'updated_by'=>$user_id,
			'updation_date'=>date("Y-m-d H:i:s")
		);
		$this->db->where('id', $organization_id);
		$status=$this->db->update('organization',$data);
		if($organization_id){
			$user_organization=array(
				'active'=>1,
				'updated_by'=>$user_id,
				'updation_date'=>date("Y-m-d H:i:s")
			);
			$this->db->where('organization_id', $user_id);
			$this->db->where('user_id', $organization_id);
			$status=$this->db->update('user_organization',$user_organization);
		}
		return $status;
	}
	function createEvents($event_name, $event_short_name, $description, $type, $importance, $location_id, $domain_id, $need, $status, $user_id){
		$event_data=array(
			'user_id'=>$user_id,
			'event_name'=>$event_name,
			'short_name'=>$event_short_name,
			'description'=>$description,
			'type'=>$type,
			'importance'=>$importance,
			'location_id'=>$location_id,
			'need'=>$need,
			'status'=>$status,
			'active'=>0,
			'created_by'=>$user_id,
			'creation_date'=>date("Y-m-d H:i:s")
		);
		if($this->db->insert('events',$event_data)){
			return $this->db->insert_id();
		}else{
			return false;
		}
	}
	function updateEvents($event_id, $event_data){
		$this->db->where('id', $event_id);
		if($this->db->update('events',$event_data)){
			return true;
		}else{
			return false;
		}
	}
	function createRepetitive($event_id, $repetitive, $repeat_on, $start_date, $end_type, $completion, $status, $user_id){
		$data=array(
			'event_id'=>$event_id,
			'repetitive'=>$repetitive,
			'repeat_on'=>$repeat_on,
			'start_date'=>$start_date,
			'end_type'=>$end_type,
			'completion'=>$completion,
			'status'=>$status,
			'active'=>1,
			'created_by'=>$user_id,
			'creation_date'=>date("Y-m-d H:i:s")
		);
		if($this->db->insert('repetitive_events',$data)){
			return true;
		}else{
			return false;
		}
	}
	function updateRepetitive($event_id, $repetitive_events_id, $repetitive, $repeat_on, $start_date, $end_type, $completion, $status, $user_id){
		$data=array(
			'repetitive'=>$repetitive,
			'repeat_on'=>$repeat_on,
			'start_date'=>$start_date,
			'end_type'=>$end_type,
			'completion'=>$completion,
			'status'=>$status,
			'active'=>1,
			'created_by'=>$user_id,
			'creation_date'=>date("Y-m-d H:i:s")
		);
		$this->db->where('id', $repetitive_events_id);
		if($this->db->update('repetitive_events',$data)){
			return true;
		}else{
			return false;
		}
	}
	function eventSchedule($event_id, $type, $start_time, $end_time, $user_id){
		$schedule_data=array(
			'event_id'=>$event_id,
			'type'=>$type,
			'start_time'=>$start_time,
			'end_time'=>$end_time,
			'active'=>1,
			'created_by'=>$user_id,
			'creation_date'=>date("Y-m-d H:i:s")
		);
		if($this->db->insert('event_schedule',$schedule_data)){
			return true;
		}else{
			return false;
		}
	}
	function updateEventSchedule($event_schedule_id, $type, $start_time, $end_time, $user_id){
		$schedule_data=array(
			'type'=>$type,
			'start_time'=>$start_time,
			'end_time'=>$end_time,
			'active'=>1,
			'updated_by'=>$user_id,
			'updation_date'=>date("Y-m-d H:i:s")
		);
		$this->db->where('id', $event_schedule_id);
		if($this->db->update('event_schedule',$schedule_data)){
			return true;
		}else{
			return false;
		}
	}
	function createEventItem($event_id, $item_id, $user_id){
		$items_detail=$this->getEventIndividualItems($event_id, $item_id);
		if(!empty($items_detail)){
			$update_data=array(
				'active'=>1,
				'updated_by'=>$user_id,
				'updation_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where('id', $items_detail->id);
			if(!$this->db->update('event_items_need', $update_data)){
				return false;
			}
		}else{
			$data=array(
				'event_id'=>$event_id,
				'item_id'=>$item_id,
				'active'=>1,
				'created_by'=>$user_id,
				'creation_date'=>date("Y-m-d H:i:s")
			);
			if(!$this->db->insert('event_items_need',$data)){
				return false;
			}
		}
		return true;
	}
	function getEventIndividualItems($event_id, $item_id){
		$this->db->select("id, event_id, item_id")->from("event_items_need")->where('item_id',$item_id);
		$this->db->where('event_id', $event_id);
		return $this->db->get()->first_row();
	}
	function removeEventItems($items_need_id, $user_id){
		$data=array(
			'active'=>0,
			'updated_by'=>$user_id,
			'updation_date'=>date("Y-m-d H:i:s")
		);
		$this->db->where('id', $items_need_id);
		if($this->db->update('event_items_need',$data)){
			return true;
		}else{
			return false;
		}
	}
	function createEventSkill($event_id, $skill_id, $user_id){
		$skills_detail=$this->getEventIndividualSkills($event_id, $skill_id);
		if(!empty($skills_detail)){
			$update_data=array(
				'active'=>1,
				'updated_by'=>$user_id,
				'updation_date'=>date('Y-m-d H:i:s')
			);
			$this->db->where('id', $skills_detail->id);
			if(!$this->db->update('event_volunteers_need', $update_data)){
				return false;
			}
		}else{
			$data=array(
				'event_id'=>$event_id,
				'skill_id'=>$skill_id,
				'active'=>1,
				'created_by'=>$user_id,
				'creation_date'=>date("Y-m-d H:i:s")
			);
			if(!$this->db->insert('event_volunteers_need',$data)){
				return false;
			}
		}
		return true;
	}
	function getEventIndividualSkills($event_id, $skill_id){
		$this->db->select("id, event_id, skill_id")->from("event_volunteers_need")->where('skill_id',$skill_id);
		$this->db->where('event_id', $event_id);
		return $this->db->get()->first_row();
	}
	function removeEventSkill($volunteers_need_id, $user_id){
		$data=array(
			'active'=>0,
			'updated_by'=>$user_id,
			'updation_date'=>date("Y-m-d H:i:s")
		);
		$this->db->where('id',$volunteers_need_id);
		if($this->db->update('event_volunteers_need',$data)){
			return true;
		}else{
			return false;
		}
	}
	function getUserOrganization($user_id){
		$this->db->select('user_organization.user_id, user_organization.organization_id, organization.name, 
						organization.organization_type, organization. license_number');
		$this->db->from('user_organization');
		$this->db->join('organization','organization.id=user_organization.organization_id AND organization.active=1');
		$this->db->where('user_organization.user_id',$user_id);
		$this->db->where('user_organization.active',1);
		return $this->db->get()->first_row();
	}
	function saveEventPhoto($event_id, $user_id, $uploaded_url, $cover_photo, $ext, $info=''){
		$return = array();
		$return['status']=false;
		if($cover_photo){
			$this->db->select("photo_id")->from('photo_attach')->where('attach_id', $event_id);
			$photo=$this->db->where('attach_type','EVENT')->where('cover',1)->get();
			if($photo->num_rows()>0)
				$photo_id=$photo->first_row()->photo_id;
		}
		if(isset($photo_id) && $photo_id ){
			$photo_data=array(
						'active'=> 1,
						'type'=>strtolower($ext),
						'caption'=>$info,
						'url'=>$uploaded_url,
						'updated_by'=>$user_id,
						'updation_date'=>date("Y-m-d H:i:s")
						);
			$this->db->where('id',$photo_id);
			$this->db->update('photo',$photo_data);
			$return['status']=true;
			$return['id']=$photo_id;
		}else{
			$photo_data=array(
                        'type'=>strtolower($ext),
                        'caption'=>$info,
                        'url'=>$uploaded_url,
                        'active'=>'1',
                        'created_by'=>$user_id,
                        'creation_date'=>date("Y-m-d H:i:s")
                        );
			$this->db->insert('photo', $photo_data);
			$photo_id=$this->db->insert_id();
			$photo_attach_data=array(
							'photo_id'=>$photo_id,
							'attach_id'=>$event_id,
							'attach_type'=>'EVENT',
							'cover'=> $cover_photo,
							'active'=>'1',
							'created_by'=>$user_id,
							'creation_date'=>date("Y-m-d H:i:s")
                        );
			$this->db->insert('photo_attach', $photo_attach_data);               
			$return['status']=true;
			$return['id']=$photo_id;			
		}
		return $return;
	}
}

?>
