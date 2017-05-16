<?php

  /* 
  * Application   : Flipvid App
  * Version       : 1.0
  * Created Date  : 21/july/2014
  * Created By    : SIPL Developer[Shubham Jain]
  * Modified Date : 21/July/2014
  * Modified By   : Shubham Jain
  * Filename      : Profile.php
  * Purpose       : This class is used for webservice of user details
  */

class Androidstructure extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('general_model');
		$this->load->model('admin_model');
		$this->load->model('android_model');
	}
	
	public function signup(){
		$email = trim($this->input->post('email')); 
		$username = trim($this->input->post('username')); 
		$password = trim($this->input->post('password')); 
		$device_token = trim($this->input->post('device_token')); 
		
		if($email == "" || $username == "" || $password == "" ){
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
					
					$id = $this->general_model->insert('tbl_admin',$set);
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
		$password = trim($this->input->post('password')); 
		
		if($username == "" || $password == ""){
			$data['status'] = false;
			$data['messaage'] = "Please entered all the required field";
		}else{
		    if($getResult = $this->android_model->login($username,$password)){
		    	if($getResult->isActive==1){
		    		$data['status'] = true;
		    		$data['id'] = $getResult->id;
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
	
	public function getUserData($userid=NULL){
		
		if($userid == "" ){
			$data['status'] = false;
			$data['messaage'] = "Invalid credential";
		}else{
		    if($getResult = $this->android_model->getUserData($userid)){
		    		$data = $getResult;
		    }else{
		        $data['status'] = false;
				$data['messaage'] = "Seems like no data found in our record";
		    }
		}
		echo json_encode($data);
	}
	
	public function updateUserData(){
		
		if($userid == "" ){
			$data['status'] = false;
			$data['messaage'] = "Invalid credential";
		}else{
		    if($getResult = $this->android_model->getUserData($userid)){
		    		$data = $getResult;
		    }else{
		        $data['status'] = false;
				$data['messaage'] = "Seems like no data found in our record";
		    }
		}
		echo json_encode($data);
	}

}