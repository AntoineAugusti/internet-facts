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
elseif (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME']) AND $_POST['is_admin'] == TRUE AND !empty($_POST['text_fact']) AND !empty($_POST['username']) AND !empty($_POST['email']))
{
	/*
	Return a code to the Ajax function after a visitor tried to add a Fact
		- 0 : the form is not filled correctly
		- 1 : the Fact has been added
		- 2 : the email is not associated with the current username
		- 3 : the user has added to much Facts for today
	*/

	$username = trim(strtolower(htmlspecialchars(mysql_real_escape_string($_POST['username']))));
	$email = trim(htmlspecialchars(mysql_real_escape_string($_POST['email'])));
	$text_fact = trim(htmlspecialchars(mysql_real_escape_string($_POST['text_fact'])));
	$date = date("d/m/Y");

	if (email_is_valid($email) == FALSE OR username_is_valid($username) == FALSE)
	{
		$code_return = 0;
	}
	else
	{
	// Email and username are valid

		$exist_username = mysql_query("SELECT id, email FROM email WHERE username = '".$username."'");

		if (mysql_num_rows($exist_username) == 0)
		{
			// The username is not in the database. We should add in the DB a new username and insert the Fact.
			$insert_username = mysql_query("INSERT INTO email (email, username) VALUES ('".$email."', '".$username."')");
			$id_author_insert = mysql_insert_id(); // Retrieve the id of the last insert
			$insert_fact = mysql_query("INSERT INTO facts (text_fact, id_author, date, approved) VALUES ('".$text_fact."', '".$id_author_insert."', '".$date."', '0')");

			if ($insert_username AND $insert_fact)
			{
				$code_return = 1;
			}
			else
			{
				$code_return = 0;
			}
		}
		else
		{
			// The username already exist.
			$fetch_username = mysql_fetch_array($exist_username);

			if ($fetch_username['email'] != $email)
			{
				$code_return = 2;
			}
			else
			{
				// The email is associated with the username
				$count_posted_today = mysql_num_rows(mysql_query("SELECT f.id FROM facts f, email e WHERE e.username = '".$username."' AND f.date = '".$date."'"));

				if ($count_posted_today < $max_facts_per_day)
				{
					// The user has added less Facts than the maximum allowed per day, let's add his Fact

					$id_author_fetch = $fetch_username['id'];
					$insert_fact = mysql_query("INSERT INTO facts (text_fact, id_author, date, approved) VALUES ('".$text_fact."', '".$id_author_fetch."', '".$date."', '0')");

					if ($insert_fact == 1)
					{
						$code_return = 1;
					}
					else
					{
						$code_return = 0;
					}
				}
				else
				{
					// The user has added too much Facts for today
					$code_return = 3;
				}
			}
		}
	}

}

echo $code_return;