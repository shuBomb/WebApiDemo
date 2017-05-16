<?php

  /* 
  * Application   : Eporwal 
  * Version       : 1.0
  * Created Date  : 03/Oct/2016
  * Created By    : SIPL Developer[Shubham Jain]
  * Modified Date : 03/Oct/2016
  * Modified By   : Shubham Jain
  * Purpose       : This class is used for webservice of user details
  */

class Epuser extends CI_Controller {
	public function __construct(){
        parent::__construct();
		$this->load->model('general_model');
		$this->load->model('epuser_model');
		
		$this->load->helper('date');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->form_validation->set_error_delimiters('', '');
	    $config['allowed_types'] = 'jpeg|jpg|png|bmp';
        $this->load->library('upload', $config);
        $this->load->library('session');
        $this->load->library('pagination');
	}
	
	public function signup(){
		$email = trim($this->input->post('email')); 
		$username = trim($this->input->post('username')); 
		$password = md5(trim($this->input->post('password'))); 
		// Password
		$imei = trim($this->input->post('password')); 
		$contactnumber = trim($this->input->post('contactnumber')); 
		$profilecreated = trim($this->input->post('profilecreated')); 
		$gender = trim($this->input->post('gender')); 
		$device_token = trim($this->input->post('device_token')); 
		$lat = trim($this->input->post('lat')); 
		$lng = trim($this->input->post('lng')); 
		$isUnmarried = trim($this->input->post('isUnmarried')); 
		
		if($email == "" || $username == "" || $password == "" || $contactnumber == "" 
		|| $profilecreated == "" || $gender == "" ){
			$data['status'] = false;
			$data['messaage'] = "Please entered all the required field";
		}else{
			$filteremail['email'] = $email;
			if($this->general_model->getData('tbl_user',$filteremail)){
				$data['status'] = false;
				$data['messaage'] = "Email address is already in use.";
			}else{
				$filterusername['username'] = $username;
				if($this->general_model->getData('tbl_user',$filterusername)){
					$data['status'] = false;
					$data['messaage'] = "Someone already has that username.. Try another?";
				}else{
					$set['email'] = $email;
					$set['username'] = $username;
					$set['password'] = $password;
					$set['imei'] = $imei;
					$set['contact_number'] = $contactnumber;
					$set['profile_created'] = $profilecreated;
					$set['gender'] = $gender;
					$set['device_token'] = $device_token;
					$set['isScreenCompleted'] = 1;
					$set['isMarried'] = $isUnmarried;
			
					$id = $this->general_model->insert('tbl_user',$set);
					$data['status'] = true;
					$data['id'] = $id;
					$data['messaage'] = "You have successfully registered,Please verify your email address";
				}
			}
		}
		echo json_encode($data);
	}
	
	
	public function login(){
		$username = trim($this->input->post('username')); 
		$password = md5(trim($this->input->post('password'))); 
		$device_token = trim($this->input->post('device_token')); 
		
		if($username == "" || $password == ""){
			$data['status'] = false;
			$data['messaage'] = "Please entered all the required field";
		}else{
		    if($getResult = $this->epuser_model->login($username,$password)){
		    	if($getResult->isActive==1){
		    	
		    	    $filter['user_id_PK'] = $getResult->user_id_PK;
		    	    $set['device_token'] = $device_token;
		    	    $this->general_model->update('tbl_user',$filter,$set);

		    		$data['status'] = true;
		    		$data['id'] = $getResult->user_id_PK;
		    		$data['username'] = $getResult->fullname;
		    		$data['isScreenCompleted'] = $getResult->isScreenCompleted;
		    		$data['email'] = $getResult->email;

					$data['pic'] = "";
              	    if($getResult->photo_url_thumb1 != "")
              	    	$data['pic'] = base_url()."upload/epor/".$getResult->photo_url_thumb1;
              	    else if($getResult->photo_url_thumb2 != "")
              	    	$data['pic'] = base_url()."upload/epor/".$getResult->photo_url_thumb2;
              	    else if($getResult->photo_url_thumb3 != "")
              	    	$data['pic'] = base_url()."upload/epor/".$getResult->photo_url_thumb3;
              	    else if($getResult->photo_url_thumb4 != "")
              	    	$data['pic'] = base_url()."upload/epor/".$getResult->photo_url_thumb4;
              	    else if($getResult->photo_url_thumb5 != "")
              	    	$data['pic'] = base_url()."upload/epor/".$getResult->photo_url_thumb5;


		    	}else{
		    	 $data['status'] = false;
				 $data['messaage'] = "It look like you are not active user, Please contact to our support team";
		    	}
		    }else{
		        $data['status'] = false;
				$data['messaage'] = "The email address or password you provided does not match our records. Please try again.";
		    }
		}
		echo json_encode($data);
	}

	
	public function personalBasicInfo(){
	    $user_id = trim($this->input->post('user_id')); 
		$fathername = trim($this->input->post('fathername')); 
		$mothername = trim($this->input->post('mothername')); 
		$state = trim($this->input->post('state')); 
		$city = trim($this->input->post('city')); 
		$address = trim($this->input->post('address')); 
		
		$fullname = trim($this->input->post('fullname')); 
		$dob = trim($this->input->post('date_of_birth')); 
		$mother_gotra = trim($this->input->post('mother_gotra')); 
		$father_gotra = trim($this->input->post('father_gotra')); 
		$height = trim($this->input->post('height')); 
		$religious = trim($this->input->post('religious')); 
		$Ismanglik = trim($this->input->post('Ismanglik'));
		$blood_group = trim($this->input->post('blood_group')); 
		
		if($fathername == "" || $mothername == "" || $state == "" || $city == "" 
		|| $address == "" || $fullname == "" || $dob == "" || $mother_gotra == "" || $father_gotra == ""
		|| $height == "" || $religious == "" || $Ismanglik == ""){
		
			$data['status'] = false;
			$data['messaage'] = "Please entered all the required field";
		
		}else{
			$newDateofBirth = date('Y-m-d',strtotime($dob));
			
			$updateScreenCompleted = $this->epuser_model->updateScreenCompleted($user_id,2);
			

			$set['fathername'] = $fathername;
			$set['mothername'] = $mothername;
			$set['user_id_FK'] = $user_id;
			$set['state'] = $state;
			$set['city'] = $city;
			$set['address'] = $address;
			
			$set['fullname'] = $fullname;
			$set['date_of_birth'] = $newDateofBirth;
			$set['mother_gotra'] = $mother_gotra;
			$set['father_gotra'] = $father_gotra;
			$set['height'] = $height;
			$set['religious'] = $religious;
			$set['manglik'] = $Ismanglik;
			$set['blood_group'] = $blood_group;
			
			
			$filter['user_id_FK'] = $user_id;
			if($this->general_model->getData("tbl_user_basic_personal",$filter)){
			    $this->general_model->update('tbl_user_basic_personal',$filter,$set);
			    $data['status'] = true;
				$data['messaage'] = "Information has been added"; 
			}else{
			    $set['user_id_FK'] = $user_id;
			    $id = $this->general_model->insert('tbl_user_basic_personal',$set);
				$data['status'] = true;
				$data['id'] = $id;
				$data['messaage'] = "Information has been added"; 
			}
		}
		echo json_encode($data);
	}
	
	
	public function complextionEduInfo(){
	    $user_id = trim($this->input->post('user_id')); 
		$complextion = trim($this->input->post('complextion')); 
		$body_type = trim($this->input->post('body_type')); 
		$weight = trim($this->input->post('weight')); 
		$special_cases = trim($this->input->post('special_cases')); 
		$about_us = trim($this->input->post('about_us')); 
		
		$highest_qualification = trim($this->input->post('highest_qualification')); 
		$field_of_study = trim($this->input->post('field_of_study')); 
		$employeed_in = trim($this->input->post('employeed_in')); 
		$occupation = trim($this->input->post('occupation')); 
		$designation = trim($this->input->post('designation')); 
		$annualIncome = trim($this->input->post('annualIncome')); 
		
		if($complextion == "" || $body_type == "" || $weight == "" || $special_cases == "" 
		|| $about_us == "" || $highest_qualification == "" || $field_of_study == "" || $employeed_in == "" 
		|| $occupation == "" || $designation == "" ){
		
			$data['status'] = false;
			$data['messaage'] = "Please entered all the required field";
		
		}else{
			$set['complextion'] = $complextion;
			$set['body_type'] = $body_type;
			$set['weight'] = $weight;
			$set['special_cases'] = $special_cases;
			$set['about_us'] = $about_us;
			
			$set['highest_qualification'] = $highest_qualification;
			$set['field_of_study'] = $field_of_study;
			$set['employeed_in'] = $employeed_in;
			$set['occupation'] = $occupation;
			$set['designation'] = $designation;
			$set['annual_income'] = $annualIncome;

			$updateScreenCompleted = $this->epuser_model->updateScreenCompleted($user_id,3);
			
			$filter['user_id_FK'] = $user_id;
			if($this->general_model->getData("tbl_user_about_education",$filter)){
			    $this->general_model->update('tbl_user_about_education',$filter,$set);
			    $data['status'] = true;
				$data['messaage'] = "Information has been added"; 
			}else{
			    $set['user_id_FK'] = $user_id;
			    $id = $this->general_model->insert('tbl_user_about_education',$set);
				$data['status'] = true;
				$data['id'] = $id;
				$data['messaage'] = "Information has been added"; 
			}
			
			 $filterIsActive['user_id_PK'] = $user_id;
			 $setIsActive['isActive'] = 1;
			 $this->general_model->update('tbl_user',$filterIsActive,$setIsActive);
		}
		echo json_encode($data);
	}
	
	
	public function addpicture(){
		
		$user_id = trim($this->input->post('user_id')); 
		$isType = trim($this->input->post('isType')); 
		$ImageNumber = trim($this->input->post('ImageNumber'));
		
		if(($user_id=="")){
		   $data['messaage'] = "Invalid Credential"; 
		   $data['status']= false;
		   echo json_encode($data);
		   return false;
		}
		
		$image_name = "";
		$image_name_thumb="";
		  if($_FILES){ 
        	// Upload job picture
            $random = time();
            $config['upload_path'] = 'upload/epor/';
            $config['allowed_types'] = 'jpg|png|jpeg|bmp';
            $config['file_name'] = $random ;
            $config['encrypt_name'] = TRUE;
            //$this->load->library('image_lib');
            //$this->image_lib->clear();
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
             
             ini_set('upload_max_filesize', '10M');
			 ini_set('memory_limit', '-1');   
             if ($this->upload->do_upload('user_image')) {
				$imageArray = $this->upload->data();
				
				$image_name = $imageArray['raw_name'].''.$imageArray['file_ext']; // Job Attachment
					
				$config1['image_library'] = 'gd2';
    			$config1['source_image'] = './upload/epor/'.$image_name;
    			$config1['create_thumb'] = TRUE;
    			$config1['maintain_ratio'] = TRUE;
    			$config1['width']     = 300;
   	 			$config1['height']   = 377;
    		
 	  			$this->load->library('image_lib', $config);
 	  			$this->image_lib->initialize($config1);
 	  			$this->image_lib->resize();
    			$this->image_lib->clear();
    			
    			$image_name_thumb =$imageArray['raw_name'].'_thumb'.$imageArray['file_ext'];
    				
    		 } else{
    		  	$data['status']=false;
				$data['message']=strip_tags($this->upload->display_errors());
				echo json_encode($data);
				return false;
	        }
		  }
		$set['isType'] = $isType;
		
		
		$filter['user_id_FK'] = $user_id;
		$filter['isType'] = $isType;
		$getData = $this->general_model->getData('tbl_user_photo',$filter);
		if($getData){
		    if($ImageNumber==1){
		    	$set['photo_url1'] = $image_name;
				$set['photo_url_thumb1'] = $image_name_thumb;
		    	$id = $this->general_model->update('tbl_user_photo',$filter,$set);
		    }
		    if($ImageNumber==2){
		    	$set['photo_url2'] = $image_name;
				$set['photo_url_thumb2'] = $image_name_thumb;
		    	$id = $this->general_model->update('tbl_user_photo',$filter,$set);
		    }
		    if($ImageNumber==3){
		    	$set['photo_url3'] = $image_name;
				$set['photo_url_thumb3'] = $image_name_thumb;
		    	$id = $this->general_model->update('tbl_user_photo',$filter,$set);
		    }
		    if($ImageNumber==4){
		    	$set['photo_url4'] = $image_name;
				$set['photo_url_thumb4'] = $image_name_thumb;
		    	$id = $this->general_model->update('tbl_user_photo',$filter,$set);
		    }
		    if($ImageNumber==5){
		    	$set['photo_url5'] = $image_name;
				$set['photo_url_thumb5'] = $image_name_thumb;
		    	$id = $this->general_model->update('tbl_user_photo',$filter,$set);
		    }
		}else{
			$set['user_id_FK'] = $user_id;
			$set['photo_url1'] = $image_name;
			$set['photo_url_thumb1'] = $image_name_thumb;
			$id = $this->general_model->insert('tbl_user_photo',$set);
		}
		
		$data['status'] = true;
		$data['messaage'] = "Photo has been added"; 
		echo json_encode($data);
		
	}
	
