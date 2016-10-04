<?php
class Mevents extends CI_Model{
    
	function __construct(){
		parent::__construct();
	}
	function getEventsList($type='', $user_id=false, $user_type="" ,$limit=false){
		$this->db->select("events.id as event_id, events.event_name, events.short_name as event_short_name, events.type as event_type,events.description as event_description,
						events.importance as event_importance, events.need as event_need, events.status as event_status,
						locality.name as location_name, district.name as district_name, state.name as state_name, country.name as country_name,
						photo.url as event_cover_photo");
		$this->db->from("events");
		$this->db->join("locality","locality.id=events.location_id AND locality.active=1");
		$this->db->join("district","locality.district_id=district.id AND district.active=1");
		$this->db->join("state","state.id=district.state_id  AND state.active=1");
		$this->db->join("country","country.id=state.country_id  AND country.active=1");
		
		$this->db->join("photo_attach","photo_attach.attach_id=events.id AND photo_attach.attach_type='EVENT' AND photo_attach.cover=1 AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
 		$this->db->where("events.active",1);
		if($type=='completed')
			$this->db->where("events.status",'COMPLETEALL');
		else if($type=='ongoing')
			$this->db->where("events.status",'ONGOING');
		else if($type=='planning')
			$this->db->where("events.status",'PLANNING');
		if($user_id && $user_type!='VOLUNTEER'){
			$this->db->where("events.user_id",$user_id);
		}
		$this->db->order_by("events.id","desc");
		if($limit){
			 $this->db->limit($limit);
		}
		return $this->db->get()->result();
	}
	function getMyVolunteerDashboard($type='', $user_id, $user_type="" ,$limit=false){
		$user_skills = '';
		$user_location = '';
		if($user_type=='VOLUNTEER' && $type=='skills' &&  $user_id){
			$this->load->model('mvolunteer');
			$skills=$this->mvolunteer->getVolunteerSkills($user_id);
			$user_skills = array_map(function($user){ return $user->skill_id; } , $skills);
			$user_skills=implode("','",$user_skills);
			$user_skills="AND volunteer_needs.skill_id IN ('".$user_skills."')";
		}else if($user_type=='VOLUNTEER' && $type=='locality' && $user_id){
			$this->load->model('mgeneral');
			$user_location=$this->mgeneral->getUserLocation($user_id);
			$user_location=isset($user_location->locality_id)?$user_location->locality_id:null;
		}
		
		$this->db->select("events.id as event_id, events.event_name, events.short_name as event_short_name, events.type as event_type,events.description as event_description,
						events.importance as event_importance, events.need as event_need, events.status as event_status,
						locality.name as location_name, district.name as district_name, state.name as state_name, 
						country.name as country_name, photo.url as event_cover_photo");
		$this->db->from("events");
		
		$this->db->join("photo_attach","photo_attach.attach_id=events.id AND photo_attach.attach_type='EVENT' AND photo_attach.cover=1 AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
		if($type=='skills'){
			$this->db->join("event_volunteers_need as volunteer_needs","volunteer_needs.event_id=events.id ".$user_skills." AND volunteer_needs.active=1");
		}else{
			$this->db->join("event_volunteers_need as volunteer_needs","volunteer_needs.event_id=events.id AND volunteer_needs.active=1",'left');
		}
		$this->db->join("event_volunteers","event_volunteers.event_id=events.id AND event_volunteers.active=1",'left');
		
		$this->db->join("locality","locality.id=events.location_id AND locality.active=1");
		$this->db->join("district","locality.district_id=district.id AND district.active=1");
		$this->db->join("state","state.id=district.state_id  AND state.active=1");
		$this->db->join("country","country.id=state.country_id  AND country.active=1");
 		$this->db->where("events.active",1);
		if($type=='completed')
			$this->db->where("events.status",'COMPLETEALL');
		else if($type=='ongoing')
			$this->db->where("events.status",'ONGOING');
		else if($type=='planning')
			$this->db->where("events.status",'PLANNING');
		
		if($type=='locality' && $user_location!='')
			$this->db->where("events.location_id",$user_location);
		else
			$this->db->where("event_volunteers.volunteer_id",$user_id);
		
		$this->db->order_by("events.id","desc");
		$this->db->group_by("volunteer_needs.event_id");
		if($limit){
			 $this->db->limit($limit);
		}
		return $this->db->get()->result();
	}
	function getEventsListBySourceID($source_ids, $limit=false){
		$this->db->select("events.id as event_id, events.event_name, events.short_name as event_short_name, events.type as event_type,events.description as event_description,
						events.importance as event_importance, events.need as event_need, events.status as event_status,
						locality.name as location_name, district.name as district_name, state.name as state_name, country.name as country_name,
						photo.url as event_cover_photo");
		$this->db->from("events");
		$this->db->join("locality","locality.id=events.location_id AND locality.active=1");
		$this->db->join("district","locality.district_id=district.id AND district.active=1");
		$this->db->join("state","state.id=district.state_id  AND state.active=1");
		$this->db->join("country","country.id=state.country_id  AND country.active=1");
		
		$this->db->join("photo_attach","photo_attach.attach_id=events.id AND photo_attach.attach_type='EVENT' AND photo_attach.cover=1 AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
 		$this->db->where("events.active",1);
		if(!empty($source_ids)){
			foreach($source_ids as $source_type=>$source_id){
				if($source_type=='location'){
					$this->db->where("events.location_id",$source_id);
				}else if($source_type=='domain'){
					$this->db->where("events.event_domain_id",$source_id);
				}else if($source_type=='type'){
					$this->db->where("events.type",$source_id);
				}
			}
		}
		$this->db->order_by("events.id","desc");
		if($limit){
			 $this->db->limit($limit);
		}
		return $this->db->get()->result();
	}
	function getEventDetail($event_short_name){
		$this->db->select("events.id as event_id, events.event_name, events.short_name as event_short_name, events.type as event_type,
						events.description as event_description, events.importance as event_importance, events.need as event_need, 
						events.status as event_status, events.location_id, events.event_domain_id, schedule.start_time, schedule.end_time, 
						items_need.item_id, volunteers_need.skill_id,
						schedule.type as schedule_type, locality.name as location_name, district.name as district_name, 
						state.name as state_name, country.name as country_name");
		$this->db->from("events");
		$this->db->join("event_schedule as schedule","schedule.event_id=events.id AND schedule.active=1","left");
		$this->db->join("event_domain","event_domain.id=events.event_domain_id AND event_domain.active=1","left");
		$this->db->join("event_items_need as items_need","items_need.event_id=events.id AND items_need.active=1","left");
		$this->db->join("items","items.id=items_need.item_id AND items.active=1","left");
		$this->db->join("event_volunteers_need as volunteers_need","volunteers_need.event_id=events.id AND volunteers_need.active=1","left");
		$this->db->join("skills","skills.id=volunteers_need.skill_id AND skills.active=1","left");
		$this->db->join("locality","locality.id=events.location_id AND locality.active=1","left");
		$this->db->join("district","locality.district_id=district.id AND district.active=1","left");
		$this->db->join("state","state.id=district.state_id  AND state.active=1","left");
		$this->db->join("country","country.id=state.country_id  AND country.active=1","left");
		$this->db->where("events.active",1);
		$this->db->where("events.short_name",$event_short_name);
		$this->db->order_by("events.id","desc");
		return $this->db->get()->first_row();
	}
	function getEventDetailByID($event_id, $inactive=false){
		$this->db->select("events.id as event_id, events.event_name, events.short_name as event_short_name, events.type as event_type,
						events.description as event_description, events.importance as event_importance, events.need as event_need, 
						events.status as event_status, events.location_id, events.event_domain_id, schedule.start_time, schedule.end_time, 
						items_need.id as items_need_id, items_need.item_id, volunteers_need.skill_id, skills.name as skill_name, 
						items.name as item_name, volunteers_need.id as volunteers_need_id, repetitive_events.id as repetitive_events_id,
						repetitive_events.repetitive, repetitive_events.repeat_on, repetitive_events.start_date, 
						repetitive_events.end_type, repetitive_events.completion, 
						schedule.id as event_schedule_id,schedule.type as schedule_type, locality.name as location_name, district.name as district_name,
						contact.name as user_name, contact.emailid as email_address, contact_source.user_id,
						state.name as state_name, country.name as country_name");
		$this->db->from("events");
		
		$this->db->join('contact_source','contact_source.user_id=events.user_id AND contact_source.contact_type="PRIMARY" AND contact_source.active=1','left');
		$this->db->join('contact','contact_source.contact_id=contact.id AND contact.active=1','left');
		$this->db->join("event_schedule as schedule","schedule.event_id=events.id AND schedule.active=1","left");
		// $this->db->join("event_domain","event_domain.id=events.event_domain_id AND event_domain.active=1","left");
		$this->db->join("event_items_need as items_need","items_need.event_id=events.id AND items_need.active=1","left");
		$this->db->join("items","items.id=items_need.item_id AND items.active=1","left");
		$this->db->join("event_volunteers_need as volunteers_need","volunteers_need.event_id=events.id AND volunteers_need.active=1","left");
		$this->db->join("repetitive_events","repetitive_events.event_id=events.id AND repetitive_events.active=1","left");
		$this->db->join("skills","skills.id=volunteers_need.skill_id AND skills.active=1","left");
		$this->db->join("locality","locality.id=events.location_id AND locality.active=1","left");
		$this->db->join("district","locality.district_id=district.id AND district.active=1","left");
		$this->db->join("state","state.id=district.state_id  AND state.active=1","left");
		$this->db->join("country","country.id=state.country_id  AND country.active=1","left");
		if(!$inactive)
			$this->db->where("events.active",1);
		$this->db->where("events.id",$event_id);
		$this->db->order_by("events.id","desc");
		$this->db->group_by("events.id");
		return $this->db->get()->first_row();
	}
	function getEventSkills($event_id){
		$this->db->select('volunteers_need.id, volunteers_need.event_id, volunteers_need.skill_id, skills.name as skill_name');
		$this->db->from('event_volunteers_need as volunteers_need');
		$this->db->join("skills","skills.id=volunteers_need.skill_id AND skills.active=1","left");
		$this->db->where('volunteers_need.event_id', $event_id);
		$this->db->where('volunteers_need.active', 1);
		return $this->db->get()->result();
	}
	function getEventItems($event_id){
		$this->db->select('items_need.id, items_need.event_id, items_need.item_id, items.name as item_name');
		$this->db->from('event_items_need as items_need');
		$this->db->join("items","items.id=items_need.item_id AND items.active=1","left");
		$this->db->where('items_need.event_id', $event_id);
		$this->db->where('items_need.active', 1);
		return $this->db->get()->result();
	}
	function subscribeEvent($user_id, $event_id){
		$event_volunteers=$this->checkEventSubscribe($user_id, $event_id);
		if(empty($event_volunteers)){
			$create_data = array(
			   'event_id' => $event_id,
			   'volunteer_id' =>  $user_id,
			   'active'=> 1,
			   'created_by'=>$user_id,
			   'creation_date'=>date('Y-m-d H:i:s')
			);
			if($this->db->insert('event_volunteers',$create_data))
				return $this->db->insert_id();
			else 
				return false;
		}else{
			return $event_volunteers->id;
		}
	}
	function checkEventSubscribe($user_id, $event_id){
		$this->db->select("id, event_id, volunteer_id");
		$this->db->from("event_volunteers");
		$this->db->where('event_id',$event_id);
		$this->db->where('volunteer_id',$user_id);
		$this->db->where('active',1);
		return $this->db->get()->first_row();
	}
	function eventVolunteers($event_id){
		$this->db->select("event_volunteers.id, event_volunteers.event_id, event_volunteers.volunteer_id as user_id, 
							locality.name as location_name, contact.name as user_name, contact.handle as user_handle,
							photo.url as user_cover_photo");
		$this->db->from("event_volunteers");
		$this->db->join('user','user.id=event_volunteers.volunteer_id AND user.active=1');
		$this->db->join('user_location','user_location.user_id=user.id AND user_location.active=1','left');
		$this->db->join('locality','locality.id=user_location.locality_id AND locality.active=1','left');
		$this->db->join('contact_source','contact_source.user_id=user.id AND contact_source.contact_type="PRIMARY" AND contact_source.active=1');
		$this->db->join('contact','contact_source.contact_id=contact.id AND contact.active=1');
		
		$this->db->join("photo_attach","photo_attach.attach_id=user.id AND photo_attach.attach_type='USER' AND photo_attach.cover=1 AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
		$this->db->where('event_volunteers.event_id',$event_id);
		$this->db->where('event_volunteers.active',1);
		return $this->db->get()->result();
	}
	function taskVolunteers($event_task_id){
		$this->db->select("volunteer_map.id, volunteer_map.volunteer_id as user_id, 
							locality.name as location_name, contact.name as user_name, contact.handle as user_handle,
							photo.url as user_cover_photo");
		$this->db->from("event_task_volunteer_map as volunteer_map");
		$this->db->join('user','user.id=volunteer_map.volunteer_id AND user.active=1');
		$this->db->join('user_location','user_location.user_id=user.id AND user_location.active=1','left');
		$this->db->join('locality','locality.id=user_location.locality_id AND locality.active=1','left');
		$this->db->join('contact_source','contact_source.user_id=user.id AND contact_source.contact_type="PRIMARY" AND contact_source.active=1');
		$this->db->join('contact','contact_source.contact_id=contact.id AND contact.active=1');
		
		$this->db->join("photo_attach","photo_attach.attach_id=user.id AND photo_attach.attach_type='USER' AND photo_attach.cover=1 AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
		$this->db->where('volunteer_map.event_task_id',$event_task_id);
		$this->db->where('volunteer_map.active',1);
		return $this->db->get()->result();
	}
	function createEventTask($event_id, $task_name, $task_start_date, $task_end_date, $estimate_hours, $hour_spent,
							$status, $percentage, $user_id){
		$create_data = array(
		   'event_id' => $event_id,
		   'task_name' =>  $task_name,
		   'start_date' =>  $task_start_date,
		   'end_date' =>  $task_end_date,
		   'estimated_hours' =>  $estimate_hours,
		   'hours_spent' =>  $hour_spent,
		   'status' =>  $status,
		   'percentage' =>  $percentage,
		   'active'=> 1,
		   'created_by'=>$user_id,
		   'creation_date'=>date('Y-m-d H:i:s')
		);
		if($this->db->insert('event_tasks',$create_data))
			return $this->db->insert_id();
		else 
			return false;
	}
	function updateEventTask($event_task_id, $task_name, $task_start_date, $task_end_date, $estimate_hours, $hour_spent,
							$status, $percentage, $user_id){
		$update_data=array();
		$update_data = array(
		   'updated_by'=>$user_id,
		   'updation_date'=>date('Y-m-d H:i:s')
		);
		if($task_name)
			$update_data['task_name']= $task_name;
		
		if($task_start_date)
			$update_data['start_date']= $task_start_date;
		if($task_end_date)
			$update_data['end_date']= $task_end_date;
		if($estimate_hours)
			$update_data['estimated_hours']= $estimate_hours;
		if($hour_spent)
			$update_data['hours_spent']= $hour_spent;
		if($percentage){
			$update_data['percentage']=$percentage;
		}
		if($status){
			$update_data['status']=$status;
		}
		$this->db->where('id',$event_task_id);
		if($this->db->update('event_tasks', $update_data))
			return true;
		else 
			return false;
	}
	
	function getEventTaskList($event_id){
		$this->db->select('event_tasks.id, event_tasks.event_id, event_tasks.task_name, event_tasks.main_task_id, event_tasks.start_date,
						event_tasks.end_date, event_tasks.estimated_hours, event_tasks.hours_spent, event_tasks.status,
						event_tasks.percentage,
						GROUP_CONCAT(DISTINCT contact_source.user_id ORDER BY contact.id ASC SEPARATOR ",") as volunteer_ids,
						GROUP_CONCAT(DISTINCT contact.name ORDER BY contact.id ASC SEPARATOR ",") as volunteer_names,
						GROUP_CONCAT(DISTINCT contact.handle ORDER BY contact.id ASC SEPARATOR ",") as volunteer_handles,
						');
		$this->db->from('event_tasks');
		$this->db->join('event_task_volunteer_map as volunteer_map','event_tasks.id=volunteer_map.event_task_id AND volunteer_map.active=1','left');
		$this->db->join('user','volunteer_map.volunteer_id=user.id AND volunteer_map.active=1','left');
		$this->db->join('contact_source','contact_source.user_id=user.id AND contact_source.contact_type="PRIMARY" AND contact_source.active=1','left');
		$this->db->join('contact','contact_source.contact_id=contact.id AND contact.active=1','left');
		$this->db->where('event_tasks.event_id',$event_id);
		$this->db->where('event_tasks.active',1);
		$this->db->group_by('event_tasks.id');
		return $this->db->get()->result();
	}
	function getEventTaskVolunteerMap($event_task_id){
		$this->db->select('user.id as user_id, contact.name as user_name');
		$this->db->from('event_task_volunteer_map as volunteer_map');
		$this->db->join('user','volunteer_map.volunteer_id=user.id AND volunteer_map.active=1');
		$this->db->join('contact_source','contact_source.user_id=user.id AND contact_source.contact_type="PRIMARY" AND contact_source.active=1');
		$this->db->join('contact','contact_source.contact_id=contact.id AND contact.active=1');
		$this->db->where('volunteer_map.event_task_id',$event_task_id);
		$this->db->where('volunteer_map.active',1);
		return $this->db->result();
	}
	function getEventIndividualTask($task_id){
		$this->db->select('event_tasks.id, event_tasks.event_id, event_tasks.task_name, event_tasks.main_task_id, event_tasks.start_date,
						event_tasks.end_date, event_tasks.estimated_hours, event_tasks.hours_spent, event_tasks.status,
						event_tasks.percentage');
		$this->db->from('event_tasks');
		$this->db->where('event_tasks.id',$task_id);
		$this->db->where('event_tasks.active',1);
		return $this->db->get()->first_row();
	}
	function getEventIndividualSubTask($task_id){
		$this->db->select('event_tasks.id, event_tasks.event_id, event_tasks.task_name, event_tasks.main_task_id, event_tasks.start_date,
						event_tasks.end_date, event_tasks.estimated_hours, event_tasks.hours_spent, event_tasks.status,
						event_tasks.percentage, tasks.task_name as main_task_name, events.event_name, 
						events.short_name as event_short_name, events.id as event_id, events.user_id as event_user_id, 
						GROUP_CONCAT(DISTINCT locality.name ORDER BY contact.id ASC SEPARATOR ",") as volunteer_locations,
						GROUP_CONCAT(DISTINCT contact_source.user_id ORDER BY contact.id ASC SEPARATOR ",") as volunteer_ids,
						GROUP_CONCAT(DISTINCT contact.name ORDER BY contact.id ASC SEPARATOR ",") as volunteer_names,
						');
		$this->db->from('event_tasks');
		$this->db->join('event_tasks as tasks','tasks.id=event_tasks.main_task_id AND tasks.active=1');
		$this->db->join('events','events.id=tasks.event_id AND events.active=1');
		$this->db->join('event_task_volunteer_map as volunteer_map','event_tasks.id=volunteer_map.event_task_id AND volunteer_map.active=1','left');
		$this->db->join('user','volunteer_map.volunteer_id=user.id AND volunteer_map.active=1','left');
		$this->db->join('user_location','user_location.user_id=user.id AND user_location.active=1','left');
		$this->db->join('locality','locality.id=user_location.locality_id AND locality.active=1','left');
		$this->db->join('contact_source','contact_source.user_id=user.id AND contact_source.contact_type="PRIMARY" AND contact_source.active=1','left');
		$this->db->join('contact','contact_source.contact_id=contact.id AND contact.active=1','left');
		$this->db->where('event_tasks.id',$task_id);
		$this->db->where('event_tasks.active',1);
		return $this->db->get()->first_row();
	}
	function createEventSubTask($event_id, $task_id, $task_name, $task_start_date, $task_end_date, $estimate_hours, $hour_spent,
								$status, $percentage, $user_id){
		$create_data = array(
		   'event_id' => $event_id,
		   'main_task_id' => $task_id,
		   'task_name' =>  $task_name,
		   'start_date' =>  $task_start_date,
		   'end_date' =>  $task_end_date,
		   'estimated_hours' =>  $estimate_hours,
		   'hours_spent' =>  $hour_spent,
		   'status' =>  $status,
		   'percentage' =>  $percentage,
		   'active'=> 1,
		   'created_by'=>$user_id,
		   'creation_date'=>date('Y-m-d H:i:s')
		);
		if($this->db->insert('event_tasks',$create_data))
			return $this->db->insert_id();
		else 
			return false;
	}
	function createEventTaskVolunteerMap($volunteer_maps){
		$status=false;
		if(!empty($volunteer_maps)){
			foreach($volunteer_maps as $maps){
				$volunteer_map=$this->getEventTaskIndividualVolunteerMap($maps['event_task_id'], $maps['volunteer_id']);
				if(empty($volunteer_map)){
					$status=$this->db->insert('event_task_volunteer_map',$maps);
					if(!$status)
						break;
				}else if(!empty($volunteer_map)){
					$status = $this->activeVolunteer($maps['event_task_id'], $maps['volunteer_id'], $maps['created_by']);
				}
			}
		}
		if($status)
			return true;
		else 
			return false;
	}
	function getEventTaskIndividualVolunteerMap($event_task_id, $volunteer_id){
		$this->db->select("id, event_task_id, volunteer_id");
		$this->db->from("event_task_volunteer_map");
		$this->db->where('event_task_id',$event_task_id);
		$this->db->where('volunteer_id',$volunteer_id);
		return $this->db->get()->first_row();
	}
	function activeVolunteer($event_task_id, $volunteer_id, $user_id){
		$data=array(
					'active'=>1,
					'updated_by'=> $user_id,
					'updation_date'=>date('Y-m-d H:i:s')
				);
		$this->db->where('event_task_id',$event_task_id);
		$this->db->where('volunteer_id',$volunteer_id);
		return $this->db->update('event_task_volunteer_map', $data);
	}
	function approveEventTask($event_task_id, $user_id){
		$data=array(
					'status'=>'APPROVED',
					'active'=>1,
					'updated_by'=> $user_id,
					'updation_date'=>date('Y-m-d H:i:s')
				);
		$this->db->where('id',$event_task_id);
		return $this->db->update('event_tasks', $data);
	}
	function removeEventTask($event_task_id, $user_id, $sub_task=false){
		$data=array(
					'active'=>0,
					'updated_by'=> $user_id,
					'updation_date'=>date('Y-m-d H:i:s')
				);
		$this->db->where('id',$event_task_id);
		$status = $this->db->update('event_tasks', $data);
		if($status && $sub_task){
			$this->db->where('main_task_id',$event_task_id);
			return $this->db->update('event_tasks', $data);
		}
		return $status;
	}
	function removeVolunteer($event_task_id, $volunteer_id, $user_id){
		$data=array(
					'active'=>0,
					'updated_by'=> $user_id,
					'updation_date'=>date('Y-m-d H:i:s')
				);
		$this->db->where('event_task_id',$event_task_id);
		$this->db->where('volunteer_id',$volunteer_id);
		return $this->db->update('event_task_volunteer_map', $data);
	}
	function volunteerSubscribe($volunteer_maps, $event_task_id, $volunteer_id, $activation){
		if($activation){
			return $this->createEventTaskVolunteerMap($volunteer_maps);
		}else{
			$data=array(
						'active'=>0,
						'updated_by'=> $volunteer_id,
						'updation_date'=>date('Y-m-d H:i:s')
					);
			$this->db->where('event_task_id',$event_task_id);
			$this->db->where('volunteer_id',$volunteer_id);
			return $this->db->update('event_task_volunteer_map', $data);
		}
		return false;
	}
	function getReleatedEventsList($event_type, $event_location, $event_id, $limit=false){
		$this->db->select("events.id as event_id, events.event_name, events.short_name as event_short_name, events.type as event_type,events.description as event_description,
						events.importance as event_importance, events.need as event_need, events.status as event_status,
						locality.name as location_name, district.name as district_name, state.name as state_name, country.name as country_name,
						photo.url as event_cover_photo");
		$this->db->from("events");
		$this->db->join("locality","locality.id=events.location_id AND locality.active=1");
		$this->db->join("district","locality.district_id=district.id AND district.active=1");
		$this->db->join("state","state.id=district.state_id  AND state.active=1");
		$this->db->join("country","country.id=state.country_id  AND country.active=1");
		
		$this->db->join("photo_attach","photo_attach.attach_id=events.id AND photo_attach.attach_type='EVENT' AND photo_attach.cover=1 AND photo_attach.active=1",'left');
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1",'left');
		
		$where="( events.type='".$event_type."' OR events.location_id='".$event_location."' )";
 		$this->db->where($where);
		
		$this->db->where("events.id!=",$event_id);
 		$this->db->where("events.active",1);
		
		
 		
		$this->db->where("events.status",'ONGOING');
		$this->db->order_by("events.id","desc");
		if($limit){
			 $this->db->limit($limit);
		}
		return $this->db->get()->result();
	}
	function getEventPhotos($event_id){
		$this->db->select("photo_attach.photo_id, photo_attach.attach_id, photo_attach.attach_type, photo_attach.cover, photo.url");
		$this->db->from("photo_attach");
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1");
		$this->db->where("photo_attach.attach_id",$event_id);
 		$this->db->where("photo_attach.attach_type","EVENT");
 		$this->db->where("photo_attach.active",1);
		return $this->db->get()->result();
	}
	function getEventCoverPhoto($event_id){
		$this->db->select("photo_attach.photo_id, photo_attach.attach_id, photo_attach.attach_type, photo_attach.cover, photo.url");
		$this->db->from("photo_attach");
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1");
		$this->db->where("photo_attach.attach_id",$event_id);
 		$this->db->where("photo_attach.attach_type","EVENT");
 		$this->db->where("photo_attach.cover",1);
 		$this->db->where("photo_attach.active",1);
		return $this->db->get()->first_row();
	}
	function removePhotos($photo_id, $user_id){
		$this->db->where('id', $photo_id);
		return $this->db->update('photo',array('active'=>0,'updated_by'=>$user_id,'updation_date'=>date("Y-m-d H:i:s")));
	}
}

?>
