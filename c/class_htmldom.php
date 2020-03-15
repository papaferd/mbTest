<?php
namespace c;
defined('BASES') OR exit('No direct script access allowed');


class HtmlDom{
	
 /**
 * $visibleBy - ���� ��� ����������, ���� - skip - ���������� �������� ������ �� , 
 * @var array
 */		
var $visibleBy = array(
		'publisher', 						
		'developer',
		'skip'=>'owners');
 /**
 * $node - ���� DomElement 
 * @var DomElement
 */	
var $node;

	
	public function __construct() {	
	
	}
	
 /**
 * getTemplateNode - ��������, ������� � ������ html 
 */		
	public function getTemplateNode($array,$template){
		
		$this->node = new \DOMDocument();
		$this->node->loadHTMLFile($template, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );	
		$this->buildTemplateNode($array);
		return $this->node->saveHTML();
		
		
	}

 /**
 * getSort - ������ ������ ��� JS sort
 */	
	public function getSort($name,$value){
		
		return 'sort(\''.$name.'\',\''.$value.'\');';
		
	}

 /**
 * getStyleTextDecor - ������ ������ style ������ 
 */	
	public function getStyleTextDecor(){
		
		return 'text-decoration:underline; cursor: pointer; margin-right:7px;';
		
	}
	
 /**
 * getExplodeName - ��������� Name �� ����� �� ����������� "," ���� �� ������ ���� skip
 */		
	public function getExplodeName($name,$array){
		$skip=array_search($name,$this->visibleBy);		
			if($skip=="skip"){
				$name_array = array(0=>$array[$name]);
			}else{
				$name_array = explode(',' ,$array[$name]);
			}		
		return($name_array);
	}
	
 /**
 * insertAElement - ��������� � ���� ������� � ����� �
 */			
	public function insertAElement($name,$array,$node){
		
		
		$name_array = $this->getExplodeName($name,$array);
	
		foreach ($name_array as $k=>$value){			
			$element = $this->node->createElement('a', ' '.htmlspecialchars(trim($value)).' ');
			$attribute = $this->node->createAttribute('onClick');
			$attribute->value = $this->getSort($name,$value);
			$element->appendChild($attribute);	
			$attribute = $this->node->createAttribute('style');
			$attribute->value = $this->getStyleTextDecor();
			$element->appendChild($attribute);
			$node->appendChild($element);
		}
		
		return $this;
		
	}
	
 /**
 * buildTemplateNode - ������� ���� �� ������� �� html
 */	
	public function buildTemplateNode($array){
		
		foreach($this->node->getElementsByTagName('*') as $node) {				
				if(isset($array[$node->getAttribute('name')])){
						if(in_array($node->getAttribute('name'), $this->visibleBy)){						
							$this->insertAElement($node->getAttribute('name'), $array, $node);
						}else{
							$node->appendChild($this->node->createElement('curiosity', htmlspecialchars($array[$node->getAttribute('name')])));	
						}
												
				}
		}		
		return $this;
	}
	
}