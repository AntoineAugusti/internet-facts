<?php
session_start();
header("Access-Control-Allow-Origin: *");

if (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME'])) 
{
	if (!isset($_SESSION['hide_intro']) AND $_SESSION['hide_intro'] != TRUE)
	{
		$_SESSION['hide_intro'] = TRUE;
	}
}