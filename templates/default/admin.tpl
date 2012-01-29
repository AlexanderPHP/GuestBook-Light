<!DOCTYPE html>
<html>
<head>
  <title>Guest Book Light</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <script type="text/javascript" src="{PATH}/templates/jquery.min.js"></script> 
  <script type="text/javascript" src="{PATH}/templates/compressedscript.js"></script> 
  <link rel="stylesheet" type="text/css" href="{PATH}/templates/default/style/style.css" />
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <h1>Guest Book <a href="index.php">Light</a></h1>
        <div class="slogan">Welcome to Guest Book!</div>
      </div>
		<div id="menubar">
			<ul id="menu">
				<li><a href="index.php">Home</a></li>
				<li  class="current"><a href="admin.php">Admin</a></li>
			</ul>
		</div>
    </div>
    <div id="site_content">
      <div id="sidebar_container">
		<div id="rightmenu">
			<ul>
				<li><a href="admin.php?action=main" title="Главная">Главная</a></li>
				<li><a href="admin.php?action=setting" title="Настройки скрипта">Настройки</a></li>
				<li><a href="admin.php?action=sql" title="Работа с Базой Данных">Работа с БД</a></li>
				<li><a href="admin.php?action=tool" title="Тул">Оминка</a></li>
				<li><a href="admin.php?action=bans" title="Блокировка пользователей">Блокировка пользователей</a></li>
				<li><a href="#5" title="###">###</a></li>	
			</ul>
		</div>
	  </div>
		<div id="content">
		<div style="margin-top:10px;"></div>
		{content}
      </div>
    </div>
    <div id="footer">
      <p>Copyright &copy;<a href="http://web-spell.ru"> Gbook Light</a> | <a href="http://www.html5webtemplates.co.uk" rel="nofollow">design from HTML5webtemplates.co.uk</a></p>
    </div>
  </div>
</body>
</html>