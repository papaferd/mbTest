<?php
namespace c;
defined('BASES') OR exit('No direct script access allowed');

 /**
 * Класс SaveTo - Проверяет и создает папку для хранения html файлов 
 */

class SaveTo{
	

 /**
 * Конструктор - 
 */
	
	public function __construct() {	
	
	}
	
 /**
 * saveAs - Сохраняет html Текст по пути URL
 */	
	public function saveAs($html,$url){		
		
		$this->mkDir($url);	
		if(!file_put_contents($_SERVER['DOCUMENT_ROOT'].$url, $html)){throw new loggingException('I`m not load in a dir html file',$url);	}		
		
	}
	
 /**
 * mkDir - Проверяет наличие папки и создает её
 */
	public function mkDir($url){		
		$urlarray = explode('/',$url);
		array_pop($urlarray);
		$thisdir = $_SERVER['DOCUMENT_ROOT'];
		foreach($urlarray as $k=>$v){
			if($v!='')
			{
				$thisdir .= '/'.$v.'/' ;
				if (!is_dir($thisdir))
				{
				   if(!mkdir($thisdir, 0755)){
					   throw new loggingException('I`m not make File Dir',$url);	   
				   }

				}
			}
		}
		
	}
}