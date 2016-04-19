<?php
//index.php
//startscherm van de webwinkel

$page_title = 'Welkom in de WebWinkel';
include ('includes/header.html');

// mysqli_connect.php bevat de inloggegevens voor de database.
// Per server is er een apart inlogbestand - localhost vs. remote server
include ('includes/mysqli_connect.php');

//
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
// check connection
if (mysqli_connect_errno()) {
	printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
	include ('includes/footer.html');
	exit();
}

echo "<h1>Mijn facturen</h1>\n";

if (!empty($_SESSION['Klant_ID'])) {
	$klantnr = $_SESSION['Klant_ID'];
} else {
	echo "<p>U moet eerst inloggen.</p>";
}

// Stap 1: maak verbinding met MySQL.
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno()) {
	printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
	include ('includes/footer.html');
	exit();
}

// Maak de SQL query die onze bestellingen gaat opleveren.

$sql = "SELECT DISTINCT `bestelling`.`Bestelling_ID`, `bestelling`.`DateTime`, `bestelling`.`Status`, `bestelling`.`TotaalPrijs`, `product`.`NaamPdf` 
FROM `bestelling`, `product`, `verkoop`
WHERE `Klant_ID` = $klantnr
AND `bestelling`.`Bestelling_ID` = `verkoop`.`Bestelling_ID`
AND `verkoop`.`Product_ID` = `product`.`Product_ID`";


// Voer de query uit en vang fouten op 
if( !$result = mysqli_query($conn, $sql)) {
	echo "<p>Geen bestellingen gevonden.</p>\n";
} else {
	if (mysqli_fetch_array($result) == false) {
		echo "Geen bestellingen gevonden<br /><br />";
	}else{
		echo "<div class=\"account\"><table><tr><td>Bestelling ID:</td><td>Besteldatum en tijd:</td><td>Status:</td><td>Totaalprijs:</td><td>Naam Product:</td></tr>";
	while($row = mysqli_fetch_array($result)) {
	
		echo "<tr><td>".$row["Bestelling_ID"]."</td><td>".$row["DateTime"]."</td><td>".$row["Status"]."</td><td>€ ".$row["TotaalPrijs"]."</td><td>".$row["NaamPdf"]."</td></tr>";
		}
	}
	echo "</table></div>";
}

/* maak de resultset leeg */
mysqli_free_result($result);

/* sluit de connection */
mysqli_close($conn);
?>

<div class="accountbutton"><a href="account.php"><button>Ga Terug</button></a></div>

<?php
include ('includes/footer.html');
?>