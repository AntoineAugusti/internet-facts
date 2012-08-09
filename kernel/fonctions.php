<?php
// NEWSLETTER 
$day_today = date("D");

if ($day_today == 'Mon') // Send on Monday
{
	$query_newsletter = mysql_fetch_array(mysql_query("SELECT send_newsletter FROM config WHERE id = '1'"));
	$send_newsletter = $query_newsletter['send_newsletter'];

	if ($send_newsletter == 0)
	{
		$message = $top_mail.mailRandomFacts(10).$end_mail;
		$today = date("d/m/Y");
		$subject = 'Newsletter - '.$today.'';

		$query = mysql_query("SELECT email, code FROM newsletter");

		while ($data = mysql_fetch_array($query)) 
		{
			$email = $data['email'];
			$code = $data['code'];

			$unsubscribe = '<br /><span style="font-size:80%">This email was adressed to you ('.$email.') because you are subscribed to our newsletter. If you want to unsubscribe, please follow <a href="http://internet-facts.com/newsletter/'.$code.'/" title="Unsubscribe" target="_blank">this link</a>.</span>';

			$mail = mail ($email, $subject, $message.$unsubscribe, $headers);
		}

		$update = mysql_query("UPDATE config SET send_newsletter = '1' WHERE id = '1'");
	}

}
elseif ($day == 'Tue') // Reset on Tuesday
{
	$query_newsletter = mysql_fetch_array(mysql_query("SELECT send_newsletter FROM config WHERE id = '1'"));
	$send_newsletter = $query_newsletter['send_newsletter'];

	if ($send_newsletter == 1)
	{
		$update = mysql_query("UPDATE config SET send_newsletter = '0' WHERE id = '1'"); 
	}
}

function mailRandomFacts($number)
{
	$query = mysql_query("SELECT f.id id, f.text_fact text_fact, e.username AS auteur, f.date date FROM facts f, email e WHERE f.approved = '1' AND e.id = f.id_author ORDER BY RAND() LIMIT ".$number."");
	$message = '';

	while ($result = mysql_fetch_array($query))
	{
		$id_fact = $result['id'];
		$txt_fact = $result['text_fact'];
		$auteur = $result['auteur']; 
		$date_fact = $result['date'];

		$message .= email_fact($id_fact, $txt_fact, $auteur, $date_fact);
	}

	return $message;
}

function display_page_bottom($page, $nombreDePages, $nom_lien_page, $div_redirection, $previous_page, $next_page)
{
	$page2 = $page + 1;
	$page3 = $page - 1;

	if ($page > 1)
	{
		if ($page >= 5)
		{
			echo '<span class="page_bottom_number"><a href="?'.$nom_lien_page.'=1">1</a></span> <span class="left" style="margin-left:5px;margin-top:-15px">...</span>';
			
			for ($num_page = $page-2;$num_page < $page;$num_page++)
			{
				echo '<span class="page_bottom_number"><a href="?'.$nom_lien_page.'='.$num_page.''.$div_redirection.'">'.$num_page.'</a></span>'; 
			}
		}
		else 
		{
			for ($num_page = '1';$num_page <= $page-1;$num_page++)
			{
				echo '<span class="page_bottom_number"><a href="?'.$nom_lien_page.'='.$num_page.''.$div_redirection.'">'.$num_page.'</a></span>'; 
			}
		}
	}

	if ($page <= $nombreDePages-4)
	{
		for ($num_page = $page;$num_page <= $page+2;$num_page++)
		{
			if ($num_page == $page)
			{
				echo '<span class="page_bottom_number_active"><a href="?'.$nom_lien_page.'='.$num_page.''.$div_redirection.'">'.$num_page.'</a></span>';
			}
			else
			{
				echo '<span class="page_bottom_number"><a href="?'.$nom_lien_page.'='.$num_page.''.$div_redirection.'">'.$num_page.'</a></span>';
			}
		}

		echo '<span class="left" style="margin-left:5px;margin-top:-13px">...</span>';
		echo '<span class="page_bottom_number"><a href="?'.$nom_lien_page.'='.$nombreDePages.''.$div_redirection.'">'.$nombreDePages.'</a></span>';
	}
	else
	{
		for ($num_page = $page;$num_page <= $nombreDePages;$num_page++)
		{
			if ($num_page == $page)
			{
				echo '<span class="page_bottom_number_active"><a href="?'.$nom_lien_page.'='.$num_page.''.$div_redirection.'">'.$num_page.'</a></span>';
			}
			else
			{
				echo '<span class="page_bottom_number"><a href="?'.$nom_lien_page.'='.$num_page.''.$div_redirection.'">'.$num_page.'</a></span>';
			}
		}
	}

	
	if ($page < $nombreDePages)
	{
		echo '<span class="page_bottom"><a href="?'.$nom_lien_page.'='.$page2.''.$div_redirection.'" title="'.$next_page.'">'.$next_page.'</a></span>';
	}
	if ($page > 1)
	{
		echo '<span class="page_bottom"><a href="?'.$nom_lien_page.'='.$page3.''.$div_redirection.'" title="'.$previous_page.'">'.$previous_page.'</a></span>';
	}

	echo '<div class="clear"></div>';
}
	
