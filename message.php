<?php
session_start();
define('GBOOK',true);
include 'gbook.lib.php';

if(isset($_POST['getmessage']) && $_POST['getmessage']){
 if(isset($_POST['page'])) {
	$msg = '';
	$page = $_POST['page'];
	$curpage = $page;
	$page -= 1;
	$perpage = $gbook->config['QUANTITY_MESSAGES'];
	$previous = true;
	$next = true;
	$first = true;
	$last = true;
	$start = $page * $perpage;

	$template = new Template($gbook->config['PATH_TO_TEMPLATE']);
	$template->open('comments.tpl', 'comments');

	$messages = $gbook->getMessage($start, $perpage);
	foreach ($messages as $message){
		$mess = nl2br(Replacer($message["msg"]));
		$dt = date('d-m-Y H:i:s',$message["datetime"]);
		
		$template->set('id',$message["id"],'comments');
		$template->set('name',$message["name"],'comments');
		$template->set('email',$message["email"],'comments');
		$template->set('comment',$mess,'comments');
		$template->set('ip',$message["ip"],'comments');
		$template->set('dt',$dt,'comments');
		
		if($gbook->auth==1 OR $message["uniqid"] == @$_COOKIE['UNIQID'] AND $gbook->config['USER_EDIT_MESSAGE'])
			$template->set_block('redact', '', 'comments');
	
		$msg .= $template->compile('comments');
	}
	$msg = "<div class='data'>$msg</div>";

	$ceil = ceil($gbook->PagCount() / $perpage);

		if ($curpage >= 7) {
			$loopstart = $curpage - 3;
				if ($ceil > $curpage + 3)
					$loopend = $curpage + 3;
				elseif ($curpage <= $ceil && $curpage > $ceil - 6) {
					$loopstart = $ceil - 6;
				$loopend = $ceil;
				} else
					$loopend = $ceil;
		} else {
			$loopstart = 1;
				if ($ceil > 7)
					$loopend = 7;
				else
					$loopend = $ceil;
		}

	$msg .= "<div class='pagination'><ul>";

		if ($first && $curpage > 1)
			$msg .= "<li p='1' class='active' title='Первая'>&lt;&lt;</li>";
		elseif ($first)
			$msg .= "<li p='1' class='inactive'>&lt;&lt;</li>";

		if ($previous && $curpage > 1) {
			$pre = $curpage - 1;
			$msg .= "<li p='$pre' class='active' title='Предыдущая'>&nbsp;&lt;&nbsp;</li>";
		} elseif ($previous)
			$msg .= "<li class='inactive'>&nbsp;&lt;&nbsp;</li>";

		for ($i = $loopstart; $i <= $loopend; $i++) {
			if ($curpage == $i)
				$msg .= "<li p='$i' style='color:#fff;background-color:#35BDF5;' class='inactive'>{$i}</li>";
			else
				$msg .= "<li p='$i' class='active'>{$i}</li>";
		}

		if ($next && $curpage < $ceil) {
			$nextpage = $curpage + 1;
			$msg .= "<li p='$nextpage' class='active' title='Следующая'>&nbsp;&gt;&nbsp;</li>";
		} elseif ($next)
			$msg .= "<li class='inactive'>&nbsp;&gt;&nbsp;</li>";

		if ($last && $curpage < $ceil)
			$msg .= "<li p='$ceil' class='active' title='Последняя'>&gt;&gt;</li>";
		else if ($last)
			$msg .= "<li p='$ceil' class='inactive'>&gt;&gt;</li>";

	$go = "<input type='text' class='goto' size='1' style='margin-top: -1px;margin-left:60px;height: 21px;'/><input type='button' id='go_btn' class='go_button' value='Go'/>";
	$total = "<span class='total' a='$ceil'>Page <b>$curpage</b> of <b>$ceil</b></span>";
	$msg = $msg . "</ul>" . $go . $total . "</div>";
	echo $msg;
 }
} elseif(isset($_POST['savecomment']) && $_POST['savecomment']){
	 if(isset($_POST['name']) && isset($_POST['msg']) && isset($_POST['email']) && !$gbook->auth) {
		
		if(empty($_POST['name']) || empty($_POST['msg']) || empty($_POST['email']))
			die('{"st":false,"err":"Не заполнены обязательные поля!"}');
		elseif(preg_match("/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $_POST['name']))
			die('{"st":false,"err":"Имя введено неверно!"}');
		elseif(strlen($_POST['email']) > 50 || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			die('{"st":false,"err":"E-mail введен неверно, либо его длина превышает 50 символов!"}');
		elseif(strlen($_POST['msg']) > $gbook->config['MAX_QUANTITY_SYMBOLS'])
			die('{"st":false,"err":"Длина сообщения не должна превышать ' .$gbook->config['MAX_QUANTITY_SYMBOLS'] . ' символов!"}');

			$msg = $gbook->clear($_POST['msg']);
			$msg = bbcode($msg);
			$name = $gbook->clear($_POST['name']);
			$email = $gbook->clear($_POST['email']);
			$gbook->save('g', $msg, $name, $email);
			echo '{"st":true}';
			
	} elseif(isset($_POST['msg']) && $gbook->auth) {
			if(empty($_POST['msg']))
				die('{"st":false,"err":"Заполните текст сообщения!"}');
			
			$msg = $gbook->clear($_POST['msg']);
			$msg = bbcode($msg);
			$gbook->save('a', $msg);
			echo '{"st":true}';
			
	}
} elseif(isset($_POST['deletemessage'])){
	if($_POST['deletemessage'] AND isset($_POST['id']) AND ($gbook->is_uniqid($_POST['id']) OR $gbook->auth)){
			$id = abs((int)$_POST['id']);
			$gbook->deleteMessage('one',$id);
			echo 'Ok';
		} elseif($_POST['deletemessage'] == 'all' && $gbook->auth){
				$gbook->deleteMessage('all');
			echo 'All deleted';
		}
}
?>