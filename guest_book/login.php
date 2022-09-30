<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);


#тут мы получим логин и пароль, который прилетит нам из формы



$GuestName = $_POST['GuestName'];
$GuestPass = $_POST['GuestPass'];

#проверяем, не пустые ли логин и пароль который пришел из формы

if(($GuestName != '0' and $GuestPass != '0') and ($GuestName != '' or $GuestPass != '') and ($GuestName != '-1' or $GuestPass != '-1') )
{
$MYSQL_HANDLE = mysqli_connect('localhost','admin1','pass123','kurs');
mysqli_set_charset($MYSQL_HANDLE, 'utf8');


function MYSQL_GET($req) //тут я просто создала функцию для запроса к базе. На самом деле она тут не нужна, потому что мы ее вызываем только 1 раз. Но обычно я ее вызываю кучу раз, поэтму создала функцию
{
	global $MYSQL_HANDLE; //Функция не видит переменных вне себя, то есть она снарружи (переменная). Чтобы функция ее увидела, нужно просто прописать ей, что она за рамками
	mysqli_next_result($MYSQL_HANDLE);
	return mysqli_fetch_all(mysqli_query($MYSQL_HANDLE,$req) ,1); //эта функция преобразует данные, которые отправляет сервер мускула, в понятный для php язык

}

$request_sql = MYSQL_GET("SELECT * FROM `users` WHERE `login`='$GuestName' AND `password`='$GuestPass' LIMIT 1;");

#тупая проверка, так нельзя проверять
if($request_sql[0]['login'] != '' or $request_sql[0]['login'] != NULL)
{
#если логин и пароль был введен корректный, логин и пароль совпали в базе
#поработаем с куками, они нам помогут запомнить пользователя чтобы по 100 раз не вводить логин и пароль

#установим куки. Время действия кук - 1 час (можно сделать сколько угодно)

setcookie('my_login',$request_sql[0]['login'], time() + 3600, '/');
setcookie('my_pass',$request_sql[0]['password'], time() + 3600, '/');
setcookie('is_admin',$request_sql[0]['is_admin'], time() + 3600, '/');
echo json_encode(array('is_login'=> 1));
exit;
} else {echo json_encode(array('is_login'=> 0)); exit;} #логин и пароль не совпали

















} elseif ($GuestName == '-1' and $GuestPass == '-1') {echo json_encode(array('is_login'=> 2)); exit;}





?>





































<html>
<head>
	<meta charset="utf-8">
	<title>Guest Book</title>
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="icon" type="image/png" href="images/icon_planet.png">
	
</head>
<body style="overflow: hidden;">
<div class="container">
  <div class="top"></div>
  <div class="bottom"></div>
  <div class="center">
    <h2>Вход в аккаунт</h2>
    <input name="GuestName" type="login" placeholder="логин"/>
    <input name="GuestPass" type="password" placeholder="пароль"/>
	<div name="is_login" style="width: max-content; height: 10px; text-align: center; margin-top: 10px; margin-bottom: 10px; font-size: 12px; color: black; visibility: hidden;">неверный логин или пароль</div>
    <div onclick="LetsLogin()" style="width: 100px; height: 20px; background: yellow; text-align: center; margin-top: 10px; cursor: pointer; margin-bottom: 10px; border-radius: 10px;">Войти</div>
  </div>
</div>
</body>
</html>



<script>

function LetsLogin()
{
	
	let GuestName = document.getElementsByName("GuestName")[0].value == '' ? -1 : document.getElementsByName("GuestName")[0].value;
	let GuestPass = document.getElementsByName("GuestPass")[0].value == '' ? -1 : document.getElementsByName("GuestPass")[0].value;


	var PH_REQ = new XMLHttpRequest();
	PH_REQ.open('POST', 'login.php', true);
	PH_REQ.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	PH_REQ.setRequestHeader('Access-Control-Allow-Origin', '*');
	PH_REQ.setRequestHeader('Access-Control-Allow-Headers', 'origin, content-type, accept');
	PH_REQ.send(
	"GuestName="+GuestName+'&'+
	"GuestPass="+GuestPass
	);

	PH_REQ.onreadystatechange=function() {
	if(PH_REQ.readyState == 4) { //тут мы проверяем ответ самого XMLHttpRequest Если запрос успешно прошел, то....
		if(PH_REQ.status == 200){ // тут мы получаем ответ уже от PHP.

			let PH_DATA = JSON.parse(PH_REQ.responseText);

			let info_obj = document.getElementsByName('is_login')[0];
			switch(parseInt(PH_DATA['is_login']))
			{
				case 1: {document.location = 'post_list.php'; break;}
				case 2: {info_obj.style.visibility = "visible"; info_obj.innerHTML = 'нужно ввести логин и пароль'; break;}
				case 0: {info_obj.style.visibility = "visible"; info_obj.innerHTML = 'неверный логин или пароль'; break;}
				
				
				
			}

			
			//document.getElementsByName('ServerData')[0].innerHTML = PH_DATA; //тут мы просто вставляем полученый ответ.
		}
	}
	};
	

	
	
}


</script>









































