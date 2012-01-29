<?php
ini_set('error_reporting', E_ALL);
header('Content-Type: text/html;charset=utf-8');
define('GBOOK',true);
session_start();
include 'gbook.lib.php';
 	if(!$gbook->auth) {
		header('refresh:5;url=index.php');
		exit('<b>Acces Denied!</b>');
	}
$pathtofolder = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
$template = new Template($gbook->config['PATH_TO_TEMPLATE']);
$template->open('admin.tpl','admin');

	$template->set('PATH',$pathtofolder,'admin');

	if(!isset($_GET['action']) || $_GET['action'] == 'main')
		$template->set('content','<div style="padding-top:500px;" id="loading"></div><div id="container"><div class="data"></div><div class="pagination"></div></div>','admin');
	elseif($_GET['action'] == 'setting'){
		include('admin/setting.php');
		$template->set('content','<div class="form_settings"><form method="post">' .$txt . '</form></div>','admin');
	} elseif($_GET['action'] == 'bans') {
		include('admin/bans.php');
		$template->set('content',$content,'admin');
	}
	echo $template->compile('admin');
		

?>