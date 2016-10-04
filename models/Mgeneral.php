<?php
class Mgeneral extends CI_Model{
    
	function __construct(){
		parent::__construct();
	}
	function sessoinRegenerate($access_token, $user_data){
		$this->session->sess_regenerate();
		$session_id = session_id();
		$user_data=serialize($user_data);
		if(empty($this->checkAuthorizationPermission($session_id))){
			$data = array(
				'id'			=> $session_id,
				'access_token' 	=> $access_token,
				'last_activity' => time(),
				'creation_time' => time(),
				'active'		=>	1,
				'user_data'		=>	$user_data
			);
			if($this->db->insert('session', $data)){
				return $session_id;
			}else{
				return false;
			}
		}else{
			$this->session->sess_regenerate();
			$session_id = session_id();
			$this->sessoinRegenerate($access_token, $user_data);
		}
	}
	function updateLastActivity($sessoin_id){
		$this->db->where('id', $sessoin_id);
		if($this->db->update('session', array('last_activity' => time()))){
			return true;
		}else{
			return false;
		}
	}
	function checkAuthorizationPermission($sessoin_id){
		$user=$this->db->select("*")->from("session")->where('id',$sessoin_id)->where('active',1)->get();
		return $user->first_row();
	}
	function deactiveSessoin($sessoin_id){
		$this->db->where('id', $sessoin_id);
		if($this->db->update('session', array('active' => 0))){
			return true;
		}else{
			return false;
		}
	}
	function verfyUser($username, $password){
		$user=$this->db->select("*")->from("user")->where(array('username'=>$username))->get();
		$user_detail=$user->first_row();
		if($user->num_rows() == 1 && ($user_detail->status=='ACTIVE' || $user_detail->status=='VERIFIED') && $user_detail->active == 1){
			if(crypt($password, $user_detail->password) == $user_detail->password) {
				return $user_detail;
			}else{
				return false;
			}
		}else if(isset($user_detail->status) && $user_detail->status=='NEW' && ($user_detail->active == 0 || $user_detail->active == 1)){
			if(crypt($password, $user_detail->password) == $user_detail->password) {
				return $user_detail;
			}else{
				return false;
			}
		}else if(isset($user_detail->status) && $user_detail->status!='ACTIVE'){
			return false;
		}else
			return false;
	}
	function checkEmailAddress($email_address){
		$user=$this->db->select("*")->from("user")->where('username',$email_address)->get();
		return $user->first_row();
	}
	function passwordRegenerate($user_id, $user_name, $email_address){
		$password = generateString(8);
		$encrypt_password=generatePasswordHash($password);
		$data = array(
			'password'	=> 	$encrypt_password,
			'updated_by'=>	$user_id,
			'updation_date' => date("Y-m-d H:i:s")
		);
		$this->db->where('id', $user_id);
		if($this->db->update('user', $data)){
			$udata = array(
				'password'	=> 	$password,
				'username'=>	$user_name
			);
			$msg=$this->load->view('mail-template/forgot-password',$udata,true);
			$subject='Alil - Regenerate New Password';
			$status=$this->alil_lib->sendEmailNotification($email_address, $subject, $msg);
			if($status)
				return true;
			else
				return false;
			
		}else{
			return false;
		}
	}
	function verfyOldPassword($user_id, $password){
		$user=$this->db->select("*")->from("user")->where(array('id'=>$user_id))->get();
		$user_detail=$user->first_row();
		if($user->num_rows() == 1 && ($user_detail->status=='ACTIVE' || $user_detail->status=='VERIFIED') && $user_detail->active == 1){
			if(crypt($password, $user_detail->password) == $user_detail->password) {
				return true;
			}else{
				return false;
			}
		}else
			return false;
	}
	function updatePassword($user_id, $password){
		$encrypt_password=generatePasswordHash($password);
		$data = array(
			'password'	=> 	$encrypt_password,
			'updated_by'=>	$user_id,
			'updation_date' => date("Y-m-d H:i:s")
		);
		$this->db->where('id', $user_id);
		if($this->db->update('user', $data)){
			return true;
		}else{
			return false;
		}
	}
	function resendVerificationCode($user_id, $email_address){
		$confirm=generateString(15);
		$data = array(
			'activation_key'	=> 	$confirm,
			'updated_by'=>	$user_id,
			'updation_date' => date("Y-m-d H:i:s")
		);
		$this->db->where('id', $user_id);
		if($this->db->update('user', $data)){
			$udata['mail']=array(
							'confirm_link' => '<a href="'.base_url('confirm').'/'.$this->alil_lib->encrypt_data($user_id).'/'.$this->alil_lib->encrypt_data($confirm).'">Confirm</a>',
							'copy_link'=>base_url('confirm').'/'.$this->alil_lib->encrypt_data($user_id).'/'.$this->alil_lib->encrypt_data($confirm),
							'vcode'=>$confirm
						);
			$msg=$this->load->view('mail-template/resend-verification-code',$udata,true);
			$subject='Alil - Regenerate New Verification Code';
			$status=$this->alil_lib->sendEmailNotification($email_address, $subject, $msg);
			if($status)
				return true;
			else
				return false;
			
		}else{
			return false;
		}
	}
	function createUserContact($user_id, $contact_type, $data){
		$status = false;
		$contact=array(
			'active'=>1,
			'created_by'=>$user_id,
			'creation_date'=>date("Y-m-d H:i:s")
		);
		if(isset($data['contact_name'])){
			$contact['name'] = $data['contact_name'];
		}
		if(isset($data['email_address'])){
			$contact['emailid'] = $data['email_address'];
		}
		if(isset($data['primary_number'])){
			$contact['primary_number'] = $data['primary_number'];
		}
		if(isset($data['secondary_number'])){
			$contact['secondary_number'] = $data['secondary_number'];
		}
		if(isset($data['age'])){
			$contact['age'] = $data['age'];
		}
		if(isset($data['gender'])){
			$contact['gender'] = $data['gender'];
		}
		if(isset($data['website'])){
			$contact['website'] = $data['website'];
		}
		if(isset($data['address'])){
			$contact['address'] = $data['address'];
		}
		if(isset($data['area'])){
			$contact['area'] = $data['area'];
		}
		if(isset($data['education'])){
			$contact['education_level'] = $data['education'];
		}
		if(isset($data['profession'])){
			$contact['profession'] = $data['profession'];
		}
		$status=$this->db->insert('contact',$contact);
		$contact_id=$this->db->insert_id();
		if($contact_id){
			$query['contact_id']=$contact_id;
			$contact_source=array(
				'contact_id'=>$contact_id,
				'user_id'=>$user_id,
				'contact_type'=>$contact_type,
				'active'=>1,
				'created_by'=>$user_id,
				'creation_date'=>date("Y-m-d H:i:s")
			);
			$status=$this->db->insert('contact_source',$contact_source);
			if(isset($data['handle']) && $data['handle']!=''){
				$contact=array( 'handle' => $data['handle']."-".$contact_id );
				$this->db->where('id', $contact_id);
				$this->db->update('contact',$contact);
			}
		}
		return $status;
	}
	function updateUserContact($user_id, $contact_id, $contact_type, $data){
		$status = false;
		if($contact_id){
			$contact =array(
				'active'=>1,
				'updated_by'=>$user_id,
				'updation_date'=>date("Y-m-d H:i:s")
			);
			if(isset($data['contact_name'])){
				$contact['name'] = $data['contact_name'];
			}
			if(isset($data['email_address'])){
				$contact['emailid'] = $data['email_address'];
			}
			if(isset($data['primary_number'])){
				$contact['primary_number'] = $data['primary_number'];
			}
			if(isset($data['secondary_number'])){
				$contact['secondary_number'] = $data['secondary_number'];
			}
			if(isset($data['age'])){
				$contact['age'] = $data['age'];
			}
			if(isset($data['gender'])){
				$contact['gender'] = $data['gender'];
			}
			if(isset($data['website'])){
				$contact['website'] = $data['website'];
			}
			if(isset($data['address'])){
				$contact['address'] = $data['address'];
			}
			if(isset($data['area'])){
				$contact['area'] = $data['area'];
			}
			if(isset($data['education'])){
				$contact['education_level'] = $data['education'];
			}
			if(isset($data['profession'])){
				$contact['profession'] = $data['profession'];
			}
			$this->db->where('id', $contact_id);
			$status=$this->db->update('contact',$contact);
		}
		return $status;
	}
	function getUserInfo($user_id){
		$this->db->select("user.id, user.status, user.activation_key, user.role, user.active, c1.emailid, 
						c1.primary_number, c1.age, c1.gender, c1.website, c1.address, c1.area,
						c1.education_level, c1.profession, c1.name as username, c2.name as secondary_contact_name, 
						c2.emailid as secondary_emailid, c2.secondary_number, c2.id as secondary_contact_id, 
						c1.id as contact_id, locality.name as location_name, locality.id as location_id,
						district.name as district_name, state.name as state_name, country.name as country_name,
						photo.url as profile_url
						");
		$this->db->join("contact_source as cs1","cs1.user_id=user.id AND cs1.contact_type='PRIMARY'");
		$this->db->join("contact as c1","cs1.contact_id=c1.id  AND c1.active=1");
		
		$this->db->join("contact_source as cs2","cs2.user_id=user.id AND cs2.contact_type='SECONDARY'",'left');
		$this->db->join("contact as c2","cs2.contact_id=c2.id  AND c2.active=1",'left');
		
		
		$this->db->join('user_location','user_location.user_id=user.id AND user_location.active=1','left');
		$this->db->join('locality','locality.id=user_location.locality_id AND locality.active=1','left');
		$this->db->join("district","locality.district_id=district.id AND district.active=1",'left');
		$this->db->join("state","state.id=district.state_id  AND state.active=1",'left');
		$this->db->join("country","country.id=state.country_id  AND country.active=1",'left');
		
		$this->db->join("photo_attach","photo_attach.attach_id=user.id AND photo_attach.attach_type='USER' AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
		$this->db->from("user");
		$this->db->where('user.id',$user_id);
		return $this->db->get()->first_row();
	}
	function getAttestation(){
		$this->db->select("user.id, user.username, user.status, user.activation_key, user.role, user.active, c1.emailid, 
						c1.primary_number, c1.age, c1.gender, c1.website, c1.address, c1.area,
						c1.education_level, c1.profession, c1.name as username, c2.name as secondary_contact_name, 
						c2.emailid as secondary_emailid, c2.secondary_number, c2.id as secondary_contact_id, 
						c1.id as contact_id, locality.name as location_name, locality.id as location_id,
						district.name as district_name, state.name as state_name, country.name as country_name,
						photo.url as profile_url
						");
		$this->db->join("contact_source as cs1","cs1.user_id=user.id AND cs1.contact_type='PRIMARY'");
		$this->db->join("contact as c1","cs1.contact_id=c1.id  AND c1.active=1");
		$this->db->join("contact_source as cs2","cs2.user_id=user.id AND cs2.contact_type='SECONDARY'",'left');
		$this->db->join("contact as c2","cs2.contact_id=c2.id  AND c2.active=1",'left');
		$this->db->join('user_location','user_location.user_id=user.id AND user_location.active=1','left');
		$this->db->join('locality','locality.id=user_location.locality_id AND locality.active=1','left');
		$this->db->join("district","locality.district_id=district.id AND district.active=1",'left');
		$this->db->join("state","state.id=district.state_id  AND state.active=1",'left');
		$this->db->join("country","country.id=state.country_id  AND country.active=1",'left');
		$this->db->join("photo_attach","photo_attach.attach_id=user.id AND photo_attach.attach_type='USER' AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		$this->db->from("user");
		$this->db->where('user.status','VERIFIED');
		$this->db->where('user.active',1);
		return $this->db->get()->result();
	}
	function createUserLocation($create_data){
		return $this->db->insert('user_location',$create_data);
	}
	function updateUserLocation($update_data, $user_location_id){
		$this->db->where('id',$user_location_id);
		return $this->db->update('user_location',$update_data);
	}
	function getUserLocation($user_id){
		$this->db->select("id, user_id, locality_id")->from("user_location");
		$this->db->where('active',1);
		/*$this->db->where('locality_id',$location_id);*/
		$this->db->where('user_id',$user_id);
		return $this->db->get()->first_row();
	}
	function verifyActivationCode($user_id, $code, $pending=false){
		if($code==''){
			return false;
		}
		$this->db->where('id',$user_id);
		$this->db->where('activation_key',$code);
		$query['row'] = $this->db->get('user')->first_row('array');
		if(!empty($query['row']) && $query['row']['status']=='NEW' && $query['row']['active']==0){
			$this->db->where('id',$user_id);
			$this->db->where('activation_key',$code);
			if($pending)
				$update_data=array('status'=>'VERIFIED','active'=>'1');
			else
				$update_data=array('status'=>'ACTIVE','active'=>'1');
			return $this->db->update('user', $update_data);
		}else if($query['row']['active']==1){
			return 'already';
		}else{
			return false;
		}
	}
	function activateOrganizationUser($user_id, $organization_id){
		$update_data=array('status'=>'ACTIVE','active'=>1,'updated_by'=>$user_id,'updation_date'=>date('Y-m-d H:i:s'));
		$this->db->where('id', $organization_id);
		$status=$this->db->update('user', $update_data);
		if($status){
			$organization_detail=$this->getUserInfo($organization_id);
			$data['mail']['username']=$organization_detail->username;
			$msg=$this->load->view('mail-template/organization-approval',$data,true);
			$email_address=$organization_detail->emailid;
			$subject='Alil Approved Your Organization Account';
			$this->alil_lib->sendEmailNotification($email_address, $subject, $msg);
		}
		return $status;
	}
	function getSkills(){
		$query=$this->db->select("id, name")->from("skills")->where('active',1)->get();
		return $query->result();
	}
	function getServices(){
		$query=$this->db->select("id, name")->from("services")->where('active',1)->get();
		return $query->result();
	}
	function getLocality(){
		$this->db->select("locality.id, locality.name,district.name as district_name, state.name as state_name, country.name as country_name")->from("locality");
		$this->db->join("district","locality.district_id=district.id AND district.active=1");
		$this->db->join("state","state.id=district.state_id  AND state.active=1");
		$this->db->join("country","country.id=state.country_id  AND country.active=1");
		$this->db->where('locality.active',1);
		$query=$this->db->get();
		return $query->result();
	}
	function getLocalityByStateID($state_id){
		$this->db->select("locality.id, locality.name,district.name as district_name, state.name as state_name, country.name as country_name")->from("locality");
		$this->db->join("district","locality.district_id=district.id AND district.active=1");
		$this->db->join("state","state.id=district.state_id  AND state.active=1");
		$this->db->join("country","country.id=state.country_id  AND country.active=1");
		$this->db->where('locality.active',1);
		$this->db->where('district.state_id',$state_id);
		$query=$this->db->get();
		return $query->result();
	}
	function getLocalityDetail($location_id){
		$this->db->select("locality.id, locality.name,district.name as district_name, state.name as state_name, country.name as country_name")->from("locality");
		$this->db->join("district","locality.district_id=district.id AND district.active=1");
		$this->db->join("state","state.id=district.state_id  AND state.active=1");
		$this->db->join("country","country.id=state.country_id  AND country.active=1");
		$this->db->where('locality.active',1);
		$this->db->where('locality.id',$location_id);
		$query=$this->db->get();
		return $query->first_row();
	}
	function getStates(){
		$query=$this->db->select("id, name")->from("state")->where('active',1)->get();
		return $query->result();
	}
	function getActivites(){
		$query=$this->db->select("id, name")->from("activities")->where('active',1)->get();
		return $query->result();
	}
	function getItems(){
		$query=$this->db->select("id, name")->from("items")->where('active',1)->get();
		return $query->result();
	}
	function getEventDomain(){
		$query=$this->db->select("id, name")->from("event_domain")->where('active',1)->get();
		return $query->result();
	}
	function saveImage($user_id, $uploaded_url, $ext, $info=''){
		$return = array();
		$return['status']=false;
		$this->db->select("photo_id")->from('photo_attach')->where('attach_id',$user_id);
		$photo=$this->db->where('attach_type','USER')->get();
		if($photo->num_rows()>0)
			$photo_id=$photo->first_row()->photo_id;
    
		if(isset($photo_id) && $photo_id ){
			$photo_data=array(
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
							'attach_id'=>$user_id,
							'attach_type'=>'USER',
							'cover'=>'1',
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
	function getUserDetailByhandles($user_handle){
		$this->db->select("user.id, user.username, user.status, user.activation_key, user.role, user.active, c1.emailid, 
						c1.primary_number, c1.age, c1.gender, c1.website, c1.address, c1.area,
						c1.education_level, c1.profession, c1.name as username, c2.name as secondary_contact_name, 
						c2.emailid as secondary_emailid, c2.secondary_number, c2.id as secondary_contact_id, 
						c1.id as contact_id, locality.name as location_name, locality.id as location_id,
						district.name as district_name, state.name as state_name, country.name as country_name,
						photo.url as profile_url
						");
		$this->db->from("user");
				
		$this->db->join("contact_source as cs1","cs1.user_id=user.id AND cs1.contact_type='PRIMARY'");
		$this->db->join("contact as c1","cs1.contact_id=c1.id  AND c1.active=1");
		$this->db->join("contact_source as cs2","cs2.user_id=user.id AND cs2.contact_type='SECONDARY'",'left');
		$this->db->join("contact as c2","cs2.contact_id=c2.id  AND c2.active=1",'left');
		$this->db->join('user_location','user_location.user_id=user.id AND user_location.active=1','left');
		$this->db->join('locality','locality.id=user_location.locality_id AND locality.active=1','left');
		$this->db->join("district","locality.district_id=district.id AND district.active=1",'left');
		$this->db->join("state","state.id=district.state_id  AND state.active=1",'left');
		$this->db->join("country","country.id=state.country_id  AND country.active=1",'left');
		
		$this->db->join("photo_attach","photo_attach.attach_id=user.id AND photo_attach.attach_type='USER' AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
		$this->db->where('user.active',1);
		$this->db->where('c1.handle',$user_handle);
		return $this->db->get()->first_row();
	}
	function createPhoto($user_id, $uploaded_url, $ext, $info=''){
		$photo_data=array(
					'type'=>strtolower($ext),
					'caption'=>$info,
					'url'=>$uploaded_url,
					'active'=>'1',
					'created_by'=>$user_id,
					'creation_date'=>date("Y-m-d H:i:s")
					);
		if($this->db->insert('photo', $photo_data)){
			$photo_id=$this->db->insert_id();
			$return['status']=true;
			$return['id']=$photo_id;
		}else{
			$return['status']=false;
		}
		return $return;
	}
	function deletePhoto($photo_id){
		return $this->db->update('photo',array('active'=>0), array('id' => $photo_id));
	}
}

?>
