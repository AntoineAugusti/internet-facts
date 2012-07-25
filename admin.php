<?php
include 'header.php';

echo '<h1>Admin Panel</h1>';

if ((!$_SESSION['logged'] OR !isset($_SESSION['logged'])) AND $_GET['action'] != 'send') // Login Form
{
	echo '
	<div class="post first-child login_form">
		<div class="img_log fade"></div>
		<h2>Sign in</h2>
		<form action="/admin?action=send" method="post">
			Username:<br>
			<input type="text" name="username"><br>
			<br />
			Password:<br>
			<input type="password" name="password"><br>
			<br />
			<input type="submit" value="Log me!"><br>
			<div class="clear"></div>
		</form>
	</div>
	';
}
elseif ($_GET['action'] == 'send') // Send the form
{
	$username_post = mysql_real_escape_string($_POST['username']);
	$password_post = mysql_real_escape_string($_POST['password']);

	if (in_array($username_post, $username) AND in_array($password_post, $password))
	{
		$_SESSION['logged'] = TRUE;

		echo '<div class="success">You\'re now logged '.ucfirst($username_post).'! Now, let\'s drink.</div>';
		echo '<div class="celebrate hide_bomb"></div>';
	}
	else
	{
		echo '<div class="error">No, no, no, no and no! That\'s WRONG dude!</div>';
		echo '<img src="/images/baby_cry.jpg" alt="Baby Cry" class="baby_cry hide_bomb" />';
	}

	meta_refresh(6, '/admin');
}
elseif($_SESSION['logged'] == TRUE)
{
	echo '
	<div class="post first-child">
		<h2>Add a Fact</h2>
		<div id="notification"></div>
		<form action="/ajax/add_fact.php" method="post" id="submit_fact_admin">
			<textarea name="text_fact" id="textarea_text_fact"></textarea>
			<br /><br />
			<input type="submit" value="Add my fact"><br>
			<div class="clear"></div>
		</form>
	</div>
	';
}

include 'footer.php';
?>