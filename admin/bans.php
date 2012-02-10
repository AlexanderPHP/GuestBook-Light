<?php
	$content = '<div align="center">';
	if(file_exists($gbook->config['PATH_TO_FILE_BANS'])){
		$i = file($gbook->config['PATH_TO_FILE_BANS']);
		$content .='<fieldset style="width: 150px;"><legend align="center">Забанненные IP</legend><select style="width:120px;" size="10" multiple>';
		foreach($i as $j){
			$content .= '<option>'.$j.'</option>'; 
		}
		$content .= '</select></fieldset>';
	} else {
		$content = "Список Black IP чист <br>";
		$gbook->updBans();
	}
if(isset($_GET['ip'])){
	$content .= 'Вы действительно хотите заблокировать IP: ' . $_GET['ip'] . ' ?
			<form  class="form_settings" action="admin.php?action=bans" method="POST">
			<input style="margin-left: auto;margin-right: auto;" class="submit" type="submit" name="yes" value="Yes"><input  style="margin-left: auto;margin-right: auto;"class="submit" type="submit" value="No"><input type="hidden" name="ip" value="' . $_GET['ip'] . '">
			</form>';
}
if(isset($_POST['yes'])){
		$ips = file($gbook->config['PATH_TO_FILE_BANS'],FILE_IGNORE_NEW_LINES);
		/*Если PHP Version < 5.3, используйте следующий код*/
		/*foreach($ips as $ip){
			$ips[] = rtrim($ip,PHP_EOL);
		}
		//--> if(array_search($_POST['ip'],array_filter($ips,function($ips){ return rtrim($ips,PHP_EOL); })) === false){
		Заменить на
		//--> if(array_search($_POST['ip'],$ips) === false){
		*/
		if(array_search($_POST['ip'],array_filter($ips,function($ips){ return rtrim($ips,PHP_EOL); })) === false){
			$gbook->addIP($_POST['ip']);
			$gbook->updBans();
			$content .= 'IP адрес добавлен в Black-лист';
			header('Location: '.$_SERVER['REQUEST_URI']);
		} else {
			$content .= 'Данный IP уже заблокирован.';
		}
}
$content .= '</div>';
?>