<?php
session_start();
require '../kernel/config.php';
header("Access-Control-Allow-Origin: *");

$code_return = 0;

if (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME']) AND $_SESSION['logged'] == TRUE AND $_POST['admin'] == TRUE AND !empty($_POST['text_fact'])) 
{
	$text_fact = htmlspecialchars(mysql_real_escape_string($_POST['text_fact']));
	$date = date("d/m/Y");

	$query = mysql_query("INSERT INTO facts (text_fact, id_author, date, approved) VALUES ('".$text_fact."', '1', '".$date."', '1')");

	if ($query)
	{
		$code_return = 1;
	}
	
}
elseif (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME']) AND $_POST['admin'] == FALSE AND !empty($_POST['text_fact']) AND !empty($_POST['username']) AND !empty($_POST['email']))
{
	$username = trim(strtolower(htmlspecialchars(mysql_real_escape_string($_POST['username']))));
	$email = trim(htmlspecialchars(mysql_real_escape_string($_POST['username'])));
	$text_fact = trim(htmlspecialchars(mysql_real_escape_string($_POST['text_fact'])));
	$date = date("d/m/Y");

	if (!email_is_valid($email) OR !username_is_valid($username))
	{
		$code_return = 0;
	}
	else
	{
		$exist_username = mysql_query("SELECT email FROM facts WHERE username = '".$username."'");

		if (mysql_num_rows($exist_username) == 1)
	}

}

echo $code_return;