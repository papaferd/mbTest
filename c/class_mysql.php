<?php
namespace c;

defined('BASES') OR exit('No direct script access allowed');

 /**
 * class DataMYSQL - Класс работы с базой данных, ошибки пишет в лог через loggingException
 */

class DataMYSQL{
	
 /**
 * $dbh - Обьект Базы mysqli
 */	
	var $dbh;
	
 /**
 * __construct - Конструктор, создает обьект $dbh, устанавливает кодировку UTF8
 */
	public function __construct() {	
		
		
		if(empty(self::$dbh)){
			try{
				
				$this->dbh = new \mysqli($GLOBALS['mysql_host'], $GLOBALS['mysql_login'],  $GLOBALS['mysql_pass'], $GLOBALS['mysql_db']);
				
			}catch(loggingException $e){
				throw new loggingException('Can not create DB connection',mysqli_error($this->dbh));
			}
			if(!$this->dbh->set_charset('utf8'))throw new loggingException('Can not set_charset on DB',mysqli_error($this->dbh));
		}
		
		
   	}

 /**
 * startTransaction - пробует начать транзакцию
 */
	public function startTransaction() {       
		if(!$this->dbh->begin_transaction())throw new loggingException('Can not start transaction',mysqli_error($this->dbh));
	}
	
 /**
 * commitTransaction - пробует COMMIT транзакцию
 */	
	public function commitTransaction() {   
		if(!$this->dbh->commit())throw new loggingException('Can not commit Transaction',mysqli_error($this->dbh));
	} 
	
 /**
 * __destruct - деструктор, закрывает соединение
 */		
	public function __destruct() {       
		$this->dbh->close();
	}

 /**
 * getMultyQuery - Выполняет MultyQuery Mysqli
 */	
	public function getMultyQuery($query='')
	{

		if($query!='')
		{

			if(!$this->dbh->multi_query($query))throw new loggingException('Can not Get Multy Query',mysqli_error($this->dbh));
			
		}else{
			
			throw new loggingException('EMPTY Multy QUERY',mysqli_error($this->dbh));
		}
		
	}

 /**
 * getMultygetQueryQuery - Выполняет Query Mysqli
 */	
	public function getQuery($query='')
	{
		if($query!='')
		{

			if(!$this->dbh->query($query))throw new loggingException('Can not Get Query',mysqli_error($this->dbh));
			
		}else{
			
			throw new loggingException('EMPTY QUERY',mysqli_error($this->dbh));
		}
		
	}

 /**
 * getLastId - Выполняет mysqli_insert_id
 */	
	public function getLastId(){
		
		return mysqli_insert_id($this->dbh);
	}
	
 /**
 * getData - Получает данные и передает их в массиве
 */		
	public function getData($query='')
	{
		if($query!='')
		{
			$data = array();
			
			$query_list = $this->dbh->query($query);
			
				if(!$this->dbh->error)
				{

						while ($row = $query_list->fetch_array(MYSQLI_ASSOC)) {
							$data[] = $row;
						}
						mysqli_free_result($query_list);
						return $data;

				}else{
					throw new loggingException('ERROR in QUERY '.$query,$this->dbh->error);
				}
			
		}else{
			
			throw new loggingException('EMPTY QUERY',$query);
		}
		
	}
	
	
}
