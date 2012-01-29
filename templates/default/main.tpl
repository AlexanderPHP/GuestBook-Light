<!DOCTYPE HTML>
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
        <h1>Guest Book Light</h1>
        <div class="slogan">Welcome to Guest Book!</div>
      </div>
	   [admin]
		<div id="menubar">
			<ul id="menu">
				<li class="current"><a href="index.php">Home</a></li>
				<li><a href="admin.php">Admin</a></li>
			</ul>
		</div>
		[/admin]
    </div>
    <div id="site_content">
      <div id="sidebar_container">
        <img class="paperclip" src="{PATH}/templates/default/images/paperclip.png" alt="paperclip" />
		[guest]
		<div class="sidebar">
          <h1>Авторизация</h1>
          <p>Введите логин и пароль</p>
          <form method="post" id="subscribe">
            <p style="padding: 0 0 9px 0;"><input class="input" type="text" name="login" value="Логин" onblur="if(this.value=='') this.value='Логин';" onfocus="if(this.value=='Логин') this.value='';" /></p>
			<p style="padding: 0 0 9px 0;"><input class="input" type="text" name="pass" value="Пароль" onblur="if(this.value=='') this.value='Пароль';" onfocus="if(this.value=='Пароль') this.value='';" /></p>
            <p><input class="subscribe" name="subscribe" type="submit" value="LogIn" /></p>
		</form>
        </div>
		[/guest]
		[admin]
		<div class="sidebar">
		<h2 style="padding-top: 15px;">Добро пожаловать</h2>
         <p style="color:red; font-size: 130%;">{login}</p>
		<form method='post'>
			<input class='subscribe' type="submit" name='logout' value="LogOut">
		</form>
		</div>
		[/admin]
		<img class="paperclip" src="{PATH}/templates/default/images/paperclip.png" alt="paperclip" />
		<div class="sidebar">
          <h3>Сортировка</h3>
          <p>Выберите метод сортировки сообщений.</p>
          <form method="post">
          <div class="form_settings">
			<select name='sort' onChange="this.form.submit();">
				 {select}
			</select>
          </div>
        </form>
        </div>
      </div>
		<div id="content">
			{editor}
		<div style="margin-top:10px;"></div>
			[admin]
				<p id='delall' align="right">
					<a href="" onClick='return false;'>Удалить всё</a>
				</p>
			[/admin]
		<div id="loading" style="position: fixed; display: none;">
			<img class="load" src="{PATH}/templates/loading.gif">
		</div>
	   <div id="container">
            <div class="data"></div>
            <div class="pagination"></div>
        </div>
      </div>
    </div>
    <div id="footer">
      <p>Copyright &copy;<a href="http://web-spell.ru"> Gbook Light</a> | <a href="http://www.html5webtemplates.co.uk" rel="nofollow">design from HTML5webtemplates.co.uk</a></p>
    </div>
  </div>
</body>
</html>