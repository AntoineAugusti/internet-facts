<?php
include 'header.php';

echo '<h1>Search</h1>';

$search_form = '
<div class="post first-child search_form">
	<div class="img_search fade"></div>
	<h2>Enter your search</h2>
	<form action="/search" method="get">
		<input type="text" name="q" placeholder="A word, a Fact, an user..."/><br/>
		<br />
		<input type="submit" value="Search"/><br/>
		<div class="clear"></div>
	</form>
</div>
';

if (empty($_GET['q']) OR $_GET['q'] == 'A work, a Fact, an user...')
{
	echo $search_form;
}
else
{
	$value_search = htmlspecialchars(mysql_real_escape_string($_GET['q']));

	if (strlen($value_search) < 50)
	{
		$query = mysql_query("INSERT INTO search (text) VALUES ('".$value_search."') ON DUPLICATE KEY UPDATE value = value + 1");
	}

	// Recherche avec MATCH sur l'index FULLTEXT pour des résultats plus pertinents
	if (strlen($value_search) >= 4)
	{
		$query_fact = mysql_query("SELECT f.id id, f.text_fact text_fact, e.username AS auteur, f.date date FROM facts f, email e WHERE f.approved = '1' AND f.id_author = e.id AND MATCH (f.text_fact) AGAINST('$value_search') > 0 ORDER BY MATCH (f.text_fact) AGAINST('$value_search') DESC LIMIT 0,15");
		$num_rows_facts = mysql_num_rows($query_fact);
		$query_match = TRUE;
	}
	// Si aucun résultat "pertinent" ou si le mot recherché est trop court pour une recherche MATCH, on effectue une recherche classique
	if (strlen($value_search) < 4 OR $num_rows_facts == 0)
	{
		$query_fact = mysql_query("SELECT f.id id, f.text_fact text_fact, e.username AS auteur, f.date date FROM facts f, email e WHERE f.approved = '1' AND f.id_author = e.id AND f.text_fact LIKE '%".$value_search."%' ORDER BY RAND() LIMIT 0,15");
		$num_rows_facts = mysql_num_rows($query_fact);
		$query_match = FALSE;	
	}

	$query_users = mysql_query("SELECT id, username FROM email WHERE username LIKE '%".$value_search."%' ORDER BY username ASC LIMIT 0,15");
	$num_rows_users = mysql_num_rows($query_users);

	$num_rows_result = $num_rows_facts + $num_rows_users;

	if ($num_rows_result >= 1) // We've got at least a Fact or a user
	{
		$result_text = 'result';
		if ($num_rows_result >= 2)
		{
			$result_text .= 's';
		}

		if ($num_rows_facts >= 1) // We've got at least a Fact
		{
			$fact_text = 'Fact';
			if ($num_rows_facts >= 2)
			{
				$fact_text .= 's';
			}

			$result_detail = '(<span class="blue italic">'.$num_rows_facts.'</span> <a href="#facts" class="black" title="View Fact results">'.$fact_text.'</a>';

			if ($num_rows_users >= 1) // We've got Fact AND user result
			{
				$user_text = 'User';
				if ($num_rows_users >= 2)
				{
					$user_text .= 's';
				}

				$result_detail .= ' - <span class="blue italic">'.$num_rows_users.'</span> <a href="#users" class="black" title="View users results">'.$user_text.'</a>)';
			}
			else
			{
				$result_detail .= ')';
			}
		}
		else // We've got at least a user (but no Facts)
		{
			$user_text = 'User';
			if ($num_rows_users >= 2)
			{
				$user_text .= 's';
			}

			$result_detail = '(<span class="blue italic">'.$num_rows_users.'</span> <a href="#users" class="black" title="View users results">'.$user_text.'</a>)';
		}

		// Display general results
		echo '
		<div class="intro_like">
			<h3>Search results for <span class="blue">"'.$value_search.'"</span><span class="right"><span class="blue italic">'.$num_rows_result.'</span> '.$result_text.' '.$result_detail.'</span></h3>
		</div>
		';

		// Display results for Facts
		if ($num_rows_facts >= 1)
		{
			$i = 1;

			echo '<h2 id="facts"><span class="blue italic">'.$num_rows_facts.'</span> '.$fact_text.'</h2>';

			while ($result = mysql_fetch_array($query_fact))
			{
				$id_fact = $result['id'];
				$txt_fact = $result['text_fact'];
				$auteur = $result['auteur']; 
				$date_fact = $result['date'];

				// We want to know if the fact is the first or the last of the page in order to change the margin
				$div_child = '';

				if ($i == 1)
				{
					$div_child = ' first-child';
				}
				elseif ($i == $num_rows_facts)
				{
					$div_child = ' last-child';
				}
			?>
				<div class="post<?php echo $div_child; ?>">
					<?php echo $txt_fact; ?><br/>
					<div class="footer_fact">
						<a href="/fact/<?php echo $id_fact; ?>/" title="View Fact #<?php echo $id_fact; ?>">#<?php echo $result['id']; ?></a><?php date_and_author ($auteur,$date_fact,$on,$by,$view_his_facts); ?>
					</div>
					<?php share_fb_twitter ($id_fact,$txt_fact,$share); ?> 
				</div>
			
		<?php
				$i++;
			}
		}

		if ($num_rows_users >= 1)
		{
			echo '<h2 id="users"><span class="blue italic">'.$num_rows_users.'</span> '.$user_text.'</h2>';
			echo '<ul class="result_users">';

			while ($data = mysql_fetch_array($query_users))
			{
				$username = $data['username'];
				echo '<li><a href="/author/'.$username.'" title="View '.$username.'\'s Facts">'.$username.'</a></li>';
			}

			echo '</ul>';
		}

	}
	else
	{ // Oops, no result

		echo '<div class="error">Oops, your search for <span class="blue">"'.$value_search.'"</span> returned no results. :(</div>';
		echo $search_form;

	} 
}



include 'footer.php';