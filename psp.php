<?php
	include ('includes/header.html');

	if (@$_SESSION['Klant_ID'] == false) {
		echo "<p>U ben nog niet ingelogd. <a href=\"login.php\">Log eerst in</a> om uw bestelling af te ronden.</p>\n";
   		echo "Nog geen account? <a href=\"register.php\">Registreer hier!</a>";
   		include ('includes/footer.html');
   		die();
	}else if ($_SESSION['Klant_ID'] == true) {
	
	echo '<div class="psp">
	Kies de <b>betaalmethode</b> waarmee u wilt afrekenen:<br/><br/>
	<a href="checkout.php?psp=creditcard">Creditcard</a><br/>
	<img src="images/creditcard.jpg" width="200px"/><br/><br/>
	<a href="checkout.php?psp=ideal">iDeal</a><br/>
	<img src="images/ideal.jpg" width="200px"/>
	</div>';

	include ('includes/footer.html');
}

?>