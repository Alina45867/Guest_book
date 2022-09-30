<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

$my_login = $_COOKIE['my_login'];
$my_pass = $_COOKIE['my_pass']; #вот это не нужно, но вдруг когда-нибудь кто-нибудь захочет все же сделать проверку на логин и пароль
$is_admin = $_COOKIE['is_admin'];

if($my_login != '' and $my_pass != '')
{}
else
{header('Location: login.php'); exit;}



//чтобы можно было распознать того, кто создает нвоый пост (и проверить админ это или нет, чтобы кнопки были активны)
?>



<head>
	<meta charset="utf-8">
	<title>Guest Book | Posts</title>
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="icon" type="image/png" href="images/icon_planet.png">
	
</head>

<a style="position: absolute; width: max-content; height: max-content; left: 0; right: 0; top: 20px; margin: auto;">Записи</a>
<a class="icon_24px" style="background-image: url('images/icon_add.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer;" onclick="Effects(0); GLOBAL_p_id = 0;">добавить</a>
<a class="icon_24px" style="background-image: url('images/icon_reload.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer; margin-left: 10px;" onclick="GetAllPosts()">обновить</a>
<a name="ButtonEditUsers" class="icon_24px" style="background-image: url('images/icon_edit.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer; margin-left: 10px; visibility: hidden" onclick="GetAllUsers()">редактировать пользователей</a>



<div name="MainPanel" style="position: absolute; background-color: white; width: 100%; height: 100%; margin-top: 50px; left: 0;">


</div> 



<div name="AddEditPost" class="Center_WidthHeight" style="position: absolute; background-color: yellow; width: 300px; height: 70px; border-radius: 5px; visibility: hidden;">
<a class="icon_24px" style="background-image: url('images/icon_close.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer; margin-left: 10px; " onclick="Effects(1)">закрыть</a>
<a class="icon_24px" style="background-image: url('images/icon_add.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer;" onclick="AddEditPost(document.getElementsByName('AddEditinput')[0].value)">запостить</a>
<input name="AddEditinput" style="width: 90%; margin-top: 15px; margin-left: 15px;"></input>
</div>















<script>
let GLOBAL_p_id = 0;
function GetAllPosts()
{
	var PH_REQ = new XMLHttpRequest();
	PH_REQ.open('POST', 'post_detail.php', true);
	PH_REQ.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	PH_REQ.setRequestHeader('Access-Control-Allow-Origin', '*');
	PH_REQ.setRequestHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');
	PH_REQ.send(`act=1`);

	PH_REQ.onreadystatechange=function() {
	if(PH_REQ.readyState == 4) { //тут мы проверяем ответ самого XMLHttpRequest Если запрос успешно прошел, то....
		if(PH_REQ.status == 200){ // тут мы получаем ответ уже от PHP.
			Effects(1);
			PH_DATA = JSON.parse(PH_REQ.responseText)
			let render = '';
			PH_DATA.forEach( function(elem,index)
			{
				let is_visible = 'hidden';
				// вот и сама проверка если логин в куках совпадает с логином автора, то показать кнопки
				//а вот можно было по-другому, но там сложнее рассказать. Про куки знают все
				if(getCookie('my_login') == elem['created_from'] || parseInt(getCookie('is_admin')) == 1) {is_visible = 'visible'}
				
				render = render + `<div class="Center_Width" style="position: relative; margin-top: 10px; background-color: yellow; width: 80%; height: max-content; min-height: 50px; padding: 5px; border-radius: 5px;"><a class="icon_24px" style="background-image: url('images/icon_close.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer; visibility: ${is_visible}" onclick="DeletePost(${elem['p_id']})">удалить пост</a><a class="icon_24px" style="background-image: url('images/icon_add.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer; visibility: ${is_visible}" onclick="Effects(0); document.getElementsByName('AddEditinput')[0].value = '${elem['text']}'; GLOBAL_p_id = ${elem['p_id']}">редактировать</a></br><a>${elem['text']}</a></div>`;
	
				//console.log(elem)
			}
			); 
			document.getElementsByName("MainPanel")[0].innerHTML=render;

			
		}
	}
	};	
}

