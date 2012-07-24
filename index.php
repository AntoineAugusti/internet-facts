<?php
include 'header.php';

	// Display a description of the website only in the index. It can be hide thanks to Ajax and a session.
	if (!isset($_SESSION['hide_intro']) AND $_SESSION['hide_intro'] != TRUE AND empty($_GET['mod']))
	{
		echo '
		<div id="intro">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam malesuada lobortis tortor eu luctus. Nunc lobortis pretium nunc eu dignissim. Ut rhoncus ipsum hendrerit nibh varius nec cursus ligula mollis. Suspendisse sed orci justo, sed interdum nisl. Mauris sit amet commodo urna. Curabitur vel mi at risus lacinia tempor.<br>
			<span class="right"><a onclick="hide_intro();return false;" title="Hide the introduction">Okey, I understand. Hide this!</a></span>
			<div class="clear"></div>
		</div>';
	}

	// Retrieve all quotes and display title and buttons for pages ONLY if we are in home or random.
	if (empty($_GET['mod']) OR $_GET['mod'] == 'random' OR $_GET['mod'] == 'author')
	{

		if ($_GET['mod'] != 'author') // index or random
		{
			$donnees = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS nb_facts FROM facts WHERE approved = '1'"));
		}
		else // author
		{
			if(!username_is_valid($_GET['username']) OR empty($_GET['username']))
			{
				header('Location: http://'.$domaine.'/404');
			}
			else
			{
				$username = mysql_real_escape_string($_GET['username']);
				$donnees = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS nb_facts FROM facts WHERE approved = '1' AND username = '".$username."'"));
			}
		}

		if (empty($_GET['mod'])) // index
		{
			if ((empty($_GET['p']) OR $_GET['p'] == 1))
			{
				echo '<h1>Latest Facts</h1>';
			}
			else
			{
				$get_page = (int) $_GET['p'];
				echo '<h1>Latest Facts - <span class="italic">Page <span class="blue">'.$get_page.'</span></span></h1>';
			}
		}
		elseif ($_GET['mod'] == 'random') // random
		{
			if ((empty($_GET['p']) OR $_GET['p'] == 1))
			{
				echo '<h1>Random Facts</h1>';
			}
			else
			{
				$get_page = (int) $_GET['p'];
				echo '<h1>Random Facts - <span class="italic">Page <span class="blue">'.$get_page.'</span></span></h1>';
			}
		}
		else // author
		{
			$nb_facts = $donnees['nb_facts'];
			$facts_text = 'fact';
			
			if ($nb_facts >= 2 Or $nb_facts == 0)
			{
				$facts_text .= 's';
			}

			if ((empty($_GET['p']) OR $_GET['p'] == 1))
			{
				echo '<h1>Facts '.$by.' <span class="blue">'.$username.'</span><span class="right"><span class="italic"><span class="blue">'.$nb_facts.'</span> '.$facts_text.'</span></h1>';
			}
			else
			{
				$get_page = (int) $_GET['p'];
				echo '<h1>Facts '.$by.' <span class="blue">'.$username.'</span> - <span class="italic">Page <span class="blue">'.$get_page.'</span></span><span class="right"><span class="italic"><span class="blue">'.$nb_facts.'</span> '.$facts_text.'</span></h1>';
			}
		}

		$display_page_top = display_page_top($donnees['nb_facts'], $nb_messages_page, 'p', $previous_page, $next_page, NULL, TRUE);
		$premierMessageAafficher = $display_page_top[0];
		$nombreDePages = $display_page_top[1];
		$page = $display_page_top[2];
	}

	// Display the title if we have only one fact and do the SQL query in order to loop
	if (empty($_GET['mod'])) // index
	{
		$reponse = mysql_query("SELECT id, text_fact, username AS auteur, date FROM facts WHERE approved = '1' ORDER BY id DESC LIMIT ".$premierMessageAafficher.", ".$nb_messages_page."");
	}
	elseif($_GET['mod'] == 'random') // random
	{
		$reponse = mysql_query("SELECT id, text_fact, username AS auteur, date FROM facts WHERE approved = '1' ORDER BY RAND() LIMIT ".$premierMessageAafficher.", ".$nb_messages_page."");
	}
	elseif ($_GET['mod'] == 'fact') // fact
	{
		$id_fact = (int) $_GET['id'];

		if (empty($id_fact))
		{
			header('Location: http://'.$domaine.'/404');
		}
		else
		{
			echo '<h1>Fact #'.$id_fact.'</h1>';

			$reponse = mysql_query("SELECT id, text_fact, username AS auteur, date FROM facts WHERE approved = '1' AND id = '".$id_fact."'");

			if (mysql_num_rows($reponse) == 0)
			{
				header('Location: http://'.$domaine.'/404');
			}
		}

	}
	elseif ($_GET['mod'] == 'author') // author
	{
		if ($donnees['nb_facts'] == 0)
		{
			echo '<div class="error">We\'re sorry but this author hasn\'t approved facts yet.</div>';
		}
		else
		{
			$reponse = mysql_query("SELECT id, text_fact, username AS auteur, date FROM facts WHERE approved = '1' AND username = '".$username."' ORDER BY id DESC LIMIT ".$premierMessageAafficher.", ".$nb_messages_page."");
		}
	}

	if ($donnees['nb_facts'] != 0 OR mysql_num_rows($reponse) != 0)
	{
		// Display all the quotes. Available for every mod
		$i = 1;

		while ($result = mysql_fetch_array($reponse))
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
			elseif ($i == $nb_messages_page)
			{
				$div_child = 'last-child';
			}
		?>
			<div class="post <?php echo $div_child; ?>">
				<?php echo $txt_fact; ?><br>
				<div class="footer_fact">
					<a href="/fact/<?php echo $id_fact; ?>">#<?php echo $result['id']; ?></a><?php date_et_auteur ($auteur,$date_fact,$on,$by,$view_his_facts); ?>
				</div>
				<?php share_fb_twitter ($id_fact,$txt_fact,$share); ?> 
			</div>
		<?php
			$i++;
		}

		// Display buttons for pages except for fact.
		if (empty($_GET['mod']) OR $_GET['mod'] == 'random' OR $_GET['mod'] == 'author')
		{
			display_page_bottom($page, $nombreDePages, 'p', NULL, $previous_page, $next_page);
		}
	}
	?>
	



<?php
include 'footer.php';
?>