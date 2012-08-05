<?php
session_start();
require '../kernel/config.php';
header("Access-Control-Allow-Origin: *");

$code_return = 0;

if (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME']) AND !empty($_POST['email']) AND email_is_valid($_POST['email'])) 
{
	$email = htmlspecialchars(mysql_real_escape_string($_POST['email']));

	$exist_email = mysql_num_rows(mysql_query("SELECT id FROM newsletter WHERE email = '".$email."'"));

	if ($exist_email != 0)
	{
		$code_return = 2;
	}
	else
	{
		$code = caracteresAleatoires(10);

		$insert = mysql_query("INSERT INTO newsletter (email, code) VALUES ('".$email."', '".$code."')");

		if ($insert)
		{
			$code_return = 1;
		}
	}
}

echo $code_return;