	public function insertCommaSeperatedData(){
	   $data = trim($this->input->post('data')); 
	   $myid = trim($this->input->post('id')); 
	   $myArray = explode('~', $data);
	   foreach($myArray as $my_Array){
	      $filter['designation'] = $my_Array;
	      $filter['occupation_id'] = $myid ;
	      $id = $this->general_model->insert('tbl_occupation_design',$filter);
    		
		}
		echo "Done";  
	}
	
	
	public function getMatches(){
	    $user_id = trim($this->input->post('user_id')); 
	    $page = trim($this->input->post('page'));
	    $lat = trim($this->input->post('lat')); 
	    $long = trim($this->input->post('long'));
	    
	    $appversion = trim($this->input->post('appversion')); 
	    $mobileversion = trim($this->input->post('mobileversion'));

	    $name = trim($this->input->post('searchText'));
	    $blood_group = trim($this->input->post('blood_group'));
	    $gender = trim($this->input->post('gender'));
	    $education = trim($this->input->post('education'));
	    $city = trim($this->input->post('city'));
	    $min_age = trim($this->input->post('min_age'));
	    $max_age = trim($this->input->post('max_age'));
	    $married = trim($this->input->post('married'));
	    
	    if($blood_group == "All"){
			$blood_group = "";
		}
		if($married == "Both"){
			$married = "";
		}else if($married == "Married"){
			$married = "1";
		}else if($married == "Unmarried"){
			$married = "0";
		}

	    
	    if(($page==0) OR ($page=='')){
			$start=0;
		}
		else{
			$start=$page*20;
		}
		$end=20;
		
		
		if($user_id == ""){
			$data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		
		}else{
		
		   $set['appversion'] = $appversion;
		   $set['mobversion'] = $mobileversion;
		   $filter['user_id_PK'] = $user_id;
		   $id = $this->general_model->update('tbl_user',$filter,$set);
		    
			if($get_cena = $this->epuser_model->getMatches($user_id,$start,$end,$lat,$long,
			$name,$gender,$education,$city,$min_age,$max_age,$blood_group,$married)){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function getMyMatches(){
	    $user_id = trim($this->input->post('user_id')); 
	    $page = trim($this->input->post('page'));
	    $lat = trim($this->input->post('lat')); 
	    $long = trim($this->input->post('long'));
	    
	    if(($page==0) OR ($page=='')){
			$start=0;
		}
		else{
			$start=$page*20;
		}
		$end=20;
		
		
		if($user_id == ""){
		
			$data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		
		}else{
			if($get_cena = $this->epuser_model->getMyMatches($user_id,$start,$end,$lat,$long)){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function getProfileViewer_(){
	    $user_id = trim($this->input->post('user_id')); 
	    $page = trim($this->input->post('page'));
	    $lat = trim($this->input->post('lat')); 
	    $long = trim($this->input->post('long'));
	    
	    if(($page==0) OR ($page=='')){
			$start=0;
		}
		else{
			$start=$page*20;
		}
		$end=20;
		
		
		if($user_id == ""){
		
			$data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		
		}else{
			if($get_cena = $this->epuser_model->getProfileViewer($user_id,$start,$end,$lat,$long)){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	} 
	
	
	public function getDetail(){
	    $user_id = trim($this->input->post('user_id'));
	    $selected_user_id = trim($this->input->post('selected_user_id'));  
	    
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		
		}else{
			if($get_cena = $this->epuser_model->getDetail($user_id,$selected_user_id)){
			    $this->profileviewer($user_id,$selected_user_id);
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function shortlisted($user_id,$match_id){
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    $filter['user_id_FK'] = $user_id;
			$filter['match_id_FK'] = $match_id;
			$getData = $this->general_model->getData('tbl_shortlisted',$filter);
			if($getData){
				$data['status'] = true;
				$data['code'] = 2;
				$data['messaage'] = "Your match has been deleted";
				$id = $this->general_model->delete('tbl_shortlisted',$filter);
			}else{
			    $id = $this->general_model->insert('tbl_shortlisted',$filter);
				$data['status'] = true;
				$data['messaage'] = "Your match has been shortlisted";
				$data['code'] = 1;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function profileviewer($user_id,$view_id){
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    $filter['user_id_FK'] = $user_id;
			$filter['viewer_id_FK'] = $view_id;
			$getData = $this->general_model->getData('tbl_profile_viewer',$filter);
			if($getData){
			}else{
			    $id = $this->general_model->insert('tbl_profile_viewer',$filter);
			}
		}
	}
	
	public function getContactInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getContactInfo = $this->epuser_model->getContactInfo($user_id)){
			 	$data['status'] = true;
				$data['result'] = $getContactInfo;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function getBasicInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getBasicInfo = $this->epuser_model->getBasicInfo($user_id)){
			 	$data['status'] = true;
				$data['result'] = $getBasicInfo;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function getPhysicalInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getPhysicalInfo = $this->epuser_model->getPhysicalInfo($user_id)){
			 	$data['status'] = true;
				$data['result'] = $getPhysicalInfo;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function getEducationInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getEducationInfo = $this->epuser_model->getEducationInfo($user_id)){
			 	$data['status'] = true;
				$data['result'] = $getEducationInfo;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function getFamilyInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getEducationInfo = $this->epuser_model->getFamilyInfo($user_id)){
			 	$data['status'] = true;
				$data['result'] = $getEducationInfo;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	
	
	
	public function resetPassword(){
	    $user_id = trim($this->input->post('user_id'));
	    $old_password = trim($this->input->post('oldPassword'));
	    $new_password = trim($this->input->post('newPassword'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($resetPassword = $this->epuser_model->resetPassword($user_id,$old_password,$new_password)){
			 	$data['status'] = true;
				$data['message'] = $resetPassword;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}


	public function updateContactInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    $contact_number = trim($this->input->post('contact_number'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
			$set['contact_number'] = $contact_number;
			$filter['user_id_PK'] = $user_id;
		    $id = $this->general_model->update('tbl_user',$filter,$set);

		    $data['status'] = true;
			$data['message'] = "Your contact detail has been updated";
			$data['code'] = 200;
		}
		echo str_replace('\/','/',json_encode($data));
	}

	public function updateBasicInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    $fullname = trim($this->input->post('fullname'));
	    $dob = trim($this->input->post('dob'));
	    $state = trim($this->input->post('state'));
	    $city = trim($this->input->post('city'));
	    $address = trim($this->input->post('address'));
	    $height = trim($this->input->post('height'));
	    $religious = trim($this->input->post('religious'));
	    $manglik = trim($this->input->post('manglik'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
			$set['fullname'] = $fullname;
			$set['date_of_birth'] = $dob;
			$set['state'] = $state;
			$set['city'] = $city;
			$set['address'] = $address;
			$set['height'] = $height;
			$set['religious'] = $religious;
			$set['manglik'] = $manglik;
			$filter['user_id_FK'] = $user_id;
		    $id = $this->general_model->update('tbl_user_basic_personal',$filter,$set);

		    $data['status'] = true;
			$data['message'] = "Your basic detail has been updated";
			$data['code'] = 200;
		}
		echo str_replace('\/','/',json_encode($data));
	}

	public function updatePhysicalInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    $complextion = trim($this->input->post('complextion'));
	    $body_type = trim($this->input->post('body_type'));
	    $weight = trim($this->input->post('weight'));
	    $special_cases = trim($this->input->post('special_cases'));
	    $about_us = trim($this->input->post('about_us'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
			$set['complextion'] = $complextion;
			$set['body_type'] = $body_type;
			$set['weight'] = $weight;
			$set['special_cases'] = $special_cases;
			$set['about_us'] = $about_us;
			$filter['user_id_FK'] = $user_id;
		    $id = $this->general_model->update('tbl_user_about_education',$filter,$set);

		    $data['status'] = true;
			$data['message'] = "Your physical detail has been updated";
			$data['code'] = 200;
		}
		echo str_replace('\/','/',json_encode($data));
	}


	public function updateEducationInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    $highest_qualification = trim($this->input->post('highest_qualification'));
	    $field_of_study = trim($this->input->post('field_of_study'));
	    $employeed_in = trim($this->input->post('employeed_in'));
	    $occupation = trim($this->input->post('occupation'));
	    $designation = trim($this->input->post('designation'));
	    $company_name = trim($this->input->post('company_name'));
	    $annual_income = trim($this->input->post('annual_income'));
	    $expereince = trim($this->input->post('expereince'));
	    $college = trim($this->input->post('college'));

	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
			$set['highest_qualification'] = $highest_qualification;
			$set['field_of_study'] = $field_of_study;
			$set['employeed_in'] = $employeed_in;
			$set['occupation'] = $occupation;
			$set['designation'] = $designation;
			$set['company_name'] = $company_name;
			$set['annual_income'] = $annual_income;
			$set['expereince'] = $expereince;
			$set['college'] = $college;

			$filter['user_id_FK'] = $user_id;
		    $id = $this->general_model->update('tbl_user_about_education',$filter,$set);

		    $data['status'] = true;
			$data['message'] = "Your Education detail has been updated";
			$data['code'] = 200;
		}
		echo str_replace('\/','/',json_encode($data));
	}


	public function updateFamilyInfo(){
	    $user_id = trim($this->input->post('user_id'));
	    $fathername = trim($this->input->post('fathername'));
	    $mothername = trim($this->input->post('mothername'));
	    $mother_gotra = trim($this->input->post('mother_gotra'));
	    $father_gotra = trim($this->input->post('father_gotra'));
	    $father_occupation = trim($this->input->post('father_occupation'));
	    $mother_occupation = trim($this->input->post('mother_occupation'));
	    $family_type = trim($this->input->post('family_type'));
	    $living_with = trim($this->input->post('living_with'));

	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
			$set['fathername'] = $fathername;
			$set['mothername'] = $mothername;
			$set['mother_gotra'] = $mother_gotra;
			$set['father_occupation'] = $father_occupation;
			$set['father_gotra'] = $father_gotra;
			$set['mother_occupation'] = $mother_occupation;
			$set['family_type'] = $family_type;
			$set['living_with'] = $living_with;

			$filter['user_id_FK'] = $user_id;
		    $id = $this->general_model->update('tbl_user_basic_personal',$filter,$set);

		    $data['status'] = true;
			$data['message'] = "Your Family detail has been updated";
			$data['code'] = 200;
		}
		echo str_replace('\/','/',json_encode($data));
	}



	public function getPicures(){
	    $user_id = trim($this->input->post('user_id'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getPicures = $this->epuser_model->getPicures($user_id)){
			 	$data['status'] = true;
				$data['result'] = $getPicures;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
		public function getMYquote(){
	       if($getPicures = $this->epuser_model->getMYquote()){
			 	$data['status'] = true;
				$data['result'] = $getPicures;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function getProfileViewer(){
	    $user_id = trim($this->input->post('user_id'));
	    $page = trim($this->input->post('page'));
		if(($page==0) OR ($page=='')){
			$start=0;
		}
		else{
			$start=$page*20;
		}
		$end=20;
		
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getProfileViewer = $this->epuser_model->getProfileViewer($user_id,$start,$end)){
			 	$data['status'] = true;
				$data['result'] = $getProfileViewer;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	public function getNameForAutocomplete(){
	    $searchkey = trim($this->input->post('searchkey')); 
	    if($get_cena = $this->epuser_model->getNameForAutocomplete($searchkey)){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function getHighestQualification(){
	    $user_id = trim($this->input->post('user_id')); 
	    if($user_id==""){
	    	$data['status'] = false;
			$data['messaage'] = "Invalid Credential";
	    }else{
	    	if($get_cena = $this->epuser_model->getHighestQualification()){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['occupation'] = $this->epuser_model->getOccupation();
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
	    }
	    
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function getFieldOfStudy(){
	    $user_id = trim($this->input->post('user_id')); 
	    $eduId = trim($this->input->post('eduId')); 
	    if($user_id==""){
	    	$data['status'] = false;
			$data['messaage'] = "Invalid Credential";
	    }else{
	    	if($get_cena = $this->epuser_model->getFieldOfStudy($eduId)){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
	    }
	    
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function getDesgination(){
	    $user_id = trim($this->input->post('user_id')); 
	    $occuId = trim($this->input->post('occuId')); 
	    if($user_id==""){
	    	$data['status'] = false;
			$data['messaage'] = "Invalid Credential";
	    }else{
	    	if($get_cena = $this->epuser_model->getDesgination($occuId)){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
	    }
	    
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function addeducation(){
	    $edu_id = trim($this->input->post('edu_id')); 
	    $fos = trim($this->input->post('fos')); 
	    if($edu_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    $filter['qualification'] = $fos;
			$getData = $this->general_model->getData('tbl_field_of_study',$filter);
			if($getData){
				$data['status'] = false;
				$data['messaage'] = "Already Added";
			}else{
			    $filter['qualification_id'] = $edu_id; 
			    $id = $this->general_model->insert('tbl_field_of_study',$filter);
				$data['status'] = true;
				$data['messaage'] = "Added";
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function addOccupation(){
	    $occupation = trim($this->input->post('occupation')); 
	    if($occupation == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Data";
		}else{
		    $filter['name'] = $occupation;
			$getData = $this->general_model->getData('tbl_occupation',$filter);
			if($getData){
				$data['status'] = false;
				$data['messaage'] = "Already Added";
			}else{
			    $id = $this->general_model->insert('tbl_occupation',$filter);
				$data['status'] = true;
				$data['messaage'] = "Added";
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function addDesignation(){
	    $occu_id = trim($this->input->post('occu_id')); 
	    $designation = trim($this->input->post('designation')); 
	    if($occu_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    $filter['designation'] = $designation;
			$getData = $this->general_model->getData('tbl_occupation_design',$filter);
			if($getData){
				$data['status'] = false;
				$data['messaage'] = "Already Added";
			}else{
			    $filter['occupation_id'] = $occu_id; 
			    $id = $this->general_model->insert('tbl_occupation_design',$filter);
				$data['status'] = true;
				$data['messaage'] = "Added";
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function getNotification(){
	    $user_id = trim($this->input->post('user_id'));
	    if($user_id == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getEducationInfo = $this->epuser_model->getNotification($user_id)){
			 	$data['status'] = true;
				$data['result'] = $getEducationInfo;
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Something went wrong';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	
	public function forgotWS(){
	    $email = trim($this->input->post('email'));
	    if($email == ""){
		    $data['status'] = false;
			$data['messaage'] = "Invalid Credential";
		}else{
		    if($getEducationInfo = $this->epuser_model->forgotWS($email)){
			 	$data['status'] = true;
				$data['message'] = "Your password has been send to your registered email address";
				$data['code'] = 200;
			}
			else{
				$data['status'] = false;
				$data['message'] = 'Email address not found';
				$data['code'] = 207;
			}
		}
		echo str_replace('\/','/',json_encode($data));
	}
	
	public function verified($email,$random,$time){
       $setData['password']  = md5(base64_decode($random));
       $setData['imei']  = base64_decode($random);
       $filter['email']  = base64_decode($email);
       $id = $this->general_model->update('tbl_user',$filter,$setData);
       echo "Your password will be ".base64_decode($random);
   } 
   
   
   public function skipUserInfo(){
	    $user_id = trim($this->input->post('user_id')); 
	    $email = trim($this->input->post('email')); 
	    $password = trim($this->input->post('password')); 
	    if($user_id=="" || $email=="" || $password==""){
	    	$data['status'] = false;
			$data['messaage'] = "Invalid Credential";
	    }else{
	    	if($get_cena = $this->epuser_model->skipUserInfo($user_id,$email,$password)){
				$data['status'] = true;
				$data['result'] = $get_cena;
				$data['message'] = 'Your information has been deleted';
			}
			else{
				$data['status'] = false;
				$data['message'] = 'No Data Found';
				$data['code'] = 207;
			}
	    }
	    
		echo str_replace('\/','/',json_encode($data));
	}
	
	

}