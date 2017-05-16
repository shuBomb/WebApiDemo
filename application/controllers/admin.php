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

class Admin extends CI_Controller {

	public function __construct(){
        parent::__construct();
		$this->load->model('general_model');
		$this->load->model('admin_model');
		
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
	
	
	public function sendPushNotificationToGCMSever(){
		$token = "e7YmNvgoH3s:APA91bHhVnTeOPy_63N06deAeeCfJYGkCeFT8yYn5jp-AuLvO3lH7AORZ7zAqXQNJfhz9IfOlPk21aLbNKWRvw7YIikIC2TiPiudKcvDfbWj3XuQJUu5XUUtEhvgdqWmZeQEirMPkBkd";
		$message="THis is shubham here";
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
		
		$fields = array(
            'to' => $token,
            'notification' => array('title' => 'Working Good', 'body' => 'That is all we want'),
            'data' => array('message' => $message)
        );
 
        $headers = array(
            'Authorization:key=AIzaSyCHs7rRMesUKnoQ4roYjwW3R9AeFOEVLKU',
            'Content-Type:application/json'
        );		
		$ch = curl_init();
 
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    
        $result = curl_exec($ch);
       
        curl_close($ch);

        echo json_encode($result);
	}
	
	
	public function addLocalData(){
		$name = trim($this->input->post('data')); 
		$data = json_decode($name);
		
		foreach ($data as $key => $value) {
			$imei[$key] = $data[$key]->imei;
			$latitude[$key] = $data[$key]->latitude;
			$longitude[$key] = $data[$key]->longitude;
			$speed[$key] = $data[$key]->speed;
			$date[$key] = $data[$key]->date;
			$current_time=@date("Y-m-d H:i:s");
			
			$filter['imei'] = $imei[$key];
			$filter['latitude'] = $latitude[$key];
			$filter['longitude'] = $longitude[$key];
			$filter['datetime'] = $date[$key];
			
			$id = $this->general_model->insert('tbl_location',$filter);
			
		}
		$setdata['status'] = true;
		echo json_encode($setdata);
	}

	
	public function addqualification(){
		$name = trim($this->input->post('name')); 
		$filter['qualification'] = $name;
		if($this->general_model->getData('tbl_qualification',$filter)){
			$setdata['status'] = false;
		}else{
		    $data['qualification'] = $name;
			$id = $this->general_model->insert('tbl_qualification',$data);
			$setdata['status'] = true;
			$setdata['id'] = $id;
		}
		
		echo json_encode($setdata);
	}
	
	public function addFos(){
		$qname = trim($this->input->post('qname')); 
		$fos = trim($this->input->post('fos'));
		$split = explode("~",$qname);
		
		$filter['qualification'] = $split[1];
		$filter['qualification_id'] = $split[0];
		$id = $this->general_model->insert('tbl_field_of_study',$filter);
		$setdata['status'] = true;
		$setdata['id'] = $id;
		
		echo json_encode($setdata);
	}
	
	
	public function ListOfState(){
	   $getStateData = $this->general_model->getData('tbl_country_states',"","array","*","rows");
		
		$setdata['status'] = true;
		$setdata['result'] = $getStateData;
		echo json_encode($setdata);
	}
	
	public function ListOfGotra(){
	   $getGotraData = $this->general_model->getData('tbl_gotra',"","array","*","rows");
		
		$setdata['status'] = true;
		$setdata['result'] = $getGotraData;
		echo json_encode($setdata);
	}
	
	public function ListOFStreet(){ 
		header('Content-Type: application/json'); 
		
		$getListOFStreet = $this->general_model->getData('tbl_street',"","array","*","rows");
		
		$data['status'] = true;
		$data['result'] = $getListOFStreet;
		
		echo str_replace('\/','/',json_encode($data));
    }
	
	public function getListOfQualification(){
	   $getStateData = $this->general_model->getData('tbl_qualification',"","array","*","rows");
		
		$setdata['status'] = true;
		$setdata['result'] = $getStateData;
		
		echo json_encode($setdata);
	}
	
	public function ListOFTab(){ 
		header('Content-Type: application/json'); 
		
		$getListOFStreet = $this->general_model->getData('tbl_tab',"","array","*","rows");
		
		$data['status'] = true;
		$data['result'] = $getListOFStreet;
		
		echo str_replace('\/','/',json_encode($data));
    }
	
	
	public function savestreetdata(){
		$name = trim($this->input->post('name')); 
		$number = trim($this->input->post('number')); 
		$address = trim($this->input->post('address')); 
		$email = trim($this->input->post('email')); 
		$streetid = trim($this->input->post('streetid')); 
		
		$data['name'] = $name;
		$data['number'] = $number;
		$data['address'] = $address;
		$data['email'] = $email;
		$data['street_id'] = $streetid;
		
		$patientid = $this->general_model->insert('tbl_detail',$data);
		
		$setdata['status'] = true;
		$setdata['id'] = $patientid;
		
		echo json_encode($setdata);
	}
	
	


public function savechilddata(){
		$name = trim($this->input->post('name')); 
		$number = trim($this->input->post('number')); 
		$mid = trim($this->input->post('memberid')); 
		
		$data['member_name'] = $name;
		$data['parent_id_FK'] = $mid;
		$data['member_phone'] = $number;
		
		$patientid = $this->general_model->insert('tbl_member',$data);
		
		$setdata['status'] = true;
		$setdata['id'] = $patientid;
		
		echo json_encode($setdata);
	}
	
	
	public function getStreetData(){ 
		header('Content-Type: application/json'); 
		
		if($getdata = $this->admin_model->getStreetData()){
			$data['status'] = true;
			$data['result'] = $getdata;
		}else{
			$data['status'] = false;
			$data['result'] = "No Record found";
		}
		
		echo str_replace('\/','/',json_encode($data));
    }
    
    
    public function getHeadData(){ 
		header('Content-Type: application/json'); 
		
		if($getdata = $this->admin_model->getHeadData()){
			$data['status'] = true;
			$data['result'] = $getdata;
		}else{
			$data['status'] = false;
			$data['result'] = "No Record found";
		}
		
		echo str_replace('\/','/',json_encode($data));
    }
	
	
	
	
	
	public function forgetpwd() { 
       /* Check for required parameter */
        $object_info = $_POST;
        $required_parameter = array('user_id', 'email');
        $chk_error = check_required_value($required_parameter, $object_info);
        if ($chk_error) {
             $resp = array('code' => MISSING_PARAM, 'message' => 'YOU_HAVE_MISSED_A_PARAMETER_' . strtoupper($chk_error['param']));
             $this->response($resp);
        }

        /* Check for email */
        $check_email = $this->common_model->getRecordCount(USER, array('email' => $_POST['email']));
        if($check_email == 0) {
            $resp = array('code' => ERROR, 'message' => 'FAILURE', 'response' => array('error' => 'EMAIL_IS_NOT_EXISTS', 'error_label' => 'This email is not exists in out database'));
            $this->response($resp);
        }

        $random = rand(1,8989898);
        $email = $_POST['email'];
        $condition = array('email' => $_POST['email']);
        $updateArr = array('password' => $random);
        $this->common_model->updatePassword(USER, $updateArr, $condition);

        $msg= " Hello ,<br/><br/>
       
        Your password has been changed :".$random."
        <br/><br/>
        Thanks<br/><br/>
        Ecozoom Team
        ";
   
        // Set to, from, message, etc.
        
          $this->load->library('email');
			$config['protocol'] = 'sendmail';
			$config['mailtype'] = 'html';
			$config['wordwrap'] = TRUE;
	
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");

	    	// Set to, from, message, etc.

			$this->email->initialize($config);

      
        $this->email->from('tarunj295@gmail.com', 'Ecozoom Team');
        $this->email->to($email);

        $this->email->subject('Ecozoom Team: Reset Password');
        $this->email->message($msg);
   
        $result = $this->email->send();
       
        if($result==1){
             $resp = array('code' => SUCCESS, 'message' => 'SUCCESS', 'response' => array('generate_password' => $random,'email_status' => '1')); //Password has been sent to your email id
        }else{
            $resp = array('code' => SUCCESS, 'message' => 'SUCCESS', 'response' => array('generate_password' => $random,'email_status' => '2')); // Something went wrong
        }
    }
    
    
    
	
	/***********************************************************
	@ Function Name: index
  	@ Purpose      : Function for throw login page   
  	@ Created Date : 16/Aug/2014
  	/**********************************************************/
	public function index() {
	  	if($this->session->userdata('userid')){
	  	$this->bannerlist();
	  	
		}
		else{
		$data['title'] = "CP Login";
		$this->load->view('login', $data);
		}
	}
	 
	 
	  function is_logged_in(){
	$is_logged_in = $this->session->userdata('is_logged_in');
        if(!isset($is_logged_in) || $is_logged_in != true){
			$this->load->view('admin_header');
			echo '<div class="permission"><p class="error">You don\'t have permission to access this page. <a href="'.base_url('login').'">Login</a></p></div>';	
			die();		
		}	
      }
	 
	 
	/***********************************************************
	@ Function Name: check
  	@ Purpose      : Function for check login details into database   
  	@ Created Date : 16/Aug/2014
  	/**********************************************************/
	public function check(){
		$email = trim($this->input->get_post('email'));
		$password = trim($this->input->get_post('password'));
		if($email == "" || $password== ""){
			$msg['error'] = "Please fill all required fields.";
			$msg['title'] = "CP Login";
			$this->load->view('login', $msg);
		}else{
			if($result = $this->admin_model->check_login_credencial($email,$password)){
				$newdata = array(
                   'username'  => $email,
                   'userid'     => $result['id'],
                   'logged_in' => TRUE,
                    'password' => $password
              );
              
            	$this->session->set_userdata($newdata);
				redirect('/admin/bannerlist');
			}
			else{
				$msg['error'] = "Invalid email or password.";
				$msg['title'] = "CP Login";
				$this->load->view('login', $msg);
			}
		}
	}
	
	public function forgotpassword(){
		$email = trim($this->input->get_post('email'));	
		$filter['email'] = $email;
		if($query = $this->general_model->getData("tbl_admin",$filter)){
			$random = rand(1,8989898);
			$set['Password'] = $random;
			$update = $this->general_model->update("tbl_admin",$filter,$set);
			
			
			$msg= " Hello Admin ,<br/><br/>
		
			Your password has been changed :".$random."
			<br/><br/>
			Thanks<br/><br/>
			Digital Advertisement Team
			";
		
			$config = Array(
    		'protocol' => 'smtp',
    		'smtp_host' => 'ssl://smtp.googlemail.com',
    		'smtp_port' => 465,
    		'smtp_user' => 'jainshubham090@gmail.com',
    		'smtp_pass' => 'weindians',
    		'mailtype'  => 'html', 
    		'charset'   => 'iso-8859-1'
			);
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");

	    	// Set to, from, message, etc.

			$this->email->initialize($config);

        	$this->email->from('jainshubham090@gmail.com', 'Digital Advertisement');
        	$this->email->to($email); 

        	$this->email->subject('Digital Advertisement: Reset Password');
        	$this->email->message($msg);  
        
    		$result = $this->email->send();
    		
    		if($result==1){
    			echo "1"; //Password has been sent to your email id
    		}else{
    		echo "2"; // Something went wrong
    		}
		}else{
		echo "3"; // Email address not found in our database
		}
		
	}
	
	public function logout(){
			$this->session->unset_userdata();
			$this->session->sess_destroy();
			$data['title'] = "CP Login";
			$this->load->view('login', $data);
	}
	
	
	
	/***********************************************************
	@ Function Name: dashboard
  	@ Purpose      : Function for view home page  
  	@ Created Date : 16/Aug/2014
  	/**********************************************************/
	public function dashboard(){
			$data['title'] = "Welcome Page";
			$this->load->view('patients/dashboard', $data);
			
	}
	
	
   /***********************************************************
	@ Function Name: index
  	@ Purpose      : Function for throw login page   
  	@ Created Date : 22/Aug/2013
  	/**********************************************************/
	public function bannerlist() {
		if($this->session->userdata('userid')){
			$search_by = $this->input->get_post('search_by');
			$config = array();
			$config["base_url"] = base_url() . "admin/bannerlist";
       	 	$config["total_rows"] = $this->admin_model->total_banner_counts();
        	$config["per_page"] =   20;
        	$config["uri_segment"] = 3;
			$config['first_link'] 	= 'First';
			$config['next_link'] 	= 'Next';
			$config['prev_link'] 	= 'Previous';
			$config['last_link'] 	= 'Last';
			$choice = $config['total_rows']/$config['per_page'];
			$config['num_links'] = round($choice);	
			$this->pagination->initialize($config);
        	$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
	  		$data['page'] = $page;
			$data['title'] = "My Med Manager";
			$data['search_by']=($search_by) ? $search_by : $search_by=NULL;
			$data['data'] = $this->admin_model->get_banner_data($config['per_page'],$page);
			$data["links"] = $this->pagination->create_links();
			$data["total_banner"] = $this->admin_model->total_banner_counts();
			
		   $this->load->view('banner/dashboard', $data);
	    }
		else{
			redirect('admin', 'refresh');
		}
	}
	
	
	 /***********************************************************
	@ Function Name: index
  	@ Purpose      : Function for listing patient
  	@ Created Date : 16/july/2015
  	/**********************************************************/
	public function addbanner() {
		if($this->session->userdata('userid')){
			$data['title'] = "Add Banner";
			$data['msg']="";
			$data['errormsg']="";
			
			
			//$this->load->view('patients/header');
			$this->load->view('banner/addbanner', $data);
	    }
		else{
			redirect('admin', 'refresh');
		}
	}
	
	
	public function savebanner(){
		if($this->session->userdata('userid')){
		//	$this->is_logged_in();
		
		/* echo "<pre>";
		print_r($_FILES);
		die; */   
		
		
		
		$campaignname = trim($this->input->post('campaignname')); // Required When Update patient Detail
		$url = trim($this->input->post('url'));
		
		if(($campaignname=="")){
		   $data['errormsg'] = "Please enter all required field"; 
		   $data['msg']= "";
		   $this->load->view('banner/addbanner', $data);
		   return false;
		}
		
		$image_name = "";
		  if($_FILES){ 
        	// Upload job picture
            $random = time();
            $config['upload_path'] = 'upload/';
            $config['allowed_types'] = 'jpg|png|jpeg|bmp';
            $config['file_name'] = $random ;
            $config['encrypt_name'] = TRUE;
            //$this->load->library('image_lib');
            //$this->image_lib->clear();
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
             
             ini_set('upload_max_filesize', '10M');
			 ini_set('memory_limit', '-1');   
             if ($this->upload->do_upload('banner_image')) {
				$imageArray = $this->upload->data();
				
				$message = $imageArray['raw_name'].''.$imageArray['file_ext']; // Job Attachment
					
				$config1['image_library'] = 'gd2';
    			$config1['source_image'] = './upload/'.$message;
    			$config1['create_thumb'] = TRUE;
    			$config1['maintain_ratio'] = TRUE;
    			$config1['width']     = 300;
   	 			$config1['height']   = 377;
    		
 	  			$this->load->library('image_lib', $config);
 	  			$this->image_lib->initialize($config1);
 	  			$this->image_lib->resize();
    			$this->image_lib->clear();
    				
    			unlink('./upload/'.$message);
    			
    			$image_name =$imageArray['raw_name'].'_thumb'.$imageArray['file_ext'];
    				
				
			 } else{
			 	$data['status']=false;
				$data['message']=strip_tags($this->upload->display_errors());
				$msg['msg']= strip_tags($this->upload->display_errors()); 
				$msg['errormsg'] = "";
				$this->load->view('banner/addbanner', $msg);
				return false;
	        }
		  }
		  
		$data['ad_picture'] = $image_name;
		$data['created_date'] = date("Y-m-d H:i:s");
		$data['campaign_name'] = $campaignname;
		$data['url'] = $url;
		
		$patientid = $this->general_model->insert('tbl_ads',$data);
		
		redirect('admin/bannerlist', 'refresh');
		}
		else{
			redirect('admin', 'refresh');
		}
	}
	
	
		
	
	public function deletebanner() {
   if($this->session->userdata('userid')){
	   header('Content-Type: application/json');
	   $getid = $this->input->post('getid');
	   
	   $filter['ads_id_PK'] = $getid;
	   $set['isDelete'] = 1;
	   $set['modified_date'] = date("Y-m-d H:i:s");
	   
	   $getdata = $this->general_model->update('tbl_ads',$filter,$set);
	   echo "1"; 
	 }else{
	   echo "0"; 
	 }
   }
   
   
   public function getbannerdata(){
   		if($this->session->userdata('userid')){
	   		header('Content-Type: application/json');
	   		$getid = $this->input->post('getid');
	   		if($data = $this->admin_model->getbannerdata($getid)){
	   		  	$data['status'] = 'true';
	   		}else{
	   			$data['status'] = 'false';
 	  		}
 	   }else{
 	   $data['status'] = 'false';
 	   }
	   echo json_encode($data);
   }
   
   
	
	public function updatebanner(){
		if($this->session->userdata('userid')){ 
			
	        $set['campaign_name'] = trim($this->input->post('campaignname'));
	        $set['url'] = trim($this->input->post('url'));
	        $set['status']= trim($this->input->post('getstatus'));
	        $set['ad_picture']= trim($this->input->post('imgname'));
	        
	        $filter['ads_id_PK']= trim($this->input->post('pkBannerId'));
	        
	        $getdata = $this->general_model->update('tbl_ads',$filter,$set);
			 echo "1"; 
	    }
		else{
			echo "0";
		}
	}
	
	
	public function configuration() {
		if($this->session->userdata('userid')){
			$data['title'] = "Configuration";
			$data['data'] = $this->admin_model->getconfiguration();
			//$this->load->view('patients/header');
			$this->load->view('banner/configuration', $data);
	    }
		else{
			redirect('admin', 'refresh');
		}
	}
	
	public function saveconfig(){
		if($this->session->userdata('userid')){
		$no_of_ads = trim($this->input->post('no_of_ads')); // Required When Update patient Detail
		$shuffle_interval = trim($this->input->post('shuffle_interval'));
		$config_id = trim($this->input->post('config_id'));
		
		
		$set['shuffle_interval'] = $shuffle_interval;
		$set['no_of_ads'] = $no_of_ads;
		$filter['config_id_PK'] = $config_id;
		
		$getdata = $this->general_model->update('tbl_config',$filter,$set);
			echo "1";
		}
		else{
			echo "0";
		}
	}
	
	
	public function searchBanner($search_key=FALSE) {
	 
	 	if($this->input->get_post('search_key')){
			$search_key = $this->input->get_post('search_key');
		}else{
			$search_key=($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		}
		
		if($this->session->userdata('userid')){
			$config = array();
			$config["base_url"] = base_url() . "admin/searchBanner/".$search_key;
       	 	$config["total_rows"] = $this->admin_model->search_banner_counts($search_key);
        	$config["per_page"] =   20;
        	$config["uri_segment"] = 3;
			$config['first_link'] 	= 'First';
			$config['next_link'] 	= 'Next';
			$config['prev_link'] 	= 'Previous';
			$config['last_link'] 	= 'Last';
		
			$choice = $config['total_rows']/$config['per_page'];
			$config['num_links'] = round($choice);	
			$this->pagination->initialize($config);
        	$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0; 
        	$data['search_key']=($search_key) ? $search_key : $search_key="";
        	$data['page'] = $page;
			$data['title'] = "Digital Ads";
			$data['data'] = $this->admin_model->search_banner_data($search_key,$config['per_page'],$page);
			$data["links"] = $this->pagination->create_links();
			$data["total_patients"] = $this->admin_model->total_banner_counts();
			
			$this->load->view('banner/dashboard', $data);
	    }
		else{
			redirect('admin', 'refresh');
		}
	}
	
	public function ajaxtest(){ 
	$this->load->view('banner/ajaxtest'); 
	}
	
	
	#####  This function will proportionally resize image ##### 
function normal_resize_image($source, $destination, $image_type, $max_size, $image_width, $image_height, $quality){
    
    if($image_width <= 0 || $image_height <= 0){return false;} //return false if nothing to resize
    
    //do not resize if image is smaller than max size
    if($image_width <= $max_size && $image_height <= $max_size){
        if(save_image($source, $destination, $image_type, $quality)){
            return true;
        }
    }
    
    //Construct a proportional size of new image
    $image_scale    = min($max_size/$image_width, $max_size/$image_height);
    $new_width      = ceil($image_scale * $image_width);
    $new_height     = ceil($image_scale * $image_height);
    
    $new_canvas     = imagecreatetruecolor( $new_width, $new_height ); //Create a new true color image
    
    //Copy and resize part of an image with resampling
    if(imagecopyresampled($new_canvas, $source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)){
        save_image($new_canvas, $destination, $image_type, $quality); //save resized image
    }

    return true;
}

##### This function corps image to create exact square, no matter what its original size! ######
function crop_image_square($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality){
    if($image_width <= 0 || $image_height <= 0){return false;} //return false if nothing to resize
    
    if( $image_width > $image_height )
    {
        $y_offset = 0;
        $x_offset = ($image_width - $image_height) / 2;
        $s_size     = $image_width - ($x_offset * 2);
    }else{
        $x_offset = 0;
        $y_offset = ($image_height - $image_width) / 2;
        $s_size = $image_height - ($y_offset * 2);
    }
    $new_canvas = imagecreatetruecolor( $square_size, $square_size); //Create a new true color image
    
    //Copy and resize part of an image with resampling
    if(imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)){
        save_image($new_canvas, $destination, $image_type, $quality);
    }

    return true;
   }

     public function uploadFile() {
        if ($this->input->is_ajax_request() == FALSE) {
            echo "Invalid URL Call";
            exit();
        }

        $response = array('status' => false);

        if($_FILES){ 
        	// Upload job picture
            $random = time();
            $config['upload_path'] = 'upload/';
            $config['allowed_types'] = '*';
            $config['file_name'] = $random ;
            $config['encrypt_name'] = TRUE;
            //$this->load->library('image_lib');
            //$this->image_lib->clear();
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
             
             ini_set('upload_max_filesize', '10M');
			 ini_set('memory_limit', '-1');   
             if ($this->upload->do_upload('profile-image')) {
				
				$imageArray = $this->upload->data();
				
				$message = $imageArray['raw_name'].''.$imageArray['file_ext']; // Job Attachment
					
				$config1['image_library'] = 'gd2';
    			$config1['source_image'] = './upload/'.$message;
    			$config1['create_thumb'] = TRUE;
    			$config1['maintain_ratio'] = TRUE;
    			$config1['width']     = 300;
   	 			$config1['height']   = 377;
    		
 	  			$this->load->library('image_lib', $config);
 	  			$this->image_lib->initialize($config1);
 	  			$this->image_lib->resize();
    			$this->image_lib->clear();
    				
    			unlink('./upload/'.$message);
    			
    			$image_name =$imageArray['raw_name'].'_thumb'.$imageArray['file_ext'];
    			
    			
				$data['image_url'] =  base_url().'upload/'.$image_name; // Job Attachment
				$data['status']=true;
				$data['image_name'] = $image_name;
				
				// echo "<br/>Image : <img style='margin-left:10px;' src='uploads/".$imgn."'>";
				
			 } else{
			 	$data['status']=false;
				$data['message']=strip_tags($this->upload->display_errors());
				echo json_encode($data);
				return false;
	        }
	        echo json_encode($data);
		  }
    }
    
    

	public function getData(){ 
		header('Content-Type: application/json'); 
		
		if($getdata = $this->admin_model->getData()){
			$data['status'] = true;
			$data['result'] = $getdata;
		}else{
			$data['status'] = false;
			$data['result'] = "No Record found";
		}
		
		echo str_replace('\/','/',json_encode($data));
    }
    
    
    public function adClick($ads_id_PK){ 
		header('Content-Type: application/json'); 
		
		if($ads_id_PK==""){
		    $data['status'] = false;
			echo str_replace('\/','/',json_encode($data));
			return false;
		}
    	
		if($getdata = $this->admin_model->adClick($ads_id_PK)){
			$data['status'] = true;
		}else{
			$data['status'] = false;
		}
	 
	 	echo str_replace('\/','/',json_encode($data));
    }
    
    
    public function getDataVideo(){ 
		header('Content-Type: application/json'); 
		
		if($getdata = $this->admin_model->getDataVideo()){
			$data['status'] = true;
			$data['medication'] = $getdata;
		}else{
			$data['status'] = false;
			$data['medication'] = "No Record found";
		}
		
		echo str_replace('\/','/',json_encode($data));
    }
    
    public function getDataAudio($getpage){ 
		header('Content-Type: application/json'); 
		
		if($getpage==0){
			$start=0;
		}
		else{
		   $start=$getpage*15;
		}
		   $end=15;
		
		if($getdata = $this->admin_model->getDataAudio($start,$end)){
			$data['status'] = true;
			$data['medication'] = $getdata;
		}else{
			$data['status'] = false;
			$data['medication'] = "No Record found";
		}
		
		echo str_replace('\/','/',json_encode($data));
    }
    
    public function timezonetest_(){
	    date_default_timezone_set('Asia/Kolkata');
	    echo  date("Y-m-d H:i:s");
		echo "</br>";
		echo date('I');
		echo date_default_timezone_get();
	
	die;
	   
	   $utc_date = DateTime::createFromFormat(
    'Y-m-d H:i:s',
    $getCurrentTime,
    new DateTimeZone('UTC')
	);

	$acst_date = clone $utc_date; // we don't want PHP's default pass object by reference here
	$acst_date->setTimeZone(new DateTimeZone('Asia/Kolkata'));

	echo 'UTC:  ' . $utc_date->format('Y-m-d g:i A');  // UTC:  2011-04-27 2:45 AM 
	echo "</br>";
	echo 'ACST: ' . $acst_date->format('Y-m-d g:i A'); // ACST: 2011-04-27 12:15 PM

	die;
	}
	
	
	public function timezonetest(){
	    date_default_timezone_set('Asia/Kolkata');
		$date =  date("Y-m-d H:i:s");
		echo date("Y-m-d H:i:s",strtotime($date));
		echo "</br>";
		//echo date('O');
		echo date_default_timezone_get();
		}
		
		
		public function locationdata(){
		
		$data = $_POST['data'];
		$output = $data;
   		$outputdata=explode(',',$data);
		$current_time=@date("Y-m-d H:i:s");
		//echo $output;
		//351737050598145##22.7295594##75.8650538##2013-3-8 17:7:51## -- 2013-03-08 17:07:54
		$phone_no = $outputdata[0];
		$latitude  = $outputdata[1];
		$longitude = $outputdata[2];
		
		$data1['imei'] = $phone_no;
		$data1['latitude'] = $latitude;
		$data1['longitude'] = $longitude;
		$data1['datetime'] = $current_time;
		
		$id = $this->general_model->insert('tbl_location',$data1);
		$setdata['status'] = true;
		$setdata['id'] = $id;
		echo str_replace('\/','/',json_encode($setdata));
		
		}
	
}
