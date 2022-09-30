<?php
$MYSQL_HANDLE = mysqli_connect('localhost','admin1','pass123','kurs');
mysqli_set_charset($MYSQL_HANDLE, 'utf8mb4');

$ACTION = $_POST['act'];

$my_login = $_COOKIE['my_login'];
$my_pass = $_COOKIE['my_pass']; #вот это не нужно, но вдруг когда-нибудь кто-нибудь захочет все же сделать проверку на логин и пароль
$is_admin = $_COOKIE['is_admin'];


function MYSQL_GET($req) //тут я просто создала функцию для запроса к базе. На самом деле она тут не нужна, потому что мы ее вызываем только 1 раз. Но обычно я ее вызываю кучу раз, поэтму создала функцию
{
	global $MYSQL_HANDLE; //Функция не видит переменных вне себя, то есть она снарружи (переменная). Чтобы функция ее увидела, нужно просто прописать ей, что она за рамками
	mysqli_next_result($MYSQL_HANDLE);
	return mysqli_fetch_all(mysqli_query($MYSQL_HANDLE,$req) ,1); //эта функция преобразует данные, которые отправляет сервер мускула, в понятный для php язык

}

switch($ACTION)
{
	case 1: GetAllPosts(); break; //получаем все посты
	case 2: DeletePost(); break; //удалить определенный пост
	case 3: AddEditPost(); break; //добавить\изменить пост новый пост
	case 4: GetAllUsers(); break; //получить всех пользователей
	case 5: EditUser(); break; //получить всех пользователей
	
	
}


function GetAllPosts()
{
	$all_data = MYSQL_GET("SELECT * FROM `posts`");
	echo json_encode($all_data);
}


function GetAllUsers()
{
	$all_data = MYSQL_GET("SELECT * FROM `users`");
	echo json_encode($all_data);
}



function AddEditPost()
{
	global $my_login;
	$post_text = $_POST['post_text'];
	$post_id = $_POST['post_id'];
	if($post_id == 0) //new post
	{
		MYSQL_GET("INSERT INTO `posts` (`created_from`,`text`) VALUES ('$my_login','$post_text')");
	}
	else //edit post
	{
		MYSQL_GET("UPDATE `posts` SET `text`='$post_text' WHERE `p_id`=$post_id");
	}
}

function DeletePost()
{
	$post_id = $_POST['post_id'];
	MYSQL_GET("DELETE FROM `posts` WHERE `p_id`=$post_id");
	
}




function EditPost()
{
	$post_text = $_POST['post_text'];
	$post_id = $_POST['post_id'];
	MYSQL_GET("UPDATE `posts` SET `text`='$post_text' WHERE `p_id`=$post_id");
}




function EditUser()
{
	$u_id = $_POST['u_id'];
	echo '++++++++++++++++++++++++++++++++++'.$u_id;
	
	$user_login = $_POST['user_login'];
	$user_password = $_POST['user_password'];
	$user_comment = $_POST['user_comment'];
	MYSQL_GET("UPDATE `users` SET `login`='$user_login',`password`='$user_password', `comment`='$user_comment' WHERE `u_id`=$u_id");
	//echo "UPDATE `users` SET `login`='$user_login',`password`='$user_password', `comment`='$user_comment' WHERE `u_id`=$u_id";
}










?>