<?php
session_start();
require '../kernel/config.php';
header("Access-Control-Allow-Origin: *");

$code = 0;

if (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME']) AND $_SESSION['logged'] == TRUE AND !empty($_POST['text_fact']) AND !empty($_POST['id_fact'])) 
{
	$text_fact = htmlspecialchars(mysql_real_escape_string($_POST['text_fact']));
	$id_fact = (int) mysql_real_escape_string($_POST['id_fact']);

	$exist = mysql_num_rows(mysql_query("SELECT text_fact FROM facts WHERE id = '".$id_fact."' AND approved = '0'"));

	if ($exist == 1 AND strlen($text_fact) >= 50)
	{
		$update = mysql_query("UPDATE facts SET text_fact = '".$text_fact."' WHERE id = '".$id_fact."'");

		if ($update)
		{
			$code = $id_fact;
		}
	}
}

echo $code;