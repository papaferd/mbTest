<?php
namespace c;
defined('BASES') OR exit('No direct script access allowed');


class ParseJSON{
	
 /**
 * $JSON - Раскодированный JSON
 */	
  var $JSON;
	
 /**
 * $DB - Обьект базы
 */
  var $DB;
	
 /**
 * $qINSERTinAPP - Массив Запрос для Вставки в таблицу APP
 */
  var $qINSERTinAPP;	
	
 /**
 * $qINSERTinAVERAGE - Массив Запрос для Вставки в таблицу Avarege
 */
  var $qINSERTinAVERAGE;
	
 /**
 * $qINSERTinPRICE - Массив Запрос для Вставки в таблицу Price
 */
  var $qINSERTinPRICE;
	
 /**
 * $qINSERTinRANK - Массив Запрос для Вставки в таблицу Rank
 */
  var $qINSERTinRANK;
	
 /**
 * $qUPDATE - Запрос для Обновления
 */
  var $qUPDATE;	

 /**
 * __construct - Конструктор, обнуляет масиивы, декодирует JSON
 */	
	public function __construct($json) {	
		
		$qINSERTinAPP = array();
		$qINSERTinAVERAGE = array();
  		$qINSERTinPRICE = array();
		$qINSERTinRANK = array();
		$qUPDATE = array();
		
		if(file_exists($json)){
			if(!$this->JSON = json_decode(file_get_contents(urlencode($json)), true))throw new loggingException('I`m not decode JSON file',$json);
		}else{
			throw new loggingException('I`m not find JSON file',$json);
		}
		
	}
	
 /**
 * parseJson - парсер JSON, парсит JSON и записывает в базу
 */	
	public function parseJson(){		
		$this->dbConection();
		$intop = 0;
		foreach ($this->JSON as $k=>$value){	
			$intop++;
			$value['score_rank']=$intop;
			$this->issetAppid($value);	
		}
		$this->DB->startTransaction();
		$this->completeQuery();	
		$this->DB->commitTransaction();
		return $this;
	}
	
 /**
 * completeQuery - вызывает фунции обновления и вставки в базу
 */		
	public function completeQuery(){
		$this->completeInsertQuery();
		$this->completeUpdateQuery();
		return $this;
	}
	
 /**
 * completeUpdateQuery - вызывает фунцию обновления в базу
 */	
	public function completeUpdateQuery(){
		if(is_array($this->qUPDATE)){			
			foreach($this->qUPDATE as $k=>$query){
				$this->DB->getQuery($query);
			}			
		}
		
		return $this;
	}
	
