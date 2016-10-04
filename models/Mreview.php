<?php
class Mreview extends CI_Model{
    
	function __construct(){
		// Call the Model constructor
		parent::__construct();
	}
	function findReviewByRatingID($rating_id){
		$list=$this->db->select('*')->from('review')->where('rating_id',$rating_id)->get()->first_row('array');
		return $list;
	}
	function writeReview($rating_id, $content, $add_photo, $photo_id=""){
		$insert=0;
		$return=$this->findReviewByRatingID($rating_id);
		if($insert && $add_photo=='Yes'){
			$review_id=$this->db->insert_id();
			foreach($photo_id as $ids){
				$photo_attach=array(
					'photo_id' => $ids,
					'attach_id' => $review_id,
					'attach_type'=>2,
					'active'=>1,
					'created_by'=>1,
					'creation_date'=>date("Y-m-d H:i:s")
				);
			$this->db->insert('photo_attach',$photo_attach); 
			}
		}
		return $insert;
	}
	function findTotalPhotosByReviewID($review_ids){
		$list=$this->db->query("SELECT 
									a.photo_id,a.attach_id,b.image,b.type
								FROM 
									photo_attach a,
									photo b
								where 
									a.attach_id IN ('".$review_ids."') and
									a.attach_type='3' and
									a.photo_id=b.id and
									a.active='1'
									order by a.photo_id ASC
								")->result_array();
		return $list;
	}
	function getReviews($source_id, $source_type, $offset=0, $limit=1){
		$this->db->select("rw.*,c.name as user_name");
		$this->db->from("detail_review_v rw");
		$this->db->join("contact_source cs","cs.user_id=rw.user_id and cs.contact_type='PRIMARY'");
		$this->db->join("contact c","c.id=cs.contact_id");
		$this->db->where('rw.source_id',$source_id);
		$this->db->where('rw.source_type',$source_type);
		$this->db->where('rw.active',1);
		if($this->input->get('sort')){
			if($this->input->get('sort')=='ra')
				$this->db->order_by('points','asc');
			else if($this->input->get('sort')=='rd')
				$this->db->order_by('points','desc');
			else if($this->input->get('sort')=='da')
				$this->db->order_by('updation_date','asc');
			else if($this->input->get('sort')=='dd')
				$this->db->order_by('updation_date','desc');
		}
		else
			$this->db->order_by('updation_date','desc');
		
		$this->db->limit($limit,$offset);
		return $this->db->get()->result();
	} 
	function getReviewsCount($source_id, $source_type){
		$this->db->select("count(*) as count");
		$this->db->from("detail_review_v");
		$this->db->where('source_id',$source_id);
		$this->db->where('source_type',$source_type);
		$this->db->where('active',1);
		
		return $this->db->get()->first_row()->count;
	}
	function isAlreadyReviewed($user_id, $source_id, $source_type){
		$this->db->select("*");
		$this->db->from("detail_review_v");
		$this->db->where('source_id',$source_id);
		$this->db->where('source_type',$source_type);
		$this->db->where('user_id',$user_id);
		$this->db->where('active',1);
		return $this->db->get()->num_rows();
	}
	function getReviewIdByRatingId($rating_id){
		$review=$this->db->select("id")->from('review')->where('rating_id',$rating_id)->get()->first_row();
		if($review)
			return $review->id;
	}
	function getReviewByID($review_id){
		return $review=$this->db->select("*")->from('detail_review_v')->where('review_id',$review_id)->get()->first_row();
	}
	function getReviewByRatingID($rating_id){
		return $review=$this->db->select("*")->from('detail_review_v')->where('rating_id',$rating_id)->get()->first_row();
	}
	function createReview($user_id, $rating_id, $points, $content, $source_id, $source_type, $add_photo, $photo_id){
		
		$params=array($rating_id, $points, $content, $source_id, $source_type, $user_id);
		// call the stored procedure
		$this->db->query("CALL createReview(?,?,?,?,?,?,@x_result,@x_review_id)", $params);
		// get the stored procedure returned output
		$query = $this->db->query('SELECT @x_result AS result,@x_review_id as review_id');
		$row = $query->row();
		$result= $row->result;
		
		$return['status']=$row->result?true:false;
		if(isset($row->review_id))
			$return['id']=$row->review_id;
			
		if($result && $add_photo=='Yes'){
			$review_id=$row->review_id;;
			foreach($photo_id as $ids){
				$photo_attach=array(
					'photo_id' => $ids,
					'attach_id' => $review_id,
					'attach_type'=>'REVIEW',
					'active'=>1,
					'created_by'=>$user_id,
					'creation_date'=>date("Y-m-d H:i:s")
				);
				$this->db->insert('photo_attach',$photo_attach);
			}
			$review_photos=$this->getReviewsPhotos($review_id, "REVIEW");
			if(!empty($review_photos)){
				$this->db->where('review_id', $review_id);
				$this->db->update("detail_review_v",array('photos'=>json_encode($review_photos)));
			}
		}
		return $return;
	}
	function updateReview($user_id, $rating_id, $review_id, $content, $points, $source_id, $source_type, $photos){
		$params=array($rating_id, $review_id, $content, $points, $source_id, $source_type, $user_id);
		// call the stored procedure
		$this->db->query("CALL updateReview(?,?,?,?,?,?,?,@x_result)", $params);
		// get the stored procedure returned output
		$query = $this->db->query('SELECT @x_result AS result');
		$row = $query->row();
		$result=$row->result;
		$status=$row->result?true:false;
		if(!empty($photos)){
			foreach($photos as $ids){
				$data[] = array(
							'photo_id' => $ids,
							'attach_id' => $review_id,
							'attach_type' => '3',
							'updated_by' => '1',
							'updation_date' => date("Y-m-d H:i:s")
						);
			}
			$this->db->insert_batch('photo_attach', $data);   
		}
		return $status;
	}
	function deleteReview($user_id, $review_id, $rating_id, $source_id, $source_type){
		$photo=$this->findTotalPhotosByReviewID($review_id);
		if(!empty($photo)){
			foreach($photo as $image){
				$photo_id[]=$image['photo_id'];
			}
			$this->db->where_in('photo_id', $photo_id);
			$this->db->where('attach_type', "REVIEW");
			$this->db->delete('photo_attach');
			$this->db->where_in('id', $photo_id);
			$this->db->update('photo',array('active'=>0));
		}
		$params=array($review_id, $rating_id, $source_id, $source_type, $user_id);
		// call the stored procedure
		$this->db->query("CALL deleteReview(?,?,?,?,?,@x_result)", $params);
		// get the stored procedure returned output
		$query = $this->db->query('SELECT @x_result AS result');
		$row = $query->row();
		$result=$row->result;
		return $result;
	}
	function getReviewsPhotos($source_id, $source_type){
		$this->db->select("photo.id, photo.type, photo.url");
		$this->db->from("photo_attach");
		$this->db->join("photo","photo.id=photo_attach.photo_id AND photo.active=1");
		$this->db->where('photo_attach.attach_id',$source_id);
		$this->db->where('photo_attach.attach_type',$source_type);
		$this->db->where('photo_attach.active',1);
		return $this->db->get()->result();
	}
}

?>
