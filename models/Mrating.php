<?php
class Mrating extends CI_Model{
    
	function __construct(){
		// Call the Model constructor
		parent::__construct();
		$this->_rating_desc=array(
								'1'=>'Avoid',
								'1.5'=>'Very Bad',
								'2'=>'Bad',
								'2.5'=>'Average',
								'3'=>'OK',
								'3.5'=>'Good',
								'4'=>'Very Good',
								'4.5'=>'Best',
								'5'=>'Excellent'
                            );
	}
	function isAlreadyRated($source_id,$user_id,$source_type){
		$this->db->select("id,points");
		$this->db->from("rating");
		$this->db->where('source_id',$source_id);
		$this->db->where('user_id',$user_id);
		$this->db->where('source_type',$source_type);
		return $this->db->get()->first_row();
	}
	function getRating($source_id, $source_type){
		$this->db->select("source_id,points,votes,reviews");
		$this->db->from("rating_review_v");
		$this->db->where('source_id',$source_id);
		$this->db->where('source_type',$source_type);
		return $this->db->get()->first_row();
	}
	function getUserRating($source_id, $user_id, $source_type){
		$this->db->select("id, points");
		$this->db->from("rating");
		$this->db->where('source_id',$source_id);
		$this->db->where('source_type',$source_type);
		$this->db->where('user_id',$user_id);
		$this->db->where('active',1);
		return $this->db->get()->first_row();
	}
	function getRatingDescription($score){
		if($score>=1 && $score<1.5)
			$score=1;
		else if($score<2)
			$score=1.5;
		else if($score<2.5)
			$score=2;
		else if($score<3)
			$score=2.5;
		else if($score<3.5)
			$score=3;
		else if($score<4)
			$score=3.5; 
		else if($score<4.5)
			$score=4;
		else if($score<5)
			$score=4.5;
		else
			$score=5;
		return $this->_rating_desc[$score];      
	}
	function createRating($user_id, $source_id, $rating, $source_type){
		try{
			$params=array($rating, $source_id, $source_type, $user_id);
			$this->db->query("CALL createRating(?,?,?,?,@x_result,@x_rating_id)", $params);
			  // get the stored procedure returned output
			$query = $this->db->query('SELECT @x_result AS result,@x_rating_id as rating_id');
			$row = $query->row();
			$return['status']=$row->result?true:false;
			if(isset($row->rating_id))
				$return['id']=$row->rating_id;
			return $return;
		}
		catch ( Exception $error_string ){
			return array('status'=>false);
		}
	}
	function updateRating($user_id, $source_id, $rating, $rating_id, $source_type){
		try{
			$params=array($rating_id, $rating, $source_id, $source_type, $user_id);
			// call the stored procedure
			$this->db->query("CALL updateRating(?,?,?,?,?,@x_result)", $params);
			
			$query = $this->db->query('SELECT @x_result AS result');
			$row = $query->row();
			$return['status']=$row->result?true:false;
			$return['id']=$rating_id;
			return $return;
		}
		catch ( Exception $error_string ){
			return array('status'=>false);
		}
	}
	function deleteRating($user_id, $source_id, $rating, $rating_id, $source_type){
		try{
			$params=array($rating_id, $source_id, $source_type, $user_id);
			// call the stored procedure
			$this->db->query("CALL deleteRating(?,?,?,?,@x_result)", $params);
			// get the stored procedure returned output
			$query = $this->db->query('SELECT @x_result AS result');
			$row = $query->row();
			$return['status']=$row->result?true:false;
			$return['id']=$rating_id;
			return $return;
		}
		catch ( Exception $error_string ){
			return array('status'=>false);
		}
	}
}

?>
