<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*

	*Class Name	: generalmodel.php
	*Date Created	: March 20,2014
	*Created By	: @@@@
	*Purpose	: General Model :-
					1) Common methods that use in most model.
					2) Methods that are of same type.

*/
class General_model extends CI_Model {
	
	public function __construct() {
        /* Call the Model constructor */
        parent::__construct();
    }
	
	/*	
		*Function Name				: 	insert
		*Date Created				:	February 27,2014
		*Created By				:	@@@@
		*Purpose				: 	Function to add a new row to database
		*Input Params and Type                  : 	$tableName:string,$data:array
		*Output Params and Type                 : 	insert_id:int
	*/
	public function insert($tableName = '', $data) {
		$this->db->insert($tableName, $data);
		return $this->db->insert_id();
	}
	 
	/*	
		*Function Name				: 	update
		*Date Created				:	February 27,2014
		*Created By				:	@@@@
		*Purpose				: 	Function to modify a row/s of database based on multiple/single where clauses
		*Input Params and Type                  : 	$tableName:string,$filters:array,$data:array
		*Output Params and Type                 : 	TRUE:int,FALSE:int
	*/
	public function update($tableName = '', $filters = array(), $data) {
		if(is_array($filters) && count($filters) > 0){
			foreach($filters as $field => $value) 
				$this->db->where($field, $value);

			$this->db->update($tableName, $data);
			return TRUE;
		}else
			return FALSE;
	}
	
	/*	
		*Function Name				: 	delete
		*Date Created				:	February 27,2014
		*Created By				:	@@@@
		*Purpose				: 	Function to delete a row/s of database based on multiple/single where clauses
		*Input Params and Type                  : 	$tableName:string,$filters:array
		*Output Params and Type                 : 	TRUE:int,FALSE:int
	*/
	
	public function delete($tableName = '', $filters = array()) {
		if(is_array($filters) && count($filters) > 0){
			foreach($filters as $field => $value) 
				$this->db->where($field, $value);
		    $this->db->delete($tableName);
		    return TRUE;
		}else
			return FALSE;
	}
	
	/*	
		*Function Name				: 	getData
		*Date Created				:	February 27,2014
		*Created By				:	@@@@
		*Purpose				: 	Function to get data
		*Input Params and Type                  : 	$tableName:string,$filters:array or string ,$returnType:string,$select:string,$noRow:string,$orderBy:string,$orderFormat:string,$join:array
		*Output Params and Type                 : 	array:string,FALSE:int
	*/
	
	public function getData($tableName = '', $filters = array(), $returnType='', $select = '', $noRow = '', $printRowCount='', $orderBy = '', $orderFormat = 'desc', $join = array(), $groupBy = array()) { 
	
		/* Add Select arguments */
		if($select != '')
			$this->db->select($select);
		
		/* Add Filters */
		if(is_array($filters) && count($filters) > 0){
		  foreach($filters as $field => $value) 
		  	  if(is_numeric($field)){
				$this->db->where($value,NULL,false);  
			  }else{
		  	   $this->db->where($field, $value);
		  	  } 
		}elseif(!is_array($filters) && $filters != ''){
				$this->db->where($filters);
		}
		
		/* Apply joins */
		if(is_array($join) && count($join > 0)){   
			foreach($join as $joinTable){
			   $this->db->join($joinTable['tableName'], $joinTable['conditionLeft'].'='. $joinTable['conditionRight'], $joinTable['type']);
			}   
		}
                
		/* Apply Group By Clause */ 
                if(is_array($groupBy) && count($groupBy > 0))
                    $this->db->group_by($groupBy);
                
		if($orderBy != '')
                    $this->db->order_by($orderBy, $orderFormat);
                    $result = $this->db->get($tableName);
		
		 //echo $this->db->last_query();
		if($printRowCount != '')
			return $result->num_rows();
                   // echo $this->db->last_query();
		if($result->num_rows() > 0 ){	
			if($noRow == 'rows'){
				if($returnType == 'array')
					return $result->result_array();
				else
					return $result->result();
			}else{
				if($returnType == 'array')
					return $result->row_array();
				else
					return $result->row();
			}
		}else
			return FALSE;
	}
	
	/*	
		*Function Name				: 	getPaginatedData
		*Date Created				:	February 27,2014
		*Created By				:	@@@@
		*Purpose				: 	Function for Pagination
		*Input Params and Type                  : 	$tableName:string,$filters:array,$returnType:string,$perPage:int,$start:int,$orderBy:string,$orderFormat:string,$join:array,$select:string
		*Output Params and Type                 : 	array:string,FALSE:int
	*/
	
