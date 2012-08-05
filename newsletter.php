<?php
include 'header.php';
$code = htmlspecialchars(mysql_real_escape_string($_GET['code']));

echo '<h1>Newsletter</h1>';

if (empty($code))
{
	echo '
	<div id="intro_like" class="newsletter_intro">
		<div class="img_newsletter"></div>
		Want to learn amazing things every Monday morning? Well, you are totally right!<br/>
		<br/>
		By subscribing to the newsletter you will receive every Monday morning in your inbox 15 random Facts from Internet Facts.<br/>
		<br/>
		The opportunity to start the week in a good mood with your favorite Facts!<br/>
	</div>';

	echo '
	<div class="post">
		<h2>Subscribe</h2>
		<div id="notification"></div>
		<form id="form_newsletter" action="/ajax/newsletter.php" method="post">
			Your email address:<br/>
			<input type="email" name="email" id="input_email" placeholder="Your email address" /><br/>
			<span class="min_info">Please enter a valid email address. It will never be published on the site.</span><br/>
			<br/>
			<input type="submit" value="Subscribe!"/>
		</form>
	</div>';
}
else
{
	$query = mysql_query("SELECT email FROM newsletter WHERE code = '".$code."'");
	$exist_email = mysql_num_rows($query);

	if ($exist_email != 1)
	{
		$result = '<div class="error">We can\'t find an email address with this code (<span class="blue">'.$code.'</span>).</div>';
	}
	else
	{
		$delete = mysql_query("DELETE FROM newsletter WHERE code = '".$code."'");

		if ($delete)
		{
			$fetch = mysql_fetch_array($query);
			$email = $fetch['email'];

			$result = '<div class="success">You are now unsubscribed with your email address (<span class="blue">'.$email.'</span>).</div>';
		}
		else
		{
			$result = 'An error occured';
		}
	}

	echo '
	<div class="post">
		<h2>Unsuscribe</h2>
		'.$result.'
	</div>';
}

include 'footer.php';