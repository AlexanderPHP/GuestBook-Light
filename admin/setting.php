<?php
$txt = "<img align='left' src='$pathtofolder/templates/default/images/warning.png'>Внимание! Перед тем, как редактировать настройки прочитайте файл <a href='./readme.html'>Readme.html</a><br>Неккоректная настройка конфигурационного файла может привести к ошибкам!<br><br><br>";
		if($gbook->config['ADMIN_EDIT_CONF']){
			foreach($gbook->config as $k=>$v){
				if(!$gbook->config['SQL_ADMIN_EDIT'] && $k=='SQL_ADMIN_EDIT')
					$v = 'Редактирование БД запрещено в файле конфигурации!';
				$txt .= "<input type='text' value='{$k}' readonly style='margin: 0 0 5px 60px;'>
						<input name='newconf[{$k}]' type='text' value='{$v}'><br>";
			}
			$txt .= '<div align="right" style="margin: 0 18px;"><input class="submit" type="submit" value="Submit"></div>';
				if(isset($_POST['newconf']) AND !empty($_POST['newconf'])){
				$newconf = '';
				foreach ($_POST['newconf'] as $k=>$v){
						if($k == 'SQL_ADMIN_EDIT' && !$gbook->config['SQL_ADMIN_EDIT']) $v = 0;
						$newconf .= "$k = $v\n";
				}
				file_put_contents('data/global_config.cfg',$newconf);
				header('Location:'.$_SERVER['SCRIPT_NAME']); }
		} else {
			$txt = 'Редактирование настроек через Админ-панель отключено.<br>Поменяйте значение ADMIN_EDIT_CONF на 1 в \'data/global_config.cfg\'';
		}
?>