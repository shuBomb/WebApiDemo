<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Epuser_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
    
    public function login($username,$password) {
  		$query = $this->db->query("SELECT user_id_PK,isActive,isScreenCompleted,username,email,fullname,
        photo_url_thumb1,photo_url_thumb2,photo_url_thumb3,photo_url_thumb4,photo_url_thumb5 FROM tbl_user 
        LEFT JOIN tbl_user_photo ON tbl_user.user_id_PK=tbl_user_photo.user_id_FK
        LEFT JOIN tbl_user_basic_personal ON tbl_user.user_id_PK=tbl_user_basic_personal.user_id_FK
        WHERE (email='".$username."' OR username='".$username."') AND (password='".$password."') ");
		if($query->num_rows()>0) 
		  return $query->row();
		else
			return false;
	}

  
    public function getBestPic($user_id) {
       $final =  array();
       $query  = $this->db->query("SELECT photo_url_thumb1,photo_url_thumb2,photo_url_thumb3,photo_url_thumb4,photo_url_thumb5
            FROM tbl_user_photo WHERE user_id_FK='".$user_id."'  ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                    $data['pic'] = "";
                    
                    if($row->photo_url_thumb1 != "")
                      return base_url()."upload/epor/".$row->photo_url_thumb1;
                    else if($row->photo_url_thumb2 != "")
                      return base_url()."upload/epor/".$row->photo_url_thumb2;
                    else if($row->photo_url_thumb3 != "")
                      return base_url()."upload/epor/".$row->photo_url_thumb3;
                    else if($row->photo_url_thumb4 != "")
                      return base_url()."upload/epor/".$row->photo_url_thumb4;
                    else if($row->photo_url_thumb5 != "")
                      return base_url()."upload/epor/".$row->photo_url_thumb5;
                    $final[] = $data;
              }
               return $final;
            }
           return "";

    }
    
    public function getMatches($user_id,$start,$end,$lat,$long,$name,$gender,$education,
    $city,$min_age,$max_age,$blood_group,$married) {
        $where  = "isActive=1 ";
        
        if($name != ""){
          $where  .= "AND tbl_user_basic_personal.fullname LIKE '%".$name."%' ";
        } 
        if($blood_group != ""){
          $where  .= "AND tbl_user_basic_personal.blood_group = '".$blood_group."' ";
        }
        if($married != ""){
          $where  .= "AND tbl_user.isMarried = '".$married."' ";
        }  
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT user_id_PK, 
            username,gender,fullname,highest_qualification,father_gotra,mother_gotra,date_of_birth,state,city,height,isMarried,
            (select count(user_id_FK) from tbl_user_basic_personal JOIN tbl_user 
            ON tbl_user_basic_personal.user_id_FK=tbl_user.user_id_PK WHERE ".$where.") 
            as No_of_records   
            FROM tbl_user  
            LEFT JOIN tbl_user_basic_personal ON tbl_user.user_id_PK=tbl_user_basic_personal.user_id_FK
LEFT JOIN tbl_user_about_education ON tbl_user.user_id_PK=tbl_user_about_education.user_id_FK  WHERE  ".$where." 
 ORDER  BY created_date DESC LIMIT ".$start.",".$end."");
            if ($query->num_rows() > 0) {
              	foreach($query->result() as $row){
              	    $data['No_of_records'] = $row->No_of_records;
              	    $data['user_id'] = $row->user_id_PK;
              	    $data['pic'] = $this->getBestPic($row->user_id_PK);
              	    $data['gender'] = $row->gender;
              	    if($row->fullname != "")
              	     	$data['fullname'] = $row->fullname;
              	     else
              	    	$data['fullname'] = $row->username;
              	    	
              	    	if($row->highest_qualification != "")
              	     	$data['highest_qualification'] = $row->highest_qualification;
              	     else
              	    	$data['highest_qualification'] = "NA";
              	    	
              	    if($row->father_gotra != "")
              	     	$data['gotra'] = $row->father_gotra;
              	     else
              	    	$data['gotra'] = "NA";
              	    	
              	    if($row->mother_gotra != "")
              	     	$data['mother_gotra'] = $row->mother_gotra;
              	     else
              	    	$data['mother_gotra'] = "NA";
              	    	
              	    	
              	    	
              	    	$data['state'] = $row->state;
              	    	$data['city'] = $row->city;
              	    	$data['height'] = $row->height;
              	    	$data['isMarried'] = $row->isMarried;
              	    	
              	    	
              	    $age = (date('Y') - date('Y',strtotime($row->date_of_birth)));
              	    $data['age'] = $age;
         			
         			$final[] = $data;
            	}
            	 return $final;
            }
           return false;
    }
    
    
    public function getNameForAutocomplete($searchkey) {
        $where  = "";
        /*if($name != ""){
          $where = WHERE fullname LIKE '%".$name."%' ;
        }*/    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT user_id_PK, 
            username,gender,fullname,highest_qualification,father_gotra,date_of_birth,state,city,height FROM tbl_user  
            LEFT JOIN tbl_user_basic_personal ON tbl_user.user_id_PK=tbl_user_basic_personal.user_id_FK
LEFT JOIN tbl_user_about_education ON tbl_user.user_id_PK=tbl_user_about_education.user_id_FK WHERE isActive=1 AND fullname LIKE '%$searchkey%'
LIMIT 22 ");
            if ($query->num_rows() > 0) {
              	foreach($query->result() as $row){
              	    if($row->fullname != "")
              	     	$data['fullname'] = $row->fullname;
              	     else
              	    	$data['fullname'] = $row->username;
              	    $final[] = $data;
            	}
            	 return $final;
            }
           return false;
    }
    
    
    public function getMyMatches($user_id,$start,$end,$lat,$long){
        $this->db->select('match_id_FK');
        $this->db->where("user_id_FK", $user_id);
        $query = $this->db->get("tbl_shortlisted");
        $total = $query->num_rows();
        if ($total == 0)
            return 0;
        else {
            $commaList = "";
            $i=0;
            foreach ($query->result() as $row) {
                if($i==0)
                	$commaList = $row->match_id_FK;
                else
                  $commaList = $commaList.",".$row->match_id_FK;
               $i++;  
            }
            return $this->getSelectedMatches($commaList,$start,$end,$lat,$long);
        }
    }
    
    
    
    
    
    public function getSelectedMatches($user_id,$start,$end,$lat,$long) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT user_id_PK,
            username,gender,fullname,highest_qualification,father_gotra,date_of_birth,state,city,height FROM tbl_user  
            LEFT JOIN tbl_user_basic_personal ON tbl_user.user_id_PK=tbl_user_basic_personal.user_id_FK
