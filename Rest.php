<?php
class Rest {

	public $_content_type = "application/json";
	public $_request = array();
	
	private $_method = "";		
	private $_code = 200;
	
	protected $db = NULL;
	
	public function __construct()
	{
		$this->inputs();
	}
	
	private function get_referer()
	{
		return $_SERVER['HTTP_REFERER'];
	}

	private function get_status_message()
	{
		$status = array(
					100 => 'Continue',  
					101 => 'Switching Protocols',  
					200 => 'OK',
					201 => 'Created',  
					202 => 'Accepted',  
					203 => 'Non-Authoritative Information',  
					204 => 'No Content',  
					205 => 'Reset Content',  
					206 => 'Partial Content',  
					300 => 'Multiple Choices',  
					301 => 'Moved Permanently',  
					302 => 'Found',  
					303 => 'See Other',  
					304 => 'Not Modified',  
					305 => 'Use Proxy',  
					306 => '(Unused)',  
					307 => 'Temporary Redirect',  
					400 => 'Bad Request',  
					401 => 'Unauthorized',  
					402 => 'Payment Required',  
					403 => 'Forbidden',  
					404 => 'Not Found',  
					405 => 'Method Not Allowed',  
					406 => 'Not Acceptable',  
					407 => 'Proxy Authentication Required',  
					408 => 'Request Timeout',  
					409 => 'Conflict',  
					410 => 'Gone',  
					411 => 'Length Required',  
					412 => 'Precondition Failed',  
					413 => 'Request Entity Too Large',  
					414 => 'Request-URI Too Long',  
					415 => 'Unsupported Media Type',  
					416 => 'Requested Range Not Satisfiable',  
					417 => 'Expectation Failed',  
					500 => 'Internal Server Error',  
					501 => 'Not Implemented',  
					502 => 'Bad Gateway',  
					503 => 'Service Unavailable',  
					504 => 'Gateway Timeout',  
					505 => 'HTTP Version Not Supported');
		return ($status[$this->_code])?$status[$this->_code]:$status[500];
	}

	private function set_headers()
	{
		header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
		header("Content-Type:".$this->_content_type);
	}

	protected function response($data,$status)
	{
		$this->_code = ($status)?$status:200;
		$this->set_headers();
		echo $data;
		exit;
	}

	protected function json($data)
	{
		if(is_array($data)){
			return json_encode($data);
		}
	}

	protected function processApi()
	{
		$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
		if((int)method_exists($this,$func) > 0)
			$this->$func();
		else
			$this->response($this->json($this->get_status_message()),404);
	}

	protected function dbConnect(){
		$this->db = mysqli_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
		if($this->db)
			mysqli_select_db($this->db,self::DB);
	}
}