	public function getPaginatedData($tableName = '',$filters=array(), $returnType='', $perPage= NULL, $start= NULL, $orderBy='', $orderFormat='asc', $join = array(), $select = '') {
		
		
		if($select != '')
			$this->db->select($select);
		
		/*Apply where clause*/
		if(is_array($filters) && count($filters) > 0){
			foreach ($filters as $field => $value) 
				$this->db->where($field, $value);
		}elseif(!is_array($filters) && $filters != ''){
				$this->db->where($filters);
		}
		
		/*Apply joins */
		if(is_array($join) && count($join > 0)){ 
			foreach($join as $joinTable){
			   $this->db->join($joinTable['tableName'], $joinTable['conditionLeft'].'='. $joinTable['conditionRight'], $joinTable['type']);
			}   
		}
		
		$this->db->limit($perPage, $start);
		$this->db->order_by($orderBy, $orderFormat);
		$result = $this->db->get($tableName);
		
		//echo $this->db->last_query(); die;
		if($result->num_rows() > 0){
			if($returnType == 'array'){
				 return $result->result_array();
			}else{
				 return $result->result();
			}
		}else
			return FALSE;
	}
	
	
	
	/*	
		*Function Name				: 	getSearchPaginatedData
		*Date Created				:	May 28,2014
		*Created By				:	@@@@
		*Purpose				: 	Function for Search result with Pagination
		*Input Params and Type                  : 	$tableName:string,$filters:array,$returnType:string,$perPage:int,$start:int,$orderBy:string,$orderFormat:string,$join:array,$select:string
		*Output Params and Type                 : 	array:string,FALSE:int
	*/
	
	public function getSearchPaginatedData($tableName = '',$filters=array(), $returnType='', $perPage= NULL, $start= NULL, $orderBy='', $orderFormat='asc', $join = array(), $select = '', $like = '') {
		
		if($select != '')
			$this->db->select($select);
		
		/*Apply where clause*/
		if(is_array($filters) && count($filters) > 0){
			foreach ($filters as $field => $value) 
				$this->db->where($field, $value);
		}elseif(!is_array($filters) && $filters != ''){
				$this->db->where($filters);
		}
		
		/*Apply joins */
		if(is_array($join) && count($join > 0)){ 
			foreach($join as $joinTable){
			   $this->db->join($joinTable['tableName'], $joinTable['conditionLeft'].'='. $joinTable['conditionRight'], $joinTable['type']);
			}   
		}
		
		if($like != '')
			$this->db->like($like);
		
		$this->db->limit($perPage, $start);
		$this->db->order_by($orderBy, $orderFormat);
		$result = $this->db->get($tableName);
		if($result->num_rows() > 0){
			if($returnType == 'array'){
				 return $result->result_array();
			}else{
				 return $result->result();
			}
		}else
			return FALSE;
	}
	
	
	
	/*	
		*Function Name				: 	getTotalRows
		*Date Created				:	February 27,2014
		*Created By				:	@@@@
		*Purpose				: 	Function to return total number of rows
		*Input Params and Type                  : 	$tableName:string,$filters:array,$join:array
		*Output Params and Type                 : 	array:string,FALSE:int
	*/
	
	public function getTotalRows($tableName = '', $filters = array(), $join = array()) {
		
		if(is_array($join) && count($join > 0)){
			foreach($join as $joinTable){
			   $this->db->join($joinTable['tableName'], $joinTable['conditionLeft'].'='. $joinTable['conditionRight'], $joinTable['type']);
			}
		}
		
		if(is_array($filters) && count($filters) > 0){
			foreach ($filters as $field => $value) 
				$this->db->where($field, $value);
		}elseif(!is_array($filters) && $filters != ''){
				$this->db->where($filters);
		}
		
		$result = $this->db->get($tableName);
		
		if($result->num_rows() > 0)
			return $result->num_rows();
		else
			return 0;
	}
	 /* Function for checking duplicate field in database @@@@ */ 
        function api_duplicate_check($table, $field, $compared,$except=NULL){
                $query='SELECT '.$field.' FROM '.$table.' WHERE '.$field.' = "'.$compared.'"';
                if($except!=NULL){
                        $query.=' and user_id not in('.$except.')';
                }
                $query = $this->db->query($query);
                if($query->num_rows()>0){  //record already avaliable .
                        return 1;
                }
                else {
                        return 0; //record not avaiable avaliable .
                }
        }
		
		
		
		public function send_mail($event,$theme,$user){
		$this->load->library('email');
		$config['protocol'] = 'sendmail';
		$config['mailtype'] = 'html';
		$config['wordwrap'] = TRUE;
		$this->load->library('email');
		$this->email->initialize($config);
		
		$mailContent = 'Hello '.$user['name'].
		
		'<br>'.
		$theme['description'].' Thank You!<br>'. 
		'<br>'.
		'When - '.$event['date'].'<br>'.
		'Time - '.$event['time']. '<br>'.
		'Where - '.$event['location']. '<br>'.
		'Code - '.$user['unique_code']. '<br>'.
		'QR Code - <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl='.$user['unique_code'].'&choe=UTF-8" title="Link to Google.com" />';
		
		$to = $user['email'];
		$from =  'shubham_sipl@systematixindia.com';
		$subject = "Whodunnit: Game Invitation"; 
		if(count($this->config->item('cust_email')))
		$this->load->library('email', $this->config->item('cust_email'));
		else 
		$this->load->library('email');
		$this->load->library('email');
		$this->email->initialize($config);
		$this->email->from($from, 'Game Invitation');
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($mailContent);
		$this->email->send();
		return true;
	}
 		
}

/* End of file general_model.php */
/* Location: ./application/models/admin/general_model.php */
