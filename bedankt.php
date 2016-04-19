<?php # Script 18.6 - register.php
// This is the registration page for the site.
$page_title = 'De Bookstore - Bedankt';
$active = 6;
include ('includes/header.html');
?>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

	
	<p>Bedankt voor uw opmerking, we zullen zo spoedig mogelijk antwoorden.</p>
	<div class="fb-like" data-href="http://www.debookstore.nl" data-layout="box_count" data-action="like" data-show-faces="true" data-share="true"></div>
	<br><br><br><br><br><br>
	

<?php include ('includes/footer.html'); ?>