LEFT JOIN tbl_user_about_education ON tbl_user.user_id_PK=tbl_user_about_education.user_id_FK
WHERE user_id_PK IN(".$user_id.") 
              ");
            if ($query->num_rows() > 0) {
              	foreach($query->result() as $row){
              	    $data['user_id'] = $row->user_id_PK;
              	    $data['pic'] = $this->getBestPic($row->user_id_PK);
                    $data['gender'] = $row->gender;
              	    if($row->fullname != "")
              	     	$data['fullname'] = $row->fullname;
              	     else
              	    	$data['fullname'] = $row->username;
              	    	
              	    	if($row->highest_qualification != "")
              	     	$data['highest_qualification'] = $row->highest_qualification;
              	     else
              	    	$data['highest_qualification'] = "NA";
              	    	
              	    if($row->father_gotra != "")
              	     	$data['gotra'] = $row->father_gotra;
              	     else
              	    	$data['gotra'] = "NA";
              	    	
              	    	$data['state'] = $row->state;
              	    	$data['city'] = $row->city;
              	    	$data['height'] = $row->height;
              	    	
              	    	
              	    $age = (date('Y') - date('Y',strtotime($row->date_of_birth)));
              	    $data['age'] = $age;
         			
         			$final[] = $data;
            	}
            	 return $final;
            }
           return false;
    }
    
    
   public function updateScreenCompleted($userid,$isScreenCompleted){
   			$filter['user_id_PK'] = $userid;
    	   	$set['isScreenCompleted'] = $isScreenCompleted;
    	   	$update = $this->general_model->update('tbl_user',$filter,$set);
   }
    
    
     public function getDetail($user_id,$selected_user_id) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT user_id_PK,email,photo_url1,photo_url2,photo_url3,photo_url4,
            photo_url5,username,gender,blood_group,fullname,highest_qualification,father_gotra,date_of_birth,state,
            city,height,fathername,mothername,address,date_of_birth,mother_gotra,father_gotra,manglik,religious,
             complextion,body_type,weight,special_cases,about_us,field_of_study,employeed_in,occupation,designation
            ,company_name,annual_income,college,expereince,(SELECT IF(match_id_FK,true,false) FROM tbl_shortlisted WHERE (match_id_FK='".$selected_user_id."' AND user_id_FK='".$user_id."')) as t
            FROM tbl_user LEFT JOIN tbl_user_photo ON tbl_user.user_id_PK=tbl_user_photo.user_id_FK
            
