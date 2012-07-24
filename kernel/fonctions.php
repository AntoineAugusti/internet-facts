<?php

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
		echo '<br>';
	}

	$premierMessageAafficher = ($page - 1) * $nb_messages_par_page;

	return array($premierMessageAafficher, $nombreDePages, $page);
}

function username_is_valid ($username)
{
	if (preg_match("#^[a-z0-9_]+$#", $username))
	{	
		return TRUE;
	}
	else
	{	
		return FALSE;
	}
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
	$default_description = ' Internet Facts: facts about everything in your everyday life that you don\'t know.';

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
			$title = 'Internet Facts | Facts about everything you want to know';
			$description = $default_description;
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

function cut_tweet($chaine)
{
	$domaine = 'internet-facts.com';
	$name_website = 'Internet Facts';

	$lg_max = 117;
	$longueur_max_ajout_twitter = 102;
	$username_twitter = '@The_GoogleFacts';	
	
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
		$chaine .= ' '.$username_twitter;
	}

	$chaine = str_replace(' ', '%20', $chaine);
	return $chaine;
}

function date_et_auteur ($auteur, $date_fact, $on, $by, $view_his_facts) 
{
	echo '<span class="right">'.$by.' <a href="/author/'.$auteur.'" title="'.$view_his_facts.'">'.$auteur.'</a> '.$on.' '.$date_fact.'</span><br>';
}

function share_fb_twitter ($id_fact, $txt_fact, $share) 
{
	$domaine = 'internet-facts.com';
	$name_website = 'Internet Facts';

	$txt_tweet = cut_tweet($txt_fact);
	$url_encode = urlencode('http://'.$domaine.'/fact/'.$id_fact.'');
	echo '<div class="share_fb_twitter"><span class="fade_jquery"><iframe src="//www.facebook.com/plugins/like.php?href= '.$url_encode.'&amp;send=FALSE&amp;layout=button_count&amp;width=110&amp;show_faces=FALSE&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:21px;" allowTransparency="TRUE"></iframe></span><span class="right fade_jquery"><a href="http://twitter.com/share?url=http://'.$domaine.'/fact/'.$id_fact.'&text='.$txt_tweet.'" class="twitter-share-button" data-count="none">Tweet</a></span></div>';
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