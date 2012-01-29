<?php

	if(file_exists($gbook->config['PATH_TO_FILE_BANS'])){
		$i = file($gbook->config['PATH_TO_FILE_BANS']);
		$content='<fieldset style="width: 150px;"><legend align="center">Забанненные IP</legend><select style="width:120px;margin-left:16px;" size="10" multiple>';
		foreach($i as $j){
			$content .= '<option>'.$j.'</option>'; 
		}
		$content .= '</select></fieldset>';
	} else
		$gbook->updBans();
		
if(isset($_GET['ip'])){
	
}
?>