<?php
session_start();
require '../kernel/config.php';
header("Access-Control-Allow-Origin: *");

$div_error = '<div class="error">An error occured.</div>';

if (preg_match('/internet-facts.com/', $_SERVER['SERVER_NAME']) AND $_SESSION['logged'] == TRUE AND !empty($_POST['approve']) AND !empty($_POST['id_fact'])) 
{
	$id_fact = (int) htmlspecialchars(mysql_real_escape_string($_POST['id_fact']));
	$approve = htmlspecialchars(mysql_real_escape_string($_POST['approve']));

	$reponse = mysql_query("SELECT f.id id, f.text_fact text_fact, e.username AS auteur, f.date date, e.email email FROM facts f, email e WHERE f.approved = '0' AND f.id = '".$id_fact."' AND f.id_author = e.id");

	if (mysql_num_rows($reponse) == 1)
	{
		if ($approve == 'yes')
		{
			$new_approve = 1;
			$email_subject = 'Fact #'.$id_fact.' approved';
			$txt_email = 'Your Fact #'.$id_fact.' has been '.email_blue('approved').' by our team! Congratulations!<br/><br/>Here is your Fact:<br/><br/>';
		}
		else
		{
			$new_approve = -1;
			$email_subject = 'Fact #'.$id_fact.' unapproved';
			$txt_email = 'Your Fact #'.$id_fact.' has been '.email_blue('unapproved').' by our team. Keep posting!<br/><br/>Here is your Fact:<br/><br/>';
		}

		$update_fact = mysql_query("UPDATE facts SET approved = '".$new_approve."' WHERE id = '".$id_fact."'");

		if ($update_fact)
		{
			$result = mysql_fetch_array($reponse);

			$id_fact = $result['id'];
			$txt_fact = $result['text_fact'];
			$auteur = $result['auteur']; 
			$date_fact = $result['date'];
			$email = $result['email'];

			$email_message = $top_mail.'Hello '.email_blue($auteur).',<br/><br/>'.$txt_email.email_fact($id_fact, $txt_fact, $auteur, $date_fact).$end_mail;

			$mail = mail($email, $email_subject, $email_message, $headers);

			if ($mail)
			{
				echo '<div class="success">The author has been notified by email</div>';
			}
			else
			{
				echo $div_error;
				echo '1';
			}
		}
		else
		{
			echo $div_error;
			echo '2';
		}
	}
	else
	{
		echo $div_error;
		echo '3';
	}
}
else
{
	echo $div_error;
	echo '4';
}