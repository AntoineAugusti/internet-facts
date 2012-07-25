<?php
session_start();
require '../kernel/config.php';
header("Access-Control-Allow-Origin: *");

$code_return = 0;

if (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME']) AND $_SESSION['logged'] == TRUE AND $_POST['admin'] == TRUE AND !empty($_POST['text_fact'])) 
{
	$text_fact = htmlspecialchars(mysql_real_escape_string($_POST['text_fact']));
	$date = date("d/m/Y");

	$query = mysql_query("INSERT INTO facts (text_fact, username, date, approved) VALUES ('".$text_fact."', 'admin', '".$date."', '1')");

	if ($query)
	{
		$code_return = 1;
	}
	
}

echo $code_return;