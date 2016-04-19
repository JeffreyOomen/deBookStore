<?php
include ('includes/header.html');

	//Check if the user is logged in: 
	if (!isset($_SESSION['Klant_ID'])) {
		header("Location: login.php");
		die();
	}else{ 
		echo "<h1>Verander je accountgegevens: </h1>";
		require ('includes/mysqli_connect.php'); 

		// Check if the form has been submitted:
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		// Check for a first name:
		if (empty($_POST['Voornaam'])) {
			$errors[] = 'You forgot to enter your first name.';
		} else {
			$fn = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Voornaam'])));
		}
		
		// Check for a last name:
		if (empty($_POST['Achternaam'])) {
			$errors[] = 'You forgot to enter your last name.';
		} else {
			$ln = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Achternaam'])));
		}

		// Check for an age:
		if (empty($_POST['Leeftijd'])) {
			$errors[] = 'You forgot to enter your age.';
		} else {
			if (is_numeric($_POST['Leeftijd'])) {
			$l = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Leeftijd'])));
			}else{
				echo "Please enter a number!";
			}
		}

		// Check for a gender:
		if (empty($_POST['Geslacht'])) {
			$errors[] = 'You forgot to enter your gender.';
		} else {
			$g = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Geslacht'])));
		}

		// Check for a living place:
		if (empty($_POST['Woonplaats'])) {
			$errors[] = 'You forgot to enter your residence.';
		} else {
			$p = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Woonplaats'])));
		}

		// Check for a zip code:
		if (empty($_POST['Postcode'])) {
			$errors[] = 'You forgot to enter your zip code.';
		} else {
			$pc = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Postcode'])));
		}

		// Check for a Telephone number:
		if (empty($_POST['Telefoon'])) {
			$errors[] = 'You forgot to enter your Telephone number.';
		} else {
			if (is_numeric($_POST['Telefoon'])){
			$t = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Telefoon'])));
			}else{
				echo "Please enter a number!";
			}
		}

		// Check for an email address:
		if (empty($_POST['Email'])) {
			$errors[] = 'You forgot to enter your email address.';
		} else {
			$e = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Email'])));
		}

		// Check for a Student number:
		if (empty($_POST['Studentennr'])) {
			$errors[] = 'You forgot to enter your Student number.';
		} else {
			if (is_numeric($_POST['Studentennr'])) {
			$sn = mysqli_real_escape_string($dbc, strip_tags(trim($_POST['Studentennr'])));
			}else{
				echo "Please enter a number!";
			}
		}
		
		if (empty($errors)) { // If everything's OK.

		$klantnr = $_SESSION['Klant_ID'];

	$sql = "SELECT `Voornaam`, `Achternaam`, `Leeftijd`, `Geslacht`,  `Woonplaats`, `Postcode`, `TelefoonNr`, `Email`, `StudentenNr` FROM `klant` WHERE `Klant_ID`='".$klantnr."'";
		$r = mysqli_query($dbc, $sql);
		if (mysqli_num_rows($r) == 1) {

			// Make the query:
			$q = "UPDATE `klant` SET `Voornaam`='$fn', `Achternaam`='$ln', `Leeftijd`='$l', `Geslacht`='$g', `Woonplaats`='$p', `Postcode`='$pc', `TelefoonNr`='$t', `Email`='$e', `Studentennr`='$sn' WHERE `Klant_ID`='".$klantnr."'LIMIT 1";
			$r = @mysqli_query ($dbc, $q);
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				// Print a message:
				echo '<p>The user has been edited.</p>';	
				
			} else { // If it did not run OK.
				echo '<p class="error">The user could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				//echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
			}
				
		} else { // Already registered.
			echo '<p class="error">The email address has already been registered.</p>';
		}
		
	} else { // Report the errors.

		echo '<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p>';
	
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q = "SELECT `Voornaam`, `Achternaam`, `Leeftijd`, `Geslacht`, `Woonplaats`, `Postcode`, `TelefoonNr`, `email`, `StudentenNr` FROM `klant` WHERE `Klant_ID`='".$_SESSION['Klant_ID']."'";		
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// Create the form:
	echo '<div class="editform"><form action="edit_account.php" method="post">
<p>Voornaam: <input type="text" name="Voornaam" size="15" maxlength="40" value="' . $row[0] . '" /></p>
<p>Achternaam: <input type="text" name="Achternaam" size="15" maxlength="40" value="' . $row[1] . '" /></p>
<p>Leeftijd: <input type="text" name="Leeftijd" size="20" maxlength="2" value="' . $row[2] . '"  /> </p>
<p>Geslacht: <input type="text" name="Geslacht" size="20" maxlength="1" value="' . $row[3] . '"  /> </p>
<p>Woonplaats: <input type="text" name="Woonplaats" size="20" maxlength="35" value="' . $row[4] . '"  /> </p>
<p>Postcode: <input type="text" name="Postcode" size="20" maxlength="6" value="' . $row[5] . '"  /> </p>
<p>Telefoon nummer: <input type="text" name="Telefoon" size="20" maxlength="10" value="' . $row[6] . '"  /> </p>
<p>Email Adres: <input type="text" name="Email" size="20" maxlength="35" value="' . $row[7] . '"  /> </p>
<p>Studenten nummer: <input type="text" name="Studentennr" size="20" maxlength="8" value="' . $row[8] . '"  /> </p>
<div class=\"edituserbutton\"><button>Verander je account details</button></div>
</form></div>';

} else { // Not a valid user ID.
	echo '<p class="error">This page has been accessed in error.</p>';
}

mysqli_close($dbc);
		
include ('includes/footer.html');
}//end first else statement

?>