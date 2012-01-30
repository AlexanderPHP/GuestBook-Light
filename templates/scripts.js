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
	$.ajax({
		type: "POST",
		url: "message.php",
		data: "savecomment=1&"+$('#myForm').serialize(),
		dataType: "json",
		success: function(msg){
 				if(!msg.st)
					$("#error").html(msg.err);
				else
					loadData(1);
				}
	});
	$("#myForm").trigger("reset");
	return false;
	});
});