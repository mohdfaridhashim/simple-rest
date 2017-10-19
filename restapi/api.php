<?php
    header('Access-Control-Allow-Origin: *'); 
	/* 
		This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 You code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers
			
		Add This extension for localhost checking :
			Chrome Extension : Advanced REST client Application
			URL : https://chrome.google.com/webstore/detail/hgmloofddffdnphfgcellkdfbfbjeloo
		
		I used the below table for demo purpose.
		CREATE TABLE `conversation` (
			`c_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`user_one` int(11) NOT NULL,
			`user_two` int(11) NOT NULL,
			`ipaddress` varchar(30) DEFAULT NULL,
			`time_chat` int(11) DEFAULT NULL,
			FOREIGN KEY (user_one) REFERENCES users(user_id),
			FOREIGN KEY (user_two) REFERENCES users(user_id)
		);

		CREATE TABLE `conversation_reply` (
			`cr_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`reply` text,
			`user_id_fk` int(11) NOT NULL,
			`ip` varchar(30) NOT NULL,
			`time_chat` int(11) NOT NULL,
			`c_id_fk` int(11) NOT NULL,
			FOREIGN KEY (user_id_fk) REFERENCES users(user_id),
			FOREIGN KEY (c_id_fk) REFERENCES conversation(c_id)
		);
		CREATE TABLE IF NOT EXISTS `messages` (
		  `id` int(11) NOT NULL,
		  `conversation_id` int(11) NOT NULL,
		  `user_from` int(11) NOT NULL,
		  `user_to` int(11) NOT NULL,
		  `message` text NOT NULL,
		  PRIMARY KEY (`id`)
		);
		CREATE TABLE IF NOT EXISTS `users` (
		  `user_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_fullname` varchar(25) NOT NULL,
		  `user_email` varchar(50) NOT NULL,
		  `user_password` varchar(50) NOT NULL,
		  `user_status` tinyint(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`user_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 	*/
	
	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";
		public $result = array();

		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "root";
		const DB = "chatsystem";
		
		private $db = NULL;
	
		public function __construct(){
			$this->result['statusCODE'] = '0';
			$this->result['status'] = 'FAIL';
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				mysqli_select_db($this->db,self::DB);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD> JSON FORMAT
		 */
		
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$data = $this->_request;
			$data = json_decode($data);
			$email = $data->email;
			$password = $data->pwd;
			$this->result['message'] = 'invalid authentication';
			//$email = $this->_request['email'];		
			//$password = $this->_request['pwd'];
			
			// Input validations
			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysqli_query($this->db,"SELECT user_id, user_fullname, user_email FROM users WHERE user_email = '$email' AND user_password = '".$password."' LIMIT 1");
					if(mysqli_num_rows($sql) > 0){
						$this->result = mysqli_fetch_array($sql,MYSQLI_ASSOC);
						
						// If success everythig is good send header as "OK" and user details
						$this->successResult();
						unset($this->result['message']);
						$this->response($this->json($this->result), 200);
					}
					$this->response($this->result, 200);	// If no records "No Content" status
				}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$this->response($this->json($this->result), 400);
		}
		
		private function users(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$sql = mysqli_query($this->db,"SELECT user_id, user_fullname, user_email FROM users WHERE user_status = 1");
			if(mysqli_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
					$this->result['data'][] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->successResult();
				$this->response($this->json($this->result), 200);
			}
			$this->response($this->result,200);	// If no records "No Content" status
		}

		private function groupmembers(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			$sql = mysqli_query($this->db,"SELECT user_two FROM users U, conversation C WHERE C.user_one = U.user_id and U.user_id = '$id'");
			if(mysqli_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
					$this->result['data'][] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->successResult();
				$this->response($this->json($this->result), 200);
			}
			$this->response($this->result,200);	// If no records "No Content" status
		}
		
		private function profile(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){				
				$sql = mysqli_query($this->db,"SELECT user_id, user_fullname, user_email FROM users WHERE user_id = {$id}");
				if(mysqli_num_rows($sql) > 0){
					
					$this->result = mysqli_fetch_array($sql,MYSQLI_ASSOC);
					// If success everythig is good send header as "OK" and return list of users in JSON format
					$this->successResult();
					$this->response($this->json($this->result), 200);
				}
				$this->response($this->result,200);	// If no records "No Content" status
			}else
				$this->response($this->result,200);	// If no records "No Content" status
			
		}

		
		/*private function deleteUser(){
			// Cross validation if the request method is DELETE else it will return "Not Acceptable" status
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){				
				mysql_query("DELETE FROM users WHERE user_id = $id");
				$success = array('status' => "Success", "msg" => "Successfully one record deleted.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}*/
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}

		private function successResult()
		{
			$this->result['statusCODE'] = '1';
			$this->result['status'] = 'SUCCESS';
		}

		// private message
		private function listTalk(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$result = array();
			$user_one = (int)$this->_request['id'];
			if(!is_numeric($user_one)){
				$this->response('',204);	// If no records "No Content" status
			}
			$query = "SELECT u.user_id,c.c_id,u.user_email FROM conversation c, users u WHERE CASE WHEN c.user_one = '$user_one' THEN c.user_two = u.user_id WHEN c.user_two = '$user_one' THEN c.user_one= u.user_id END 
			AND ( c.user_one ='$user_one' OR c.user_two ='$user_one' ) Order by c.c_id DESC Limit 20";
			$sql = mysqli_query($this->db,$query);
			if($sql){
				if(mysqli_num_rows($sql) > 0){
					
					while($rlt = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
						$c_id=$rlt['c_id'];
						$cquery= mysqli_query($this->db,"SELECT R.cr_id,R.time_chat,R.reply FROM conversation_reply R WHERE R.c_id_fk='$c_id' ORDER BY R.cr_id DESC LIMIT 1");
						if($cquery){
							$crow=mysqli_fetch_array($cquery,MYSQLI_ASSOC);
							$rlt['detail'] = $crow;
							$this->result['chat'][] = $rlt;
						}		
					}
					// If success everythig is good send header as "OK" and return list of users in JSON format
					$this->successResult();
					$this->response($this->json($this->result), 200);
				}
			}
			$this->response($this->json($this->result),200);	// If no records "No Content" status

		}

		private function showTalk(){
			if($this->get_request_method() != "GET"){
				$this->response($this->result,406);
			}
			$cid = (int)$this->_request['cid'];
			$query = "SELECT R.cr_id,R.c_id_fk,R.time_chat,R.reply,U.user_id,U.user_fullname,U.user_email FROM users U, conversation_reply R WHERE U.user_id=R.user_id_fk AND R.c_id_fk='$cid' ORDER BY R.cr_id ASC LIMIT 20";
			$sql = mysqli_query($this->db,$query);
			if($sql){
				if(mysqli_num_rows($sql) > 0){
					$result = array();
					while($rlt = mysqli_fetch_array($sql,MYSQLI_ASSOC)){
						$this->result['detail'][] = $rlt;
					}
					$this->successResult();
					$this->response($this->json($this->result), 200);
				}
			}
			$this->response($this->json($this->result),200);
		}

		private function createTalk(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$user_one = (int)$this->_request['user_id'];
			$user_two = (int)$this->_request['user_two'];
			if($user_one != $user_two)
			{
				$query="SELECT c_id FROM conversation WHERE (user_one='$user_one' and user_two='$user_two') or (user_one='$user_two' and user_two='$user_one') ";
				$sql = mysqli_query($this->db,$query);
				if($sql){
					if(mysqli_num_rows($sql)<1){
						$time=time();
						$ip=$_SERVER['REMOTE_ADDR'];
						$query = mysqli_query($this->db,"INSERT INTO conversation(user_one,user_two,ipaddress,time_chat) VALUES ('$user_one','$user_two','$ip','$time')");
						if($query){
							$q=mysqli_query($this->db,"SELECT c_id FROM conversation WHERE user_one='$user_one' ORDER BY c_id DESC limit 1");
							if($q){
								$this->result=mysqli_fetch_array($q,MYSQLI_ASSOC);
								$this->successResult();
								$this->response($this->json($this->result), 200);
							}
						}
						$this->response($this->json($this->result),200);
					}else{
						$this->result=mysqli_fetch_array($sql,MYSQLI_ASSOC);
						$this->successResult();
						$this->response($this->json($this->result), 200);
					}
				}
				$this->response($this->json($this->result),200);
			
			}
			$this->response($this->json($this->result),200);
		}

		private function replyTalk(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$data = $this->_request;
			$data = json_decode($data);
			$uid = (int)$data->sender;
			if(!is_numeric($uid)){
				$this->response($this->json($this->result),204);
			}
			$reply = $data->msg;
			$cid = (int)$data->conversation;
			if(!is_numeric($cid)){
				$this->response($this->json($this->result),204);
			}
			$receiver = (int)$data->receiver;
			$time=time();
			$ip=$_SERVER['REMOTE_ADDR'];
			$query = "INSERT INTO conversation_reply (user_id_fk,reply,ip,time_chat,c_id_fk,to_user_one) VALUES ('$uid','$reply','$ip','$time','$cid','$receiver')";
    		$result = mysqli_query($this->db,$query);
    		if($result){
    			$this->successResult();
    			$this->response($this->json($this->result),200);
    		}
    		$this->response($this->json($this->result),200);
		}

		private function upload(){

		}
	}
	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();
?>