<?php # Script 18.6 - register.php
// This is the registration page for the site.
$page_title = 'Register';
$active = 9;
include ('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form.

	// Need the database connection:
	require ("includes/mysqli_connect.php");
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);

	// Assume invalid values:
	$fn = $ln = $e = $p = $l = $w = $pc = $t = $s = FALSE;
	
	// Check for a first name:
	if (preg_match ('/^[A-Z \'.-]{2,20}$/i', $trimmed['Voornaam'])) {
		$fn = mysqli_real_escape_string ($dbc, $trimmed['Voornaam']);
	} else {
		echo '<p class="error">Voer alstublieft uw voornaam in</p>';
	}

	// Check for a last name:
	if (preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['Achternaam'])) {
		$ln = mysqli_real_escape_string ($dbc, $trimmed['Achternaam']);
	} else {
		echo '<p class="error">Voer alstublieft uw achternaam in</p>';
	}

	// Validate the gender:
	if (isset($trimmed['Geslacht'])) {
		$g = mysqli_real_escape_string ($dbc, $trimmed['Geslacht']);
	} 
	
	// Check for an email address:
	if (filter_var($trimmed['Email'], FILTER_VALIDATE_EMAIL)) {
		$e = mysqli_real_escape_string ($dbc, $trimmed['Email']);
	} else {
		echo '<p class="error">Voer alstublieft een geldig e-mail adres in</p>';
	}

	// Check for a password and match against the confirmed password:
	if (preg_match ('/^\w{4,20}$/', $trimmed['Wachtwoord1']) ) {
		if ($trimmed['Wachtwoord1'] == $trimmed['Wachtwoord2']) {
			$p = mysqli_real_escape_string ($dbc, $trimmed['Wachtwoord1']);
		} else {
			echo '<p class="error">De twee wachtwoorden zijn niet hetzelfde.</p>';
		}
	} else {
		echo '<p class="error">Voeg alstublieft een geldig wachtwoord in.</p>';
	}

	//Check for an age
	if (isset($trimmed['Leeftijd']) && is_numeric($trimmed['Leeftijd'])) {
		$l = mysqli_real_escape_string($dbc, $trimmed['Leeftijd']);
	}else{
		echo '<p class="error">Voer alstublieft uw leeftijd correct in.</p>';
	}

	//Check for an woonplaats
	if (isset($trimmed['Woonplaats'])) {
		$w = mysqli_real_escape_string($dbc, $trimmed['Woonplaats']);
	}else{
		echo '<p class="error">Voer alstublieft een geldige woonplaats in.</p>';
	}

	//Check for an postcode
	if (isset($trimmed['Postcode'])) {
		$pc = mysqli_real_escape_string($dbc, $trimmed['Postcode']);
	}else{
		echo '<p class="error">Voer alstublieft een geldige postcode in.</p>';
	}

	//Check for an telefoonnr
	if (isset($trimmed['Telefoonnr']) && is_numeric($trimmed['Telefoonnr'])) {
		$t = mysqli_real_escape_string($dbc, $trimmed['Telefoonnr']);
	}else{
		echo '<p class="error">Voer alstublieft een geldig telefoonnummer in.</p>';
	}

	//Check for an age
	if (isset($trimmed['Studentennr']) && is_numeric($trimmed['Studentennr'])) {
		$s = mysqli_real_escape_string($dbc, $trimmed['Studentennr']);
	}else{
		echo '<p class="error">Voer alstublieft een geldig studentennummer in.</p>';
	}
	
	if ($fn && $ln && $g && $e && $p && $l && $w && $pc && $t && $s) { // If everything's OK...

		// Make sure the email address is available:
		$q = "SELECT Klant_ID FROM klant WHERE email='$e'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) { // Available.

			// Create the activation code:
			$a = md5(uniqid(rand(), true));

			// Add the user to the database:
			$q = "INSERT INTO klant (Voornaam, Achternaam, Geslacht, Leeftijd, Woonplaats, Postcode, Telefoonnr, Email, Wachtwoord, Studentennr) VALUES ('$fn', '$ln', '$g', '$l', '$w', '$pc', '$t', '$e', SHA1('$p'), '$s'  );";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
				
				// Finish the page:
				echo '<h3>Bedankt voor uw registratie, U kunt nu <a href="login.php">inloggen</a>. </h3>';
				echo "<div class=\"hidden\"></div>";
				include ('includes/footer.html'); // Include the HTML footer.
				exit(); // Stop the page.
				
			} else { // If it did not run OK.
				echo '<p class="error">U kon niet worden geregistreerd door een probleem in ons systeem, probeer later opnieuw</p>';
			}
			
		} else { // The email address is not available.
			echo '<p class="error">Dit e-mail adres is al geregistreerd.</p>';
		}
		
	} else { // If one of the data tests failed.
		echo '<p class="error">Probeer nog een keer.</p>';
	}

	mysqli_close($dbc);

} // End of the main Submit conditional.
?>
	
	<form action="register.php" method="POST">
		<fieldset><legend>Registreer hier:</legend>
			<ol>
				<p>Alle velden moeten ingevuld worden!</p>
				<li><label for="voornaam">Voornaam: </label>
				<input type="text" name="Voornaam" maxlength="40" required placeholder="Voornaam" value="<?php if (isset($trimmed['Voornaam'])) {
				echo $trimmed['Voornaam']; }?>"/></li>

				<li><label for="achternaam">Achternaam: </label>
				<input type="text" name="Achternaam" maxlength="40" required placeholder="Achternaam" value="<?php if (isset($trimmed['Achternaam'])) {
				echo $trimmed['Achternaam']; }?>"/></li>

				<li><label for="geslacht">Geslacht: </label>
				<input type="radio" name="Geslacht" value="M" checked required/>Man
				<input type="radio" name="Geslacht" value="V" required/>Vrouw</li>

				<li><label for="leeftijd">Leeftijd: </label>
				<input type="text" name="Leeftijd" maxlength="2" required value="<?php if (isset($trimmed['Leeftijd'])) {
				echo $trimmed['Leeftijd']; }?>"/></li>

				<li><label for="woonplaats">Woonplaats: </label>
				<input type="text" name="Woonplaats" maxlength="35" required value="<?php if (isset($trimmed['Woonplaats'])) {
				echo $trimmed['Woonplaats']; }?>"/></li>

				<li><label for="postcode">Postcode: </label>
				<input type="text" name="Postcode" maxlength="6" required value="<?php if (isset($trimmed['Postcode'])) {
				echo $trimmed['Postcode']; }?>"/></li>

				<li><label for="telefoonnr">Telefoonnr: </label>
				<input type="text" name="Telefoonnr" maxlength="10" required placeholder="bijv. 0165012345" value="<?php if (isset($trimmed['Telefoonnr'])) {
				echo $trimmed['Telefoonnr']; }?>"/></li>

				<li><label for="email">Email: </label>
				<input type="email" name="Email" maxlength="35" required placeholder="iemand@domein.com" value="<?php if (isset($trimmed['Email'])) {
				echo $trimmed['Email']; }?>"/></li>

				<li><label for="wachtwoord1">Wachtwoord: </label>
				<input type="password" name="Wachtwoord1" maxlength="40" required value="<?php if (isset($trimmed['Wachtwoord1'])) {
				echo $trimmed['Wachtwoord1']; }?>"/></li>

				<li><label for="wachtwoord2">Bevestig Wachtwoord: </label>
				<input type="password" name="Wachtwoord2" maxlength="40" required value="<?php if (isset($trimmed['Wachtwoord2'])) {
				echo $trimmed['Wachtwoord2']; }?>"/></li>

				<li><label for="studentennr">Studentennr: </label>
				<input type="text" name="Studentennr" maxlength="7" required value="<?php if (isset($trimmed['Studentennr'])) {
				echo $trimmed['Studentennr']; }?>"/></li>
			</ol>
		</fieldset>

		<fieldset>
			<button type="submit">Registreer!</button>
		</fieldset>
	</form>

<?php include ('includes/footer.html'); ?>