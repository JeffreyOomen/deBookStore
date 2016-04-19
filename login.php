<?php # Script 18.8 - login.php
// This is the login page for the site.

$page_title = 'De Bookstore - Inloggen';
$active = 10;
include ('includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require ("includes/mysqli_connect.php");
	
	// Validate the email address:
	if (!empty($_POST['Email'])) {
		$e = mysqli_real_escape_string ($dbc, $_POST['Email']);
	} else {
		$e = FALSE;
		echo '<p class="error">Vul uw e-mail adres in.</p>';
	}
	
	// Validate the password:
	if (!empty($_POST['Wachtwoord'])) {
		$p = mysqli_real_escape_string ($dbc, $_POST['Wachtwoord']);
	} else {
		$p = FALSE;
		echo '<p class="error">Vul uw wachtwoord in.</p>';
	}
	
	if ($e && $p) { // If everything's OK.

		// Query the database:
		$q = "SELECT Klant_ID, Voornaam, Achternaam FROM klant WHERE Email='$e' AND Wachtwoord=SHA1('$p');";		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 1) { // A match was made.

			// Register the values:
			$_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC); 
			mysqli_free_result($r);
			mysqli_close($dbc);
							
			// Redirect the user:
			$url = 'index.php'; // Define the URL.
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
				
		} else { // No match was made.
			echo '<p class="error">Er is geen combinatie gevonden van dit e-mail adres en wachtwoord.</p>';
		}
		
	} else { // If everything wasn't OK.
		echo '<p class="error">Probeer nog een keer alstublieft.</p>';
	}
	
	mysqli_close($dbc);

} // End of SUBMIT conditional.
?>

<h1>Login</h1>
<p>Vul je Email adres en wachtwoord in:</p>
<form action="login.php" method="POST">
	<fieldset>

	<ol>
	<li><label for="email">Email adres:</label>
	<input type="email" name="Email" size="20" maxlength="60" value="<?php if (isset($e)) {
		echo "$e"; } ?>"/></li>

	<li><label for="wachtwoord">Wachtwoord:</label>
	<input type="password" name="Wachtwoord" size="20" maxlength="20" /></li>

	<li><button type="submit">Log in!</li>

	</ol>
	</fieldset>
</form>

<?php include ('includes/footer.html'); ?>