function GetAllUsers()
{
	var PH_REQ = new XMLHttpRequest();
	PH_REQ.open('POST', 'post_detail.php', true);
	PH_REQ.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	PH_REQ.setRequestHeader('Access-Control-Allow-Origin', '*');
	PH_REQ.setRequestHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');
	PH_REQ.send(`act=4`);

	PH_REQ.onreadystatechange=function() {
	if(PH_REQ.readyState == 4) { //тут мы проверяем ответ самого XMLHttpRequest Если запрос успешно прошел, то....
		if(PH_REQ.status == 200){ // тут мы получаем ответ уже от PHP.
			Effects(1);
			PH_DATA = JSON.parse(PH_REQ.responseText)
			console.log(PH_DATA)
			let render = '';
			PH_DATA.forEach( function(elem,index)
			{
				let is_visible = 'hidden';
				// вот и сама проверка если логин в куках совпадает с логином автора, то показать кнопки
				//а вот можно было по-другому, но там сложнее рассказать. Про куки знают все
				if(getCookie('my_login') == elem['created_from'] || parseInt(getCookie('is_admin')) == 1) {is_visible = 'visible'}
				
				render = render + `<div class="Center_Width" style="position: relative; margin-top: 10px; background-color: yellow; width: 80%; height: max-content; min-height: 50px; padding: 5px; border-radius: 5px;"><a class="icon_24px" style="background-image: url('images/icon_add.png'); font-size: 10px; padding-bottom: 1px; padding-left: 14px; cursor: pointer; visibility: ${is_visible}" onclick="EditUser(${elem['u_id']},document.getElementsByName('EditinputUserLogin${elem['u_id']}')[0].value,document.getElementsByName('EditinputUserPass${elem['u_id']}')[0].value,document.getElementsByName('EditinputUserComment${elem['u_id']}')[0].value);">сохранить</a></br>
				
				<input name="EditinputUserLogin${elem['u_id']}" style="width: 90%; margin-top: 15px; margin-left: 15px;" value="${elem['login']}"></input>
				<input name="EditinputUserPass${elem['u_id']}" style="width: 90%; margin-top: 15px; margin-left: 15px;" value="${elem['password']}"></input>
				<input name="EditinputUserComment${elem['u_id']}" style="width: 90%; margin-top: 15px; margin-left: 15px;" value="${elem['comment']}"></input>
				</div>`;
	
				//console.log(elem)
			}
			); 
			document.getElementsByName("MainPanel")[0].innerHTML=render;

			
		}
	}
	};	
}


function DeletePost(post_id)
{
	var PH_REQ = new XMLHttpRequest();
	PH_REQ.open('POST', 'post_detail.php', true);
	PH_REQ.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	PH_REQ.setRequestHeader('Access-Control-Allow-Origin', '*');
	PH_REQ.setRequestHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');
	PH_REQ.send(`act=2&post_id=${post_id}`);

	PH_REQ.onreadystatechange=function() {
	if(PH_REQ.readyState == 4) { //тут мы проверяем ответ самого XMLHttpRequest Если запрос успешно прошел, то....
		if(PH_REQ.status == 200){ // тут мы получаем ответ уже от PHP.
			GetAllPosts();
		}
	}
	};	
}



function AddEditPost(post_text)
{
	var PH_REQ = new XMLHttpRequest();
	PH_REQ.open('POST', 'post_detail.php', true);
	PH_REQ.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	PH_REQ.setRequestHeader('Access-Control-Allow-Origin', '*');
	PH_REQ.setRequestHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');
	PH_REQ.send(`act=3&post_text=${post_text}&post_id=${GLOBAL_p_id}`);

	PH_REQ.onreadystatechange=function() {
	if(PH_REQ.readyState == 4) { //тут мы проверяем ответ самого XMLHttpRequest Если запрос успешно прошел, то....
		if(PH_REQ.status == 200){ // тут мы получаем ответ уже от PHP.
			GetAllPosts();
		}
	}
	};	
}


function EditUser(u_id,user_login,user_password,user_comment)
{

	var PH_REQ = new XMLHttpRequest();
	PH_REQ.open('POST', 'post_detail.php', true);
	PH_REQ.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	PH_REQ.setRequestHeader('Access-Control-Allow-Origin', '*');
	PH_REQ.setRequestHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');
	PH_REQ.send(`act=5&u_id=${u_id}&user_login=${user_login}&user_password=${user_password}&user_comment=${user_comment}`);

	PH_REQ.onreadystatechange=function() {
	if(PH_REQ.readyState == 4) { //тут мы проверяем ответ самого XMLHttpRequest Если запрос успешно прошел, то....
		if(PH_REQ.status == 200){ // тут мы получаем ответ уже от PHP.
			console.log(PH_REQ.responseText)
			GetAllUsers();
		}
	}
	};	
}



// все функции в JS расписаны по названию - можно сразу понять что они делают
//мы кое-что не сделали все же
//редактирование и что-то там с пользователем












function Effects(ef_id)
{
	switch(ef_id)
	{
		case 0: {document.getElementsByName("AddEditPost")[0].style.visibility = 'visible'; 
				document.getElementsByName("MainPanel")[0].style.visibility = 'hidden';
				document.getElementsByName("MainPanel")[0].style.opacity = '0';
				document.getElementsByName("AddEditinput")[0].value = ''; break;}
		case 1: {document.getElementsByName("AddEditPost")[0].style.visibility = 'hidden';
				document.getElementsByName("MainPanel")[0].style.opacity = '1'; 
				document.getElementsByName("MainPanel")[0].style.visibility = 'visible'; break;}
		
		
		
	}
	
	
}



function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}





if(parseInt(getCookie('is_admin')) == 1)
{
	document.getElementsByName("ButtonEditUsers")[0].style.visibility = 'visible';
}












</script>

