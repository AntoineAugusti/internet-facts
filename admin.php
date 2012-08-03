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
			Username:<br/>
			<input type="text" name="username"/><br/>
			<br />
			Password:<br/>
			<input type="password" name="password"/><br/>
			<br />
			<input type="submit" value="Log me!"/><br/>
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
	$query = mysql_query("SELECT f.id id, f.text_fact text_fact, e.username AS auteur, f.date date FROM facts f, email e WHERE f.approved = '0' AND f.id_author = e.id");
	$nb_facts_moderation = mysql_num_rows($query);

	$i = 1;

	$txt_fact = 'Fact';

	if ($nb_facts_moderation >= 2)
	{
		$txt_fact .= 's';
	}

	echo '
	<h2>Add a Fact</h2>
	<div class="post first-child">
		<div id="notification"></div>
		<form action="/ajax/add_fact.php" method="post" id="submit_fact_admin">
			<textarea name="text_fact" id="textarea_text_fact"></textarea>
			<br /><br />
			<input type="submit" value="Add my fact"/><br/>
			<div class="clear"></div>
		</form>
	</div>
	<h2>Moderate Facts<span class="right"><span class="blue italic" id="nb_facts_moderation">'.$nb_facts_moderation.'</span> <span id="txt_fact">'.$txt_fact.'</span></span></h2>
	';

	while ($result = mysql_fetch_array($query))
	{
		$id_fact = $result['id'];
		$txt_fact = $result['text_fact'];
		$auteur = $result['auteur']; 
		$date_fact = $result['date'];

		// We want to know if the fact is the first or the last of the page in order to change the margin
		$div_child = '';

		if ($i == 1)
		{
			$div_child = 'first-child';
		}
	?>
		<div class="post <?php echo $div_child; ?>" data-id="<?php echo $id_fact; ?>">
			<span class="txt_fact" data-id="<?php echo $id_fact; ?>"><?php echo $txt_fact; ?></span><br/>
			<div class="footer_fact" data-id="<?php echo $id_fact; ?>">
				<a href="/fact/<?php echo $id_fact; ?>/" title="View Fact #<?php echo $id_fact; ?>">#<?php echo $result['id']; ?></a><?php display_moderate_facts($id_fact); ?><?php date_et_auteur ($auteur,$date_fact,$on,$by,$view_his_facts); ?>
			</div>
		</div>
	<?php
		$i++;
	}
}

include 'footer.php';
?>