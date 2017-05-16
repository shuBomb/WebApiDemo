<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_model extends CI_Model {

public function __construct() {
        $this->load->database();
    }
    
        public function check_login_credencial($email,$password) { 
		
		$this->db->where("email",$email);
		$this->db->where("password",$password);
		$this->db->select("id,username,email");
		$query = $this->db->get("tbl_admin");
		if($query->num_rows()>0) {
			$result =  $query->row();
			$data['status'] = "true";
			$data['message'] = "Success";
			$data['id'] = $result->id;
			$data['username'] = $result->username;
			$data['email'] = $result->email;
			
			return $data;
		 }
        else{
		   return false;
        }
 
    }
    
     public function total_banner_counts(){
        $this->db->where("isDelete",0);
        $this->db->where("status",1);
		$this->db->select("ads_id_PK");
		$query = $this->db->get("tbl_ads");
		return $query->num_rows();
     }
     
     
      /***********************************************************
	@ Function Name: get_user_data
  	@ Purpose      : Function for get user data
  	@ Created Date : 22/Aug/2014
  	/**********************************************************/	
		public function get_banner_data($limit,$start){ 
				$this->db->select("ads_id_PK,campaign_name,ad_picture,url,number_click,number_views,status");
				$this->db->where("isDelete","0");
				$this->db->order_by("created_date","desc");
				$this->db->limit($limit, $start);
				$query = $this->db->get("tbl_ads");
				// return $this->db->last_query();
				if($query->num_rows()==0)
				return false;
				else{
					foreach($query->result() as $row){
					    $data['ads_id_PK'] = $row->ads_id_PK;
						$data['campaign_name'] = $row->campaign_name;
            			if($row->ad_picture==""){
				 			$image = "defaultimage.png";
						}else{
				  			$image = $row->ad_picture;
						}
						$data['ad_picture'] = base_url().'upload/'.$image;
            			$data['url'] = $row->url;
            			$data['number_click'] = $row->number_click;
            			$data['number_views'] = $row->number_views;
            			$data['status'] = $row->status;
            			$final[] = $data;
            		}
            		return $final;
				 }				 
	}
	
	
	public function getbannerdata($getid){
   		$this->db->where("isDelete","0");
   		$this->db->where("ads_id_PK",$getid);
		$this->db->select("ads_id_PK,campaign_name,ad_picture,url,number_click,number_views,status");
		$query = $this->db->get("tbl_ads");
		 if($query->num_rows()>0) {
		 	$getdata = $query->row();
		 		$data['ads_id_PK'] = $getdata->ads_id_PK;
				$data['campaign_name'] = $getdata->campaign_name;
				$data['url'] = $getdata->url;
				if($getdata->ad_picture==""){
				 $image = "defaultimage.png";
				}else{
				  $image = $getdata->ad_picture;
				}
				$data['ad_picture'] = base_url().'upload/'.$image;
				$data['ads_picture_name'] = $image;
				
				$data['number_click'] = $getdata->number_click;
				$data['number_views'] = $getdata->number_views;
				$data['getstatus'] = $getdata->status;
			return $data;
		}
		  return false;
  } 
  
  
    public function getconfiguration(){ 
      	$this->db->select("config_id_PK,no_of_ads,shuffle_interval");
      	$this->db->limit(1);
      	$this->db->order_by("config_id_PK","DESC");
		$query = $this->db->get("tbl_config");
		 if($query->num_rows()>0) {
		 	$getdata = $query->row();
		 	    $data['config_id_PK'] = $getdata->config_id_PK;
		 		$data['no_of_ads'] = $getdata->no_of_ads;
				$data['shuffle_interval'] = $getdata->shuffle_interval;
			return $data;
		}
		  return false;
    }
    
    
    public function search_banner_counts($search_key){ 
     	if($search_key!="" OR $search_key!=0){ 
     			$this->db->like('campaign_name', $search_key);
     		}
			$this->db->select('campaign_name');
			$query = $this->db->get("tbl_ads");
			return count($query->result());
    }
    
    
    public function search_banner_data($search_key,$limit,$start){
			$this->db->select("ads_id_PK,campaign_name,url,ad_picture,url,number_click,number_views,status");
			$this->db->limit($limit, $start);
			$this->db->order_by("created_date","desc");
			if($search_key!="" OR $search_key!=0){ 
			 		$this->db->like('campaign_name', $search_key);
     		}
			$this->db->where("isDelete","0");
			$query = $this->db->get("tbl_ads");
			//echo $this->db->last_query();
			if($query->num_rows()==0)
				return false;
			else{
				foreach($query->result() as $row){
			   		$data['ads_id_PK'] = $row->ads_id_PK;
            		$data['campaign_name'] = $row->campaign_name;
            		if($row->ad_picture==""){
				 		$image = "defaultimage.png";
					}else{
				  		$image = $row->ad_picture;
					}
					$data['ad_picture'] = base_url().'upload/'.$image;
            		$data['number_click'] = $row->number_click;
            		$data['number_views'] = $row->number_views;
            		$data['status'] = $row->status;
            		$data['url'] = $row->url;
            		
            		$final[] = $data;
            }
            return $final;
		 }	
	}
	
	 public function getData(){ 
    	$this->db->select("no_of_ads,shuffle_interval");
    	$getquery = $this->db->get("tbl_config");
    	if($getquery->num_rows()>0) {
    	   $getdata = $getquery->row();
    	   $number_of_ads = $getdata->no_of_ads;
    	   $shuffle_interval = $getdata->shuffle_interval;
    	   $query = $this->db->query("SELECT ads_id_PK,campaign_name,ad_picture,url,number_views FROM tbl_ads 
    	   WHERE isDelete=0 AND status=1 ORDER BY RAND() LIMIT ".$number_of_ads."");
    	   if($query->num_rows()>0) {
    	   		foreach($query->result() as $row){
    	   		    $number_views = $row->number_views;
    	   		    $filter['ads_id_PK'] = $row->ads_id_PK;
    	   		    $set['number_views'] = $number_views+1;
    	   		    $update = $this->general_model->update('tbl_ads',$filter,$set);
    	   		    $data['ads_id_PK'] = $row->ads_id_PK;
					$data['campaign_name'] = $row->campaign_name;
					$data['shuffle_interval'] = $shuffle_interval;
					if($row->ad_picture==""){
				 		$image = "defaultimage.png";
					}else{
				  		$image = $row->ad_picture;
					}
					$data['ad_picture'] = base_url().'upload/'.$image;
					$data['url'] = $row->url;
					$final[] = $data;
				}
				return $final;
			}else{
			  return false;
			}
		 }
        else{
		   return [];
        }
    }
    
    
    
    public function getStreetData(){ 
    	$this->db->select("*");
    	$getquery = $this->db->get("tbl_street");
    	if($getquery->num_rows()>0) {
    	   		foreach($getquery->result() as $row){
    	   		    $data['num_rows'] = $getquery->num_rows();
    	   		    $data['id'] = $row->id;
    	   		    $data['street'] = $row->street;
    	   		    $data['imageName'] = $row->imageName;
    	   		    $data['contactDetail'] = $this->_getContactDetail($row->id);
					$final[] = $data;
				}
				return $final;
		}else{
		   return false;
		}
	}
	
	
	public function getHeadData(){ 
    	$this->db->select("*");
    	$this->db->order_by("id","desc");
    	$getquery = $this->db->get("tbl_detail");
    	if($getquery->num_rows()>0) {
    	   		foreach($getquery->result() as $row){
    	   		    $data['num_rows'] = $getquery->num_rows();
    	   		    $data['id'] = $row->id;
    	   		    $data['name'] = $row->name;
    	   		    $data['number'] = $row->number;
    	   		    $data['address'] = $row->address;
    	   		    $data['street_id'] = $row->street_id;
    	   		    $data['childDetail'] = $this->_getChildDetail($row->id);
					$final[] = $data;
				}
				return $final;
		}else{
		   return false;
		}
	}
	
	
	public function _getChildDetail($id){ 
    	$this->db->select("*");
    	$this->db->where("parent_id_FK", $id);
    	$getquery = $this->db->get("tbl_member");
    	if($getquery->num_rows()>0) {
    	   		foreach($getquery->result() as $row){
    	   		    $data['num_rows'] = $getquery->num_rows();
    	   		    $data['id'] = $row->member_id_PK;
    	   		    $data['member_name'] = $row->member_name;
    	   		    $data['member_phone'] = $row->member_phone;
    	   		    $final[] = $data;
				}
				return $final;
		}else{
		   return false;
		}
	}
	
	
	
	 public function _getContactDetail($id){ 
    	$this->db->select("*");
    	$this->db->where("street_id", $id);
    	$getquery = $this->db->get("tbl_detail");
    	if($getquery->num_rows()>0) {
    	   		foreach($getquery->result() as $row){
    	   		    $data['num_rows'] = $getquery->num_rows();
    	   		    $data['id'] = $row->id;
    	   		    $data['name'] = $row->name;
    	   		    $data['number'] = $row->number;
    	   		    $data['address'] = $row->address;
    	   		    $data['email'] = $row->email;
    	   		    $final[] = $data;
				}
				return $final;
		}else{
		   return false;
		}
	}
    
    
     public function adClick($ads_id_PK){ 
    	
    	   $query = $this->db->query("SELECT number_click FROM tbl_ads WHERE ads_id_PK=".$ads_id_PK);
    	   if($query->num_rows()>0) {
    	   		$row = $query->row();
    	   		$number_click = $row->number_click;
    	   		$filter['ads_id_PK'] = $ads_id_PK;
    	   		$set['number_click'] = $number_click+1;
    	   		$update = $this->general_model->update('tbl_ads',$filter,$set);
    	   		return true;
    	   }else{
			  return false;
			}
		 }
		 
		 
		  public function getDataVideo(){ 
    	$this->db->select("no_of_ads,shuffle_interval");
    	$getquery = $this->db->get("tbl_config");
    	if($getquery->num_rows()>0) {
    	   $getdata = $getquery->row();
    	   $number_of_ads = $getdata->no_of_ads;
    	   $shuffle_interval = $getdata->shuffle_interval;
    	   $query = $this->db->query("SELECT ads_id_PK,campaign_name,ad_picture,url,number_views,isImage FROM tbl_ads_video 
    	   WHERE isDelete=0 AND status=1 ORDER BY RAND()");
    	   if($query->num_rows()>0) {
    	   		foreach($query->result() as $row){
    	   		    $number_views = $row->number_views;
    	   		    $filter['ads_id_PK'] = $row->ads_id_PK;
    	   		    $set['number_views'] = $number_views+1;
    	   		    $update = $this->general_model->update('tbl_ads',$filter,$set);
    	   		    $data['ads_id_PK'] = $row->ads_id_PK;
					$data['campaign_name'] = $row->campaign_name;
					$data['shuffle_interval'] = $shuffle_interval;
					$data['isImage'] = $row->isImage;
					if($row->ad_picture==""){
				 		$image = "defaultimage.png";
					}else{
				  		$image = $row->ad_picture;
					}
					$data['ad_picture'] = base_url().'upload/'.$image;
					$data['url'] = $row->url;
					$final[] = $data;
				}
				return $final;
			}else{
			  return false;
			}
		 }
        else{
		   return [];
        }
    }
    
    
    public function getDataAudio($start,$end){ 
    	$this->db->select("no_of_ads,shuffle_interval");
    	$getquery = $this->db->get("tbl_config");
    	if($getquery->num_rows()>0) {
    	   $getdata = $getquery->row();
    	   $number_of_ads = $getdata->no_of_ads;
    	   $shuffle_interval = $getdata->shuffle_interval;
    	   $query = $this->db->query("SELECT ads_id_PK,campaign_name,ad_picture,url,number_views,isImage FROM tbl_ads_video 
    	    ORDER BY RAND() LIMIT ".$start.", ".$end." ");
    	   if($query->num_rows()>0) {
    	   		foreach($query->result() as $row){
    	   		    $number_views = $row->number_views;
    	   		    $filter['ads_id_PK'] = $row->ads_id_PK;
    	   		    $set['number_views'] = $number_views+1;
    	   		    $update = $this->general_model->update('tbl_ads',$filter,$set);
    	   		    $data['nums'] = $query->num_rows();
    	   		    $data['ads_id_PK'] = $row->ads_id_PK;
					$data['campaign_name'] = $row->campaign_name;
					$data['shuffle_interval'] = $shuffle_interval;
					$data['isImage'] = $row->isImage;
					
					if($row->ad_picture==""){
				 		$image = "defaultimage.png";
					}else{
				  		$image = $row->ad_picture;
					}
					$data['ad_picture'] = base_url().'upload/'.$image;
					$data['url'] = $row->url;
					
					$final[] = $data;
				}
				return $final;
			}else{
			  return false;
			}
		 }
        else{
		   return [];
        }
    }
    
}   