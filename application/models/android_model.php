<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Android_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    
    public function login($username,$password) {
  		$this->db->query("SELECT id FROM tbl_admin WHERE 
  		(email='".$username."' OR username='".$username."') AND (password='".$password."') ");
		$query = $this->db->get("tbl_admin");
		if($query->num_rows()>0) 
		  return $query->row();
		else
			return false;
	}
	
	public function getUserData($id) {
  		$query  = $this->db->query("SELECT * FROM tbl_admin WHERE id='".$id."' ");
		
		if($query->num_rows()>0) 
		  return $query->row();
		else
			return false;
	} 
	
}