function display_page_top($nb_messages, $nb_messages_par_page, $lien, $previous_page, $next_page, $div_redirection = NULL, $margin = FALSE)
{
	$nombreDePages  = ceil($nb_messages / $nb_messages_par_page);
	if (isset($_GET[$lien]))
	{
		$page = mysql_real_escape_string($_GET[$lien]);
	}
	else 
	{
		$page = 1; 
	}

	if ($page > $nombreDePages) 
	{
		$page = $nombreDePages;
	}

	$page2 = $page + 1;
	$page3 = $page - 1;

	$page_index = '';
	if ($margin)
	{
		$margin_page = 'page_index';
	}

	
	if ($page < $nombreDePages)
	{
		echo '<span class="page '.$margin_page.'"><a href="?'.$lien.'='.$page2.''.$div_redirection.'" title="'.$next_page.'">'.$next_page.'</a></span>';
	}
	if ($page > 1)
	{
		echo '<span class="page '.$margin_page.'"><a href="?'.$lien.'='.$page3.''.$div_redirection.'" title="'.$previous_page.'">'.$previous_page.'</a></span>';
	}
	if ($nombreDePages != 1)
	{
		echo '<br/>';
	}

	$premierMessageAafficher = ($page - 1) * $nb_messages_par_page;

	return array($premierMessageAafficher, $nombreDePages, $page);
}

function username_is_valid ($username)
{
	if (preg_match("#^[a-z0-9_]{4,}$#", $username))
	{	
		return TRUE;
	}
	else
	{	
		return FALSE;
	}
}

