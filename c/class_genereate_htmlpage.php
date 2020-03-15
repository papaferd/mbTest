<?php
namespace c;
defined('BASES') OR exit('No direct script access allowed');


class GenereateHtmlPage{

 /**
 * template ��� �������� 
 * @var string
 */	
var $template = 't/page.html';	
	
 /**
 * template ��� ���� ���� 
 * @var string
 */	
var $templatenode = 't/node.html';

 /**
 * $DB - ����� ��� ������ � ����� ������
 * @var object
 */	
var $DB;
	
 /**
 * $data - ������ � �������, ����������� ����� getDbData �������������
 * @var array
 */	
var $data;
	
 /**
 * $htmlnode - ��� ���� Games � ������ ��������� constructDataToHtml
 * @var string
 */	
var $htmlnode;
	
 /**
 * $htmldom - Dom �� ������� ��� ��� Games
 * @var DomElement
 */	
var $htmldom; 

 /**
 * ����������� 
 */	
	public function __construct() {	
		$this->dbConection();
		$this->getDbData();	
		$this->htmldom = new HtmlDom();		
		$this->htmlnode = '';
	}

 /**
 * constructDataToHtml - �������� �� ������ �� ������� ����  
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
 * getDbData - ������ ������� �� MySQL �� ���� �����
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
 * dbConection - ������� ���������� � �����
 */
	public function dbConection(){		
		$this->DB = new DataMYSQL();
		return $this;
	}
	
	
}