<?php # Script 18.11 - change_password.php
// This page allows a logged-in user to change their password.
$page_title = 'De Bookstore - Verander wachtwoord';
$active = 7;
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['Klant_ID'])) {
	
	$url = 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require ('includes/mysqli_connect.php');
			
	// Check for a new password and match against the confirmed password:
	$p = FALSE;
	if (preg_match ('/^(\w){6,20}$/', $_POST['password1']) ) {
		if ($_POST['password1'] == $_POST['password2']) {
			$p = mysqli_real_escape_string ($dbc, $_POST['password1']);
		} else {
			echo '<p class="error">De twee wachtwoorden moeten hetzelfde zijn.</p>';
		}
	} 

	if ($p) { // If everything's OK.

		// Make the query:
		$q = "UPDATE klant SET wachtwoord=SHA1('$p') WHERE Klant_ID={$_SESSION['Klant_ID']} LIMIT 1";	
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Send an email, if desired.
			echo '<h3>Uw wachtwoord is veranderd.</h3>';
			mysqli_close($dbc); // Close the database connection.
			include ('includes/footer.html'); // Include the HTML footer.
			exit();
			
		} else { // If it did not run OK.
		
			echo '<p class="error">Uw wachtwoord is niet veranderd, zorg ervoor dat uw nieuwe wachtwoord anders is dan uw oude.</p>'; 

		}

	} else { // Failed the validation test.
		echo '<p class="error">Er is een fout opgetreden, U kunt het nogmaals proberen..</p>';		
	}
	
	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>

<h1>Verander je wachtwoord:</h1>
<p>Uw wachtwoord moet tussen de 6 en 20 tekens bevatten.</p>
<form action="change_password.php" method="post">
	<fieldset>
	<ol>
	<li><label for="wachtwoord">Nieuw achtwoord:</label>
	<input type="password" name="password1" size="20" maxlength="20" /></li>

	<li><label for="nieuw wachtwoord">Bevestig nieuw wachtwoord:</label>
	<input type="password" name="password2" size="20" maxlength="20" /></li>
	</ol>
	</fieldset>

	<fieldset>
	<button type="submit">Verander wachtwoord</button>
	</fieldset>
</form>
<?php include ('includes/footer.html'); ?>