 /**
 * completeInsertQuery - вызывает фунцию вставки в базу
 */		
	public function completeInsertQuery(){
		
		if(is_array($this->qINSERTinAPP)){
			$insertapp = 'INSERT INTO `app`(`id`, `name`, `developer`, `publisher`, `score_rank`, `owners`) VALUES '.implode(',',$this->qINSERTinAPP);
			$this->DB->getQuery($insertapp);
		}
		
		if(is_array($this->qINSERTinAVERAGE)){
			$insertave = 'INSERT INTO `average`(`app_id`, `average_forever`, `average_2weeks`, `median_forever`, `median_2weeks`) VALUES '.implode(',',$this->qINSERTinAVERAGE);
			$this->DB->getQuery($insertave);
		}
		
		if(is_array($this->qINSERTinPRICE)){
			$insertpri = 'INSERT INTO `price`(`app_id`, `price`, `initialprice`, `discount`) VALUES '.implode(',',$this->qINSERTinPRICE);
			$this->DB->getQuery($insertpri);
		}
		
		if(is_array($this->qINSERTinRANK)){
			$insertran = 'INSERT INTO `rank`(`app_id`, `positive`, `negative`, `userscore`) VALUES '.implode(',',$this->qINSERTinRANK);
			$this->DB->getQuery($insertran);
		}
			
		return $this;
		
	}

/**
 * issetAppid - private, проверяем наличие и полноту данных, добавляем или обновляем в базе
 */	
	private function issetAppid($app_array){
		$appdata = $this->DB->getdata('SELECT app.id as id, av.app_id as av, pr.app_id as pr, ra.app_id as ra FROM app app LEFT JOIN average av ON app.id=av.app_id LEFT JOIN price pr ON app.id=pr.app_id LEFT JOIN rank ra ON app.id=ra.app_id WHERE app.id='.$app_array['appid']);
			if(!isset($appdata[0]) or $appdata[0]['id']==''){$this->insertInApp($app_array);}else{$this->updateApp($app_array);}
			if(!isset($appdata[0]) or $appdata[0]['av']==''){$this->insertInAverage($app_array);}else{$this->updateAverage($app_array);}
			if(!isset($appdata[0]) or $appdata[0]['pr']==''){$this->insertInPrice($app_array);}else{$this->updatePrice($app_array);}
			if(!isset($appdata[0]) or $appdata[0]['ra']==''){$this->insertInRank($app_array);}else{$this->updateRank($app_array);}
		
	}

/**
 * updateApp - Добавляет строку в массив qUPDATE для поля app
 */	
	public function updateApp($app_array){
		$this->qUPDATE[] = 'UPDATE `app` SET `name`="'.$app_array['name'].'",`developer`="'.$app_array['developer'].'",`publisher`="'.$app_array['publisher'].'",`score_rank`="'.$app_array['score_rank'].'",`owners`="'.$app_array['owners'].'" WHERE `id`='.$app_array['appid'];
		return $this;
	}

/**
 * updateAverage - Добавляет строку в массив qUPDATE для поля average
 */
	public function updateAverage($app_array){
		$this->qUPDATE[] = 'UPDATE `average` SET `average_forever`='.$app_array['average_forever'].',`average_2weeks`='.$app_array['average_2weeks'].',`median_forever`='.$app_array['median_forever'].',`median_2weeks`='.$app_array['median_2weeks'].' WHERE `app_id`='.$app_array['appid'];
		return $this;
	}

/**
 * updatePrice - Добавляет строку в массив qUPDATE для поля price
 */
	public function updatePrice($app_array){
		$this->qUPDATE[] = 'UPDATE `price` SET `price`="'.$app_array['price'].'",`initialprice`="'.$app_array['initialprice'].'",`discount`="'.$app_array['discount'].'" WHERE `app_id`='.$app_array['appid'];
		return $this;
	}

/**
 * updateRank - Добавляет строку в массив qUPDATE для поля rank
 */
	public function updateRank($app_array){
		$this->qUPDATE[] = 'UPDATE `rank` SET `positive`='.$app_array['positive'].',`negative`='.$app_array['negative'].',`userscore`='.$app_array['userscore'].' WHERE `app_id`='.$app_array['appid'];
		return $this;
	}

/**
 * insertInApp - Добавляет строку в массив qINSERTinAPP для поля app
 */
	public function insertInApp($app_array){
		$this->qINSERTinAPP[]='('.$app_array['appid'].', "'.$app_array['name'].'", "'.$app_array['developer'].'", "'.$app_array['publisher'].'", "'.$app_array['score_rank'].'", "'.$app_array['owners'].'")';	
		return $this;
	}
	
/**
 * insertInAverage - Добавляет строку в массив qINSERTinAVERAGE для поля average
 */	
	public function insertInAverage($app_array){
		$this->qINSERTinAVERAGE[]='('.$app_array['appid'].', '.$app_array['average_forever'].', '.$app_array['average_2weeks'].', '.$app_array['median_forever'].', '.$app_array['median_2weeks'].')';
		return $this;
	}
	
/**
 * insertInPrice - Добавляет строку в массив qINSERTinPRICE для поля price
 */		
	public function insertInPrice($app_array){
		$this->qINSERTinPRICE[]='('.$app_array['appid'].', "'.$app_array['price'].'", "'.$app_array['initialprice'].'", "'.$app_array['discount'].'" )';
		return $this;
	}

/**
 * insertInRank - Добавляет строку в массив qINSERTinRANK для поля rank
 */	
	public function insertInRank($app_array){
		$this->qINSERTinRANK[]='('.$app_array['appid'].', '.$app_array['positive'].', '.$app_array['negative'].', '.$app_array['userscore'].')';
		return $this;
	}

/**
 * dbConection - Соединяется с DB
 */	
	public function dbConection(){		
		$this->DB = new DataMYSQL();
		return $this;
	}
	
}