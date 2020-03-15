<?php
namespace c;
defined('BASES') OR exit('No direct script access allowed');


class GenereateHtmlPage{

 /**
 * template дл€ страницы 
 * @var string
 */	
var $template = 't/page.html';	
	
 /**
 * template дл€ Ќоды »гры 
 * @var string
 */	
var $templatenode = 't/node.html';

 /**
 * $DB - обект дл€ работы с базой данных
 * @var object
 */	
var $DB;
	
 /**
 * $data - массив с данными, полученными через getDbData конструктором
 * @var array
 */	
var $data;
	
 /**
 * $htmlnode - все ноды Games в строку собранные constructDataToHtml
 * @var string
 */	
var $htmlnode;
	
 /**
 * $htmldom - Dom из шаблона дл€ Ќод Games
 * @var DomElement
 */	
var $htmldom; 

 /**
 *  онстркутор 
 */	
	public function __construct() {	
		$this->dbConection();
		$this->getDbData();	
		$this->htmldom = new HtmlDom();		
		$this->htmlnode = '';
	}

 /**
 * constructDataToHtml - —обирает из данных по шаблону ноды  
 */	
	public function constructDataToHtml(){
		
		foreach($this->data as $k=>$value){	
			$this->htmlnode .= html_entity_decode($this->htmldom->getTemplateNode($value,$this->templatenode));			
		}

		$arrayhtml = array();
		$arrayhtml['games'] = $this->htmlnode;
		$html = html_entity_decode($this->htmldom->getTemplateNode($arrayhtml,$this->template));
		return $html;
	}

 /**
 * getDbData - ƒелает выборку из MySQL по всем пол€м
 */	
	public function getDbData(){
		$query = 'SELECT app.id, app.name, app.developer, app.publisher, app.score_rank, app.owners, 
		av.average_forever, av.average_2weeks, av.median_forever, av.median_2weeks,
		pr.price, pr.initialprice, pr.discount,
		ra.positive, ra.negative, ra.userscore 
		FROM app app
		LEFT JOIN average av ON app.id=av.app_id
		LEFT JOIN price pr ON app.id=pr.app_id
		LEFT JOIN rank ra ON app.id=ra.app_id		
		';		
		$this->data = $this->DB->getData($query);
		return $this;
	}
	
 /**
 * dbConection - —оздает соединение с базой
 */
	public function dbConection(){		
		$this->DB = new DataMYSQL();
		return $this;
	}
	
	
}