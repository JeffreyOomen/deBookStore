<?php
//index.php
//startscherm van de webwinkel

$page_title = 'De Bookstore - Uw gegevens';
include ('includes/header.html');

// mysqli_connect.php bevat de inloggegevens voor de database.
// Per server is er een apart inlogbestand - localhost vs. remote server
include ('includes/mysqli_connect.php');

// Page header:
echo '<h1>Uw gegevens</h1>';

//
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// check connection
if (mysqli_connect_errno()) {
	printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
	include ('includes/footer.html');
	exit();
}

if (empty($_SESSION['Klant_ID'])) {
	echo "<p>U bent niet ingelogd.</p>\n";
}else {
	$klantnr = $_SESSION['Klant_ID'];

	$sql = "SELECT `Voornaam`, `Achternaam`, `Leeftijd`, `Geslacht`,  `Woonplaats`, `Postcode`, `TelefoonNr`, `Email`, `StudentenNr` FROM `klant` WHERE `Klant_ID`='".$klantnr."'";
	// Voer de query uit en sla het resultaat op 

	$result = mysqli_query($conn, $sql) or die (mysqli_error($conn)."<br>Error in file ".__FILE__." on line ".__LINE__);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	echo "<div class=\"account\"><table>\n";
	echo "<tr><td id='links'>Voornaam:</td> <td id='rechts'>".$row["Voornaam"]."</td></tr>\n";
	echo "<tr><td id='links'>Achternaam:</td> <td id='rechts'>".$row["Achternaam"]."</td></tr>\n";
	echo "<tr><td id='links'>Leeftijd:</td><td id='rechts'>".$row["Leeftijd"]."</td></tr>\n";
	echo "<tr><td id='links'>Geslacht:</td> <td id='rechts'>".$row["Geslacht"]."</td></tr>\n";
	echo "<tr><td id='links'>Woonplaats:</td><td id='rechts'>".$row["Woonplaats"]."</td></tr>\n";
	echo "<tr><td id='links'>Postcode:</td><td id='rechts'>".$row["Postcode"]."</td></tr>\n";
	echo "<tr><td id='links'>Telefoon:</td><td id='rechts'>(0)".$row["TelefoonNr"]."</td></tr>\n";
	echo "<tr><td id='links'>Email:</td><td id='rechts'>".$row['Email']."</td></tr>\n";
	echo "<tr><td id='links'>Studentnummer:</td> <td id='rechts'>".$row["StudentenNr"]."</td></tr>\n";
	echo "</table></div>\n";

}

// Sluit de connection
mysqli_close($conn);
?>

	<div class="accountbutton"><a href="edit_account.php"><button>Verander uw gegevens</button></a></div>
	<div class="accountbutton"><a href="change_password.php"><button>Verander uw wachtwoord</button></a></div>
	<div class="accountbutton"><a href="bestellingenbeheer.php"><button>Bekijk uw bestellingen</button></a></div>
<?php
include ('includes/footer.html');
?>