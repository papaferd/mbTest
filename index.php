<?php
namespace c;
define ('BASES', 'mbtest');

error_reporting(-1);
ini_set('error_reporting', E_ALL);

//URL начинается с /
$url = '/mbTest/html/top_100.html';

include('config.php');
include('c/class_logerror.php');
include('c/class_mysql.php');
include('c/class_parse.php');
include('c/class_genereate_htmlpage.php');
include('c/class_htmldom.php');
include('c/class_saveto.php');


 /**
 * Передаем путь на JSON
 */

$json = new ParseJSON('top100forever.json');
$json->parseJson();//Распарсили и в базу

$html = new GenereateHtmlPage();

$file = new SaveTo();
$file->saveAs($html->constructDataToHtml(),$url);


header('Location: '.$url);
die;

