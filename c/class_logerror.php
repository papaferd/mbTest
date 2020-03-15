<?php
namespace c;
use \Exception;
use \Throwable;

defined('BASES') OR exit('No direct script access allowed');

 /**
 * loggingException - Проверяет классы Throwable и Exception - наследуется от одного из них в приоритете Throwable
 */	

if(!class_exists('Throwable')){	
	class loggingDir extends \Exception{
		public function __construct($errors='') {	
			Exception::__construct($errors); 
		}
	}	
}else{	
	class loggingDir extends \Throwable{
		public function __construct($errors='') {	
			Throwable::__construct($errors);
		}
	}
}

class loggingException extends loggingDir{	
var $Log_Error;		
		public function __construct($messages,$error='') {	
			$this->Log_Error = $messages.' >> '.$error;			
			$this->logdir($this->Log_Error);
			loggingDir::__construct($this->Log_Error); 
		}	
		public function logdir($message){
$message = date(r).' || '.$message.' || QUERY_STRING: '.$_SERVER['QUERY_STRING'].' || REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR'].' || SCRIPT_FILENAME: '.$_SERVER['SCRIPT_FILENAME'].' 
--------------------------------------------
';
		$dirl = $_SERVER['DOCUMENT_ROOT'].'/log/';	
		if (!is_dir($dirl))
		{
		   if(!mkdir($dirl, 0755)){
			   echo "Not a create log dir";die;		   
		   }
			
		}
		
		$log = $dirl.'log.l';
		if(!file_put_contents($log, $message,  FILE_APPEND)){echo 'Not a save log file';die;}	
		
	}
	
}


