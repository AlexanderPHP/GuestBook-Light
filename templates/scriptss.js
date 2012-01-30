function tag(tag1, tag2) {
		if ((document.selection)) {
		    document.getElementById("mess").focus();
		    var text = document.post.document.selection.createRange().text
		    document.post.document.selection.createRange().text = tag1 + text + tag2;
		} else if(document.forms["post"].elements["msg"].selectionStart != undefined) {
			var element    = document.forms["post"].elements["msg"];
			var str        = element.value;
			var start      = element.selectionStart;
			var length     = element.selectionEnd - element.selectionStart;
			element.value  = str.substr(0, start) + tag1 + str.substr(start, length) + tag2 + str.substr(start + length);
		}
		 return false;
	}

$(document).ready(function(){
	$("#loading").ajaxStart(function(){
	 $(this).fadeIn("fast");
	}).ajaxStop(function(){
	   $(this).fadeOut("fast");
	});
	
	function loadData(page){
		$.ajax
		({
			type: "POST",
			url: "message.php",
			data: "getmessage=1&page="+page,
			success: function(msg)
			{$("#container").html(msg);}
		});
	}
	
	loadData(1);
	
	$("#container .pagination li.active").live("click",function(){
		var page = $(this).attr("p");
		loadData(page);
		
	});
	
	$("#go_btn").live("click",function(){
		var page = parseInt($(".goto").val());
		var no_of_pages = parseInt($(".total").attr("a"));
		if(page != 0 && page <= no_of_pages){
			loadData(page);
		}else{
			alert("Введите страницу от 1 до "+no_of_pages);
			$(".goto").val("").focus();
			return false;
		}
		
	});
	
	$("#delete").live("click",function(){
		if(confirm("Вы действительно хотите удалить данное сообщение?")){
			$(this).parents(".comment_background").animate({ opacity: "hide" }, "slow",function(){
			$.ajax({
				type: "POST",
				url: "message.php",
				data: "deletemessage=1&id=" + this.id
				});
			});
		}
	});
	
	$("#delall").delegate("a","click",function(){
		if(confirm("Вы действительно хотите удалить все сообщения?")){
			$.post("message.php","deletemessage=all");
			loadData(1);
		}
	});
	
	$("#myForm").submit(function(){
	if($("input").is("#name") && $("input").is("#email")){
		var msg  = $("#mess").val();
		if (msg ==""){
			alert ("Заполните текст сообщения!");
			return false;
		}
		var name  = $("#name").val();
		if (name ==""){
			alert ("Введите имя!");
			return false;
		}
		var email  = $("#email").val();
		if (email ==""){
			alert ("Введите ваш e-mail!");
			return false;
		}		
		var data = "name="+ name + "&email=" + email + "&msg=" + msg;
	} else if($("textarea").is("#mess")){
			var msg  = $("#mess").val();
			if (msg ==""){
				alert ("Заполните текст сообщения!");
				return false;
			}
			var data = "msg=" + msg;
	}
	$.ajax({
		type: "POST",
		url: "message.php",
		data: "savecomment=1&"+data,
		success: function(msg){
				//if(parseInt(msg.st)==0)
				//	$.("#error").html(msg.txt);
				//else
					loadData(1);
				}
	});
	$("#myForm").trigger("reset");
	return false;
	});
});