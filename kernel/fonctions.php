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

	if ($page > 1)
	{
		echo '<span class="page_bottom"><a href="?'.$nom_lien_page.'='.$page3.''.$div_redirection.'">'.$previous_page.'</a> || ';
	}
	if ($page == 1 AND $page < $nombreDePages)
	{
		echo '<span class="page_bottom">';
	}
	if ($page < $nombreDePages)
	{
		echo '<a href="?'.$nom_lien_page.'='.$page2.''.$div_redirection.'">'.$next_page.'</a>';
	}
	if ($nombreDePages != '1')
	{
		echo '</span><br>';
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

	if ($page > 1)
	{
		if ($margin)
		{
			echo '<span class="page page_index"><a href="?'.$lien.'='.$page3.''.$div_redirection.'">'.$previous_page.'</a> || ';
		}
		else
		{
			echo '<span class="page"><a href="?'.$lien.'='.$page3.''.$div_redirection.'">'.$previous_page.'</a> || ';
		}
	}

	if ($page == 1 AND $page < $nombreDePages)
	{
		if ($margin)
		{
			echo '<span class="page page_index">';
		}
		else
		{
			echo '<span class="page">';
		}
	}

	if ($page < $nombreDePages)
	{
		echo '<a href="?'.$lien.'='.$page2.''.$div_redirection.'">'.$next_page.'</a>';
	}
	
	if ($nombreDePages != '1')
	{
		echo '</span><br>';
	}

	$premierMessageAafficher = ($page - 1) * $nb_messages_par_page;

	return array($premierMessageAafficher, $nombreDePages, $page);
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
	elseif ($pattern == 'random' AND preg_match('#random#', $_SERVER['REQUEST_URI']) OR ($pattern == 'index' AND $_SERVER['REQUEST_URI'] == '/'))
	{
		echo ' class="active"';
	}
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
	echo '<span class="right">'.$by.' <a href="author/'.$auteur.'" title="'.$view_his_facts.'">'.$auteur.'</a> '.$on.' '.$date_fact.'</span><br>';
}

function share_fb_twitter ($id_fact, $txt_fact, $share) 
{
	$domaine = 'internet-facts.com';
	$name_website = 'Internet Facts';

	$txt_tweet = cut_tweet($txt_fact);
	$url_encode = urlencode('http://'.$domaine.'/fact/'.$id_fact.'');
	echo '<div class="share_fb_twitter"><span class="fade_jquery"><iframe src="//www.facebook.com/plugins/like.php?href= '.$url_encode.'&amp;send=FALSE&amp;layout=button_count&amp;width=110&amp;show_faces=FALSE&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:21px;" allowTransparency="TRUE"></iframe></span><span class="right fade_jquery"><a href="http://twitter.com/share?url=http://'.$domaine.'/quote-'.$id_fact.'&text='.$txt_tweet.'" class="twitter-share-button" data-count="none">Tweet</a></span></div>';
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