function email_is_valid ($email)
{
	if(preg_match("#[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function meta_refresh ($time, $url)
{
	if (!is_numeric($time))
	{
		$time = '5';
	}

	echo '<meta http-equiv="refresh" content="'.$time.';url=\''.$url.'\'">';
}

function display_active_page ($pattern)
{
	$special_pages = array('index', 'random');
	
	if (!in_array($pattern, $special_pages))
	{
		$pattern = '#'.$pattern.'#';

		if (preg_match($pattern, $_SERVER['REQUEST_URI']))
		{
			echo ' class="active"';
		}
	}
	elseif ($pattern == 'random' AND preg_match('#random#', $_SERVER['REQUEST_URI']) OR ($pattern == 'index' AND ($_SERVER['REQUEST_URI'] == '/' OR preg_match('#\?p#', $_SERVER['REQUEST_URI'])) AND !preg_match('#random|author#', $_SERVER['REQUEST_URI'])))
	{
		echo ' class="active"';
	}
}

function display_moderate_facts($id)
{
	echo '
	<div class="moderate_fact_button">
		<a href="" onclick="moderate_fact(\'yes\','.$id.'); return false;"><span class="mini_icone icon_success"></span></a>
		<a href="" onclick="edit_fact('.$id.'); return false;"><span class="mini_icone edit"></span></a>
		<a href="" onclick="moderate_fact(\'no\','.$id.'); return false;"><span class="mini_icone delete" alt="Icone"></a>
	</div>';
}

function caracteresAleatoires($nombreDeCaracteres)
{
	$string = ""; 
	$chaine = "abcdefghijklmnpqrstuvwxyz123456789"; 
	srand((double)microtime()*1000000);

	for($i=0;$i<$nombreDeCaracteres; $i++)
	{
		$string .= $chaine[rand()%strlen($chaine)]; 
	}
	return $string;
}

function isset_is_int ($int)
{
	if (is_numeric($int))
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function display_title_and_description ()
{
	$default_title = ' | Internet Facts';
	$default_description = ' Internet Facts: facts about everything in your everyday life that you don\'t know. The Facts that you never knew in your life.';

	if (empty($_GET['mod'])) // Not fact / random / author.
	{
		if (preg_match('#addfact#', $_SERVER['REQUEST_URI']))
		{
			$title = 'Add a Fact'.$default_title;
			$description = 'Add your own Fact and be published on the website.'.$default_description;
		}
		elseif (preg_match('#newsletter#', $_SERVER['REQUEST_URI']))
		{
			$title = 'Newsletter'.$default_title;
			$description = 'Subscribe to our newsletter and get some new fresh Facts every Monday.'.$default_description;
		}
		elseif (preg_match('#about#', $_SERVER['REQUEST_URI']))
		{
			$title = 'About'.$default_title;
			$description = 'About the website: our team, our work, our project.'.$default_description;
		}
		elseif (preg_match('#contact#', $_SERVER['REQUEST_URI']))
		{
			$title = 'Contact'.$default_title;
			$description = 'Leave us a message and stay in touch with us.'.$default_description;
		}
		elseif (preg_match('#admin#', $_SERVER['REQUEST_URI']))
		{
			$title = 'Admin'.$default_title;
			$description = 'Control everything with the admin panel.'.$default_description;
		}
		elseif (preg_match('#404#', $_SERVER['REQUEST_URI']))
		{
			$title = 'Error'.$default_title;
			$description = 'Oops, an error occured!'.$default_description;
		}
		else // Index ?
		{
			$title = 'Internet Facts | The Facts that you never knew in your life';
			$description = substr($default_description, 1);
		}
	}
	elseif ($_GET['mod'] == 'random' OR $_GET['mod'] == 'author')
	{
		if ($_GET['mod'] == 'author' AND isset($_GET['username']) AND username_is_valid($_GET['username'])) // author
		{
			$username = $_GET['username'];

			$title = $username.'\'s Facts';
			$description = 'Facts by '.$username.' on Internet Facts. View all his Facts.';
		}
		else // Random
		{
			$title = 'Random Facts';
			$description = 'Random Facts about everything you want to know.';
		}

		if (isset_is_int($_GET['p']))
		{
			$get_page = (int) $_GET['p'];

			$title .= ' - Page '.$get_page.$default_title;
			$description .= $default_description;
		}
		else
		{
			$title .= $default_title;
			$description .= $default_description;
		}
	}
	elseif ($_GET['mod'] == 'fact') // fact
	{
		if (isset_is_int($_GET['id']))
		{
			$id_fact = (int) $_GET['id'];
			$query = mysql_query("SELECT text_fact FROM facts WHERE approved = '1' AND id = '".$id_fact."'");

			if (mysql_num_rows($query) == 1)
			{
				$fetch = mysql_fetch_array($query);
				$text_fact = $fetch['text_fact'];
			}

			$title = 'Fact #'.$id_fact.$default_title;
			$description = 'Fact #'.$id_fact.': \''.$text_fact.'\'';
		}
	}

	echo '<title>'.$title.'</title>'."\r\n";
	echo '<meta name="description" content="'.$description.'"/>'."\r\n\r\n";
}

function email_blue ($txt)
{
	return '<span style="color:#678DB7;font-weight:bold">'.$txt.'</span>';
}

function email_fact ($id_fact, $txt_fact, $auteur, $date_fact)
{
	$str = '
	<div style="padding:15px 1.5em;color:#555;background:#E0E0E0;margin:0 3em 40px;line-height:25px;border-bottom:1px solid #CCC;text-align:justify;">
		'.$txt_fact.'
		<div style="font-size:90%;margin-top: 5px;">
			<a href="http://internet-facts.com/fact/'.$id_fact.'/" title="View Fact #'.$id_fact.'">#'.$id_fact.'</a><span style="float:right">by <a href="http://internet-facts.com/author/'.$auteur.'" title="View his Facts">'.email_blue($auteur).'</a> on '.$date_fact.'</span>
		</div>
	</div>';

	return $str;
}

function cut_tweet($chaine)
{
	$domaine = 'internet-facts.com';
	$name_website = 'Internet Facts';

	$lg_max = 117;
	$twitter_username = '@FactsWikipedia';
	$longueur_max_ajout_twitter = 118 - strlen($twitter_username);
	
	if (strlen($chaine) > $lg_max) 
	{
		$chaine = substr($chaine, 0, $lg_max);
		$last_space = strrpos($chaine, " "); 

		// On ajoute ... à la suite de cet espace    
		$chaine = substr($chaine, 0, $last_space);
		$chaine .= '...';
	}
	elseif (strlen($chaine) <= $longueur_max_ajout_twitter)
	{
		$chaine .= ' '.$twitter_username;
	}

	$search = array ('%', ' ', '"');
	$replace = array('%25', '%20', '%34');
	$chaine = str_replace($search, $replace, $chaine);
	return $chaine;
}

function date_et_auteur ($auteur, $date_fact, $on, $by, $view_his_facts) 
{
	echo '<span class="right">'.$by.' <a href="/author/'.$auteur.'" title="'.$view_his_facts.'">'.$auteur.'</a> '.$on.' '.$date_fact.'</span><br/>';
}

function share_fb_twitter ($id_fact, $txt_fact, $share) 
{
	$domaine = 'internet-facts.com';
	$name_website = 'Internet Facts';

	$txt_tweet = cut_tweet($txt_fact);
	$url_encode = urlencode('http://'.$domaine.'/fact/'.$id_fact.'');
	echo '<div class="share_fb_twitter"><span class="fade_jquery"><div class="fb-like" data-href="http://internet-facts.com/fact-'.$id_fact.'" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></span><span class="right fade_jquery"><a href="http://twitter.com/share?url=http://'.$domaine.'/fact/'.$id_fact.'/&text='.$txt_tweet.'" class="twitter-share-button" data-count="none">Tweet</a></span></div>';
}

function captchaMath ()
{
	$n1 = mt_rand(1,84);

	if (in_array($n1, array('1', '2', '3', '6', '7', '14', '21', '42')))
	{
		$n2 = 42 / $n1;
		$phrase = ''.$n1.' x '.$n2.'';
	}
	else
	{
		if ($n1 <= 42)
		{
			$n2 = 42 - $n1;
			$phrase = ''.$n1.' + '.$n2.'';
		}
		else
		{
			$n2 = $n1 - 42;
			$phrase = ''.$n1.' - '.$n2.'';
		}
	}
	
	
	return array('42', $phrase);	
}

function captcha ()
{
	list($resultat, $phrase) = captchaMath();
	$_SESSION['captcha'] = $resultat;
	return $phrase;
}
?>	