LEFT JOIN tbl_user_basic_personal ON tbl_user.user_id_PK=tbl_user_basic_personal.user_id_FK
LEFT JOIN tbl_user_about_education ON tbl_user.user_id_PK=tbl_user_about_education.user_id_FK
 WHERE  tbl_user.user_id_PK='".$selected_user_id."' ");
            if ($query->num_rows() > 0) {
            
              $row = $query->row();
              	    $data['user_id'] = $row->user_id_PK;
              	    $data['photos'] = $this->getPicuresArray($row->user_id_PK);
              	    $data['gender'] = $row->gender;
              	    if($row->fullname != "")
              	     	$data['fullname'] = $row->fullname;
              	     else
              	    	$data['fullname'] = $row->username;
              	    	
              	    	if($row->highest_qualification != "")
              	     	$data['highest_qualification'] = $row->highest_qualification;
              	     else
              	    	$data['highest_qualification'] = "NA";
              	    	
              	    if($row->father_gotra != "")
              	     	$data['gotra'] = $row->father_gotra;
              	     else
              	    	$data['gotra'] = "NA";
              	    	
              	    	$data['blood_group'] = $row->blood_group;
              	    	$data['state'] = $row->state;
              	    	$data['city'] = $row->city;
              	    	$data['height'] = $row->height;

              	    	$data['field_of_study'] = $row->field_of_study;
              	    	$data['employeed_in'] = $row->employeed_in;
              	    	$data['occupation'] = $row->occupation;
              	    	$data['designation'] = $row->designation;
              	    	
              	    	$data['fathername'] = $row->fathername;
              	    	$data['mothername'] = $row->mothername;
              	    	$data['address'] = $row->address;
              	    	$data['date_of_birth'] = $row->date_of_birth;
              	    	$data['mother_gotra'] = $row->mother_gotra;
              	    	$data['manglik'] = $row->manglik;
              	    	$data['religious'] = $row->religious;
              	    	$data['complextion'] = $row->complextion;
              	    	$data['body_type'] = $row->body_type;
              	    	$data['weight'] = $row->weight;
              	    	$data['special_cases'] = $row->special_cases;
              	    	$data['about_us'] = $row->about_us;
              	    	$data['email'] = $row->email;
              	    	$data['isSHortlisted'] = $row->t;
              	    	
              	    	$data['company_name'] = $row->company_name;
              	    	$data['annual_income'] = $row->annual_income;
              	    	$data['college'] = $row->college;
              	    	$data['expereince'] = $row->expereince;
              	    	
              	      $age = (date('Y') - date('Y',strtotime($row->date_of_birth)));
              	      $data['age'] = $age;
         			
         		 return $data;
            }
           return false;
    }
    
    
      
    public function getContactInfo($user_id) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT contact_number,email,profile_created,gender
            FROM tbl_user WHERE  tbl_user.user_id_PK='".$user_id."' ");
            if ($query->num_rows() > 0) {
               $row = $query->row();
               $data['contact_number'] = $row->contact_number;
               $data['email'] = $row->email;
               $data['profile_created'] = $row->profile_created;
     		   $data['gender'] = $row->gender;
              	 return $data;
            }
           return false;
    }
    
    public function getBasicInfo($user_id) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT fullname,city,address,date_of_birth,state,height,religious,manglik
            FROM tbl_user_basic_personal WHERE  tbl_user_basic_personal.user_id_FK='".$user_id."' ");
            if ($query->num_rows() > 0) {
               $row = $query->row();
               $data['fullname'] = $row->fullname;
               $data['city'] = $row->city;
               $data['address'] = $row->address;
     		   $data['date_of_birth'] = date("d F Y",strtotime($row->date_of_birth));
     		   $data['state'] = $row->state;
     		   $data['height'] = $row->height;
     		   $data['religious'] = $row->religious;
     		   $data['manglik'] = $row->manglik;
              	 return $data;
            }
           return false;
    }
    
    
    public function getPhysicalInfo($user_id) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT complextion,body_type,weight,special_cases,about_us
            FROM tbl_user_about_education WHERE  tbl_user_about_education.user_id_FK='".$user_id."' ");
            if ($query->num_rows() > 0) {
               $row = $query->row();
               $data['complextion'] = $row->complextion;
               $data['body_type'] = $row->body_type;
               $data['weight'] = $row->weight;
     		   $data['special_cases'] = $row->special_cases;
     		   $data['about_us'] = $row->about_us;
     		   return $data;
            }
           return false;
    }
    
     public function getEducationInfo($user_id) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT highest_qualification,field_of_study,company_name,employeed_in,occupation
            ,designation,annual_income,expereince FROM tbl_user_about_education WHERE  tbl_user_about_education.user_id_FK='".$user_id."' ");
            if ($query->num_rows() > 0) {
               $row = $query->row();
               $data['highest_qualification'] = $row->highest_qualification;
               $data['field_of_study'] = $row->field_of_study;
               $data['company_name'] = $row->company_name;
     		   $data['employeed_in'] = $row->employeed_in;
     		   $data['occupation'] = $row->occupation;
     		   $data['designation'] = $row->designation;
     		   $data['annual_income'] = $row->annual_income;
     		   $data['expereince'] = $row->expereince;
     		   return $data;
            }
           return false;
    }
    
    
     public function getFamilyInfo($user_id) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT fathername,mothername,father_occupation,mother_occupation,family_type
            ,living_with FROM tbl_user_basic_personal WHERE  tbl_user_basic_personal.user_id_FK='".$user_id."' ");
            if ($query->num_rows() > 0) {
               $row = $query->row();
               $data['fathername'] = $row->fathername;
               $data['mothername'] = $row->mothername;
               $data['father_occupation'] = $row->father_occupation;
     		   $data['mother_occupation'] = $row->mother_occupation;
     		   $data['family_type'] = $row->family_type;
     		   $data['living_with'] = $row->living_with;
     		   return $data;
            }
           return false;
    }
    
    
    public function resetPassword($user_id,$old_password,$new_password) {
    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT password FROM tbl_user WHERE  password='".md5($old_password)."' 
            AND user_id_PK='".$user_id."' ");
            if ($query->num_rows() == 0) {
               return "Your old password does not match.";
            }else{
                $filter['user_id_PK'] = $user_id;
            	$set['password'] = md5($new_password);
            	$set['imei'] = $new_password;
				$id = $this->general_model->update('tbl_user',$filter,$set);
				return "Your password has been updaetd.";
            }
           return false;
    }

     public function getPicures($user_id){
     $final =  array();
     $query  = $this->db->query("SELECT photo_url_thumb1,photo_url_thumb2,photo_url_thumb3,photo_url_thumb4,photo_url_thumb5,isType
            FROM tbl_user_photo WHERE user_id_FK='".$user_id."'  ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                    $data['pic1'] = "";
                    $data['pic2'] = "";
                    $data['pic3'] = "";
                    $data['pic4'] = "";
                    $data['pic5'] = "";

                    if($row->photo_url_thumb1 != "")
                      $data['pic1'] = base_url()."upload/epor/".$row->photo_url_thumb1;
                    if($row->photo_url_thumb2 != "")
                      $data['pic2'] = base_url()."upload/epor/".$row->photo_url_thumb2;
                    if($row->photo_url_thumb3 != "")
                      $data['pic3'] = base_url()."upload/epor/".$row->photo_url_thumb3;
                     if($row->photo_url_thumb4 != "")
                      $data['pic4'] = base_url()."upload/epor/".$row->photo_url_thumb4;
                     if($row->photo_url_thumb5 != "")
                      $data['pic5'] = base_url()."upload/epor/".$row->photo_url_thumb5;
                    $data['isType'] = $row->isType;
                    
                    $final[] = $data;
              }
               return $final;
            }
           return false;
   }
   
   
   public function getPicuresArray($user_id){
     $final =  array();
     $query  = $this->db->query("SELECT photo_url_thumb1,photo_url_thumb2,photo_url_thumb3,photo_url_thumb4,photo_url_thumb5,isType
            FROM tbl_user_photo WHERE user_id_FK='".$user_id."'  ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                    $data['pic1'] = "";
                    $data['pic2'] = "";
                    $data['pic3'] = "";
                    $data['pic4'] = "";
                    $data['pic5'] = "";

                    if($row->photo_url_thumb1 != "")
                      $data['pic1'] = base_url()."upload/epor/".$row->photo_url_thumb1;
                    if($row->photo_url_thumb2 != "")
                      $data['pic2'] = base_url()."upload/epor/".$row->photo_url_thumb2;
                    if($row->photo_url_thumb3 != "")
                      $data['pic3'] = base_url()."upload/epor/".$row->photo_url_thumb3;
                     if($row->photo_url_thumb4 != "")
                      $data['pic4'] = base_url()."upload/epor/".$row->photo_url_thumb4;
                     if($row->photo_url_thumb5 != "")
                      $data['pic5'] = base_url()."upload/epor/".$row->photo_url_thumb5;
                    $data['isType'] = $row->isType;
                    
                    $final[] = $data;
              }
               return $final;
            }
           return "[]";
   }

 
 public function getMYquote(){
     $final =  array();
     $query  = $this->db->query("SELECT quote FROM tbl_quotes  ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                   $data['quote'] = $row->quote;
                    $final[] = $data;
              }
               return $final;
            }
           return false;
   }
   
   public function getNotification($user_id){
     $date =  date("Y-m-d H:i:s", time());
     $final =  array();
     $query  = $this->db->query("SELECT notification,image,type,created_date FROM tbl_notification order by id DESC ");
            if ($query->num_rows() > 0) {
            $i =0;
                foreach($query->result() as $row){
                $i++;
                   $data['notification'] = $row->notification;
                   if($row->image==""){
                     $data['image'] = base_url()."upload/epor/spalsh.png";
                   }else{
                   	$data['image'] = base_url()."upload/epor/".$row->image;
                   }
                   $data['type'] = $row->type;
                   if($i==1){
                     $myquery  = $this->db->query("SELECT created_date FROM tbl_user where user_id_PK='".$user_id."' ");
 					 $roww = $myquery->row();
              	     $data['time'] = 'about '.$this->get_timestamp($roww->created_date,$date).' ago';
                   }else{
                     $data['time'] = 'about '.$this->get_timestamp($row->created_date,$date).' ago';
                   }
                    $final[] = $data;
              }
               return $final;
            }
           return false;
   }
   
   
   function get_timestamp($start,$end){
	//$seconds = strtotime($end) - strtotime($start);
	$seconds = strtotime($end) - strtotime($start);
		
	$year = floor(($seconds)/(60*60*24*365));
	$months = floor($seconds / 86400 / 30 );
	$week    = floor($seconds / 604800);
	$days    = floor($seconds / 86400);
	$hours   = floor(($seconds - ($days * 86400)) / 3600);
	$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
	$seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
	  
	if(($year!=0))
		if($year==1)
			return $year.' year';
		else
			return $year.' years';
		elseif(($year==0) && ($months!=0))
			if($months==1)
				return $months.' month';
			else
				return $months.' months';
			elseif(($year==0) && ($months==0) && ($week!=0))
				if($week==1)
					return $week.' week';
				else
					return $week.' weeks';
				elseif(($year==0) && ($months==0) && ($week==0) && ($days!=0))
						if($days==1)
							return $days.' day';
						else
							return $days.' days';
						elseif(($year==0) && ($months==0) && ($week==0) && ($days==0) && ($hours!=0))
							if($hours==1)
								return $hours.' hour';
							else
								return $hours.' hours';
							elseif(($year==0) && ($months==0) && ($week==0) && ($days==0) && ($hours==0) && ($minutes!=0))
								if($hours==1)
									return $minutes.' min';
								else
									return $minutes.' mins';
								elseif(($year==0) && ($months==0) && ($week==0) && ($days==0) && ($hours==0) && ($minutes==0) && ($seconds!=0))		
									return $seconds.' sec';
								else
									return "a min";
 	}
   
   public function getProfileViewer($user_id,$start,$end) {
        $where  = "";
        /*if($name != ""){
          $where = WHERE fullname LIKE '%".$name."%' ;
        }*/    
    		$date = date('Y-m-d');
            $query  = $this->db->query("SELECT user_id_PK, 
            username,gender,fullname,highest_qualification,father_gotra,date_of_birth,state,city,height 
            FROM tbl_profile_viewer  
            LEFT JOIN tbl_user ON tbl_profile_viewer.viewer_id_FK=tbl_user.user_id_PK 
            LEFT JOIN tbl_user_basic_personal ON tbl_profile_viewer.viewer_id_FK=tbl_user_basic_personal.user_id_FK
LEFT JOIN tbl_user_about_education ON tbl_profile_viewer.viewer_id_FK=tbl_user_about_education.user_id_FK 
GROUP BY tbl_profile_viewer.viewer_id_FK
             ");
            if ($query->num_rows() > 0) { 
              	foreach($query->result() as $row){
              	    $data['user_id'] = $row->user_id_PK;
              	    $data['pic'] = $this->getBestPic($row->user_id_PK);
              	    $data['gender'] = $row->gender;
              	    if($row->fullname != "")
              	     	$data['fullname'] = $row->fullname;
              	     else
              	    	$data['fullname'] = $row->username;
              	    	
              	    	if($row->highest_qualification != "")
              	     	$data['highest_qualification'] = $row->highest_qualification;
              	     else
              	    	$data['highest_qualification'] = "NA";
              	    	
              	    if($row->father_gotra != "")
              	     	$data['gotra'] = $row->father_gotra;
              	     else
              	    	$data['gotra'] = "NA";
              	    	
              	    	$data['state'] = $row->state;
              	    	$data['city'] = $row->city;
              	    	$data['height'] = $row->height;
              	    	
              	    	
              	    $age = (date('Y') - date('Y',strtotime($row->date_of_birth)));
              	    $data['age'] = $age;
         			
         			$final[] = $data;
            	}
            	 return $final;
            }
           return false;
    }
    
    public function getHighestQualification(){
     $final =  array();
     $query  = $this->db->query("SELECT education,id FROM tbl_highest_education  ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                   $data['education'] = $row->education;
                   $data['id'] = $row->id;
                    $final[] = $data;
              }
               return $final;
            }
           return false;
   }
   
    public function getOccupation(){
     $final =  array();
     $query  = $this->db->query("SELECT name,id FROM tbl_occupation  ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                   $data['name'] = $row->name;
                   $data['id'] = $row->id;
                    $final[] = $data;
              }
               return $final;
            }
           return [];
   }

   
   public function getFieldOfStudy($id){
     $final =  array();
     $query  = $this->db->query("SELECT qualification,id FROM tbl_field_of_study WHERE qualification_id='".$id."' ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                   $data['qualification'] = $row->qualification;
                   $data['id'] = $row->id;
                    $final[] = $data;
              }
               return $final;
            }
           return false;
   }
   
   public function getDesgination($id){
     $final =  array();
     $query  = $this->db->query("SELECT designation,id FROM tbl_occupation_design WHERE occupation_id='".$id."' ");
            if ($query->num_rows() > 0) {
                foreach($query->result() as $row){
                   $data['designation'] = $row->designation;
                   $data['id'] = $row->id;
                    $final[] = $data;
              }
               return $final;
            }
           return false;
   }


 public function forgotWS($email){
     $final =  array();
     $query  = $this->db->query("SELECT username FROM tbl_user WHERE email='".$email."' ");
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $rand = rand(0,10000000);
                $this->mail_verification($email,$row->username,$rand);
               return true;
            }
           return false;
   }
   



	public function mail_verification($email,$username,$random){
        $time = time();
        $ci = get_instance();
		$ci->load->library('email');
		$config['protocol'] = "smtp";
		$config['smtp_host'] = "ssl://smtp.gmail.com";
	$config['smtp_port'] = "465";
	$config['smtp_user'] = "teameporwal@gmail.com"; 
	$config['smtp_pass'] = "eporwal@1234";
	$config['charset'] = "utf-8";
	$config['mailtype'] = "html";
	$config['newline'] = "\r\n";

$ci->email->initialize($config);

$ci->email->from('teameporwal@gmail.com', 'Eporwal Team');
$list = array($email);
$ci->email->to($list);
$ci->email->bcc("jainshubham090@gmail.com");
$ci->email->subject('Your passsword has been updated');

        $message = "Hello ".$username.",<br/><br/>
        
        Please <a href=".base_url()."epuser/verified/".base64_encode($email)."/".base64_encode($random)."/".base64_encode($time)." 
        >click here</a> to change your password.<br/><br/>
        
        Thanks
        ";
        
        $ci->email->message($message);
        
        if ($ci->email->send()) {
            return TRUE;
        }
        
        return false;
    }
   
   
   
   public function skipUserInfo($user_id,$email,$password){
     $final =  array(); 
     $query  = $this->db->query("SELECT user_id_PK FROM tbl_user WHERE email='".$email."' AND password='".md5($password)."' ");
            if ($query->num_rows() > 0) {
                $row = $query->row();
                $filter['user_id_PK'] = $row->user_id_PK;
			    $id = $this->general_model->delete('tbl_user',$filter);
               return true;
            }
           return false;
   }
   
   
    
   
    
}