<?php
session_start();
require 'kernel/config.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Internet Facts</title>
		<meta name="description" content="Internet Facts : some facts you will probably don't know about everything on Earth."/>

		<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png"/>
		<meta property="og:image" content="/images/icon_facebook.png" /> 
		
		<link rel="stylesheet" href="/css/uniform.css" type="text/css" media="all">
		<link rel="stylesheet" href="/css/style.css" type="text/css" media="all">
		<meta name="viewport" content="width=device-width">

		<script src="//code.jquery.com/jquery-latest.min.js"></script>

		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-12045924-26']);
		  _gaq.push(['_setDomainName', 'internet-facts.com']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
	</head>
<body>
	<div id="header">
		<div class="content">
			<div id="logo">
				<a href="/" title="Internet Facts">Internet Facts</a>
			</div>

			<div id="social-networks">
				<a href="https://twitter.com/The_GoogleFacts" class="twitter-follow-button" data-show-count="false">Follow @The_GoogleFacts</a>
			</div>
		</div>
	</div><!-- END HEADER -->

	<div id="menu" class="group">
		<a href="/" title="Home" class="active"><span class="icon home"></span>Home</a>
		<a href="/random" title="Random Facts"><span class="icon random"></span>Random Facts</a>
		<a href="/addfact" title="Add a Fact"><span class="icon add"></span>Add a Fact</a>
		<a href="/newsletter" title="Newsletter"><span class="icon newsletter"></span>Newsletter</a>
		<a href="/about" title="About"><span class="icon about"></span>About</a>
		<a href="/contact" title="Contact"><span class="icon contact"></span>Contact</a>
	</div><!-- END MENU -->

	<div id="content">