<?php
include 'header.php';

echo '<h1>Add a Fact</h1>';

echo '
<div id="intro_like">
	<div class="img_infos fade"></div>
	<h2>Informations</h2>
	Build with us Internet Facts and add your own Fact to the website.<br />
	<ul class="margin margin_top_bottom_15">
		<li>Your Fact should be relatively short.</li>
		<li>Your Fact must be in english.</li>
		<li>Your Fact does not already exist on the website.</li>
		<li>Your Fact does not include spelling mistakes.</li>
		<li>You have to respect the syntax : capital letters, commas, spaces...</li>
	</ul>
	Your Fact will then be approved or rejected by our team, you will be informed by email.
</div>

<div class="post first-child">
	<div class="img_add_fact fade"></div>
	<h2>Write your Fact</h2>
	<div id="notification"></div>
	<div class="add_fact">
		<form action="/ajax/add_fact.php" method="post" id="submit_fact_user">
			Your username:<br />
			<input type="text" name="username" id="username"/><br />
			<span class="min_info">Minimum 4 letters. You can use letters (a-z), numbers(0-9) and underscores (_).</span>
			<br /><br />
			Your email:<br />
			<input type="text" name="email" id="email"/><br />
			<span class="min_info">If you want to add more Facts with your username, you will have to use the same email. Please enter a valid email address. It will never be published on the site.</span>
			<br /><br />
			Your Fact:<br/>
			<textarea name="text_fact" id="textarea_text_fact" placeholder="Write your Fact just here, with all your love."></textarea><br/>
			<span class="min_info">Minimum 50 characters.</span>
			<br /><br />
			<input type="submit" value="Add my fact"/><br />
			<div class="clear"></div>
		</form>
	</div>
</div>
';

include 'footer.php';
?>