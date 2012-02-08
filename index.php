<?php
header("Content-Type: text/html;charset=utf-8");
if(substr($_SERVER['REQUEST_URI'],-strlen(basename(__FILE__))) != basename(__FILE__)){
	header('Location: '.$_SERVER['SCRIPT_NAME']);
}
define('GBOOK',true);
session_start();
$path = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
include 'gbook.lib.php';
	$template = new Template($gbook->config['PATH_TO_TEMPLATE']);

	if(isset($_POST['login'],$_POST['pass']) && !empty($_POST['login']) && !empty($_POST['pass']))
		$gbook->Login($_POST['login'],$_POST['pass']);//если был POST, пробуем залогинить
	if(isset($_POST['logout'])) //выход
		$gbook->Logout();
	 if(isset($_POST['sort'])){
		setSortMessage($_POST['sort']);
		}
	
	if((isset($_GET['del']) || isset($_GET['delall'])) && ($gbook->auth == 1 || $gbook->is_uniqid($_GET['del']))) include 'deletepost.inc.php';
		
	$template->open('main.tpl', 'main');
	$template->open('editor.tpl','editor');
 	if(!$gbook->auth) {
		$template->set_block('guest', '', 'main');
		$template->set_block('guest', '', 'editor');
	} else
		$template->set_block('admin', '', 'main');

	$template->set('select',SortSelected(),'main');
	$template->set('messages','<div id="container"><div class="data"></div><div class="pagination"></div></div>','main');
	$template->set('login',$gbook->returnData("admin_name"),'main');
	$template->set('PATH',$path,'main');
	$template->set('PATH',$path,'editor');
	$template->set('errormsg','<div id="error"></div>','editor');
	$template->set('editor',$template->compile('editor'),'main');
	echo $template->compile('main');
?>