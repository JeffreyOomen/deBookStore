<?php
//index.php
//startscherm van de webwinkel

$page_title = 'Welkom in de WebWinkel';
$active = 5;
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

echo "<h1>Mijn bestellingen</h1>\n";
echo "<p>U kunt het boek openen door op de titel te klikken.</p>";

	
	
if (!isset($_SESSION['Klant_ID'])) {
    echo "U moet eerst inloggen voordat U uw bestellingen kunt zien.";
    echo "<div class=\"hidden\"></div>";
    include ("includes/footer.html");
    die ();	
}
	
else {
	$klantnr = $_SESSION['Klant_ID'];
}

// Stap 1: maak verbinding met MySQL.
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (mysqli_connect_errno()) {
	printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
	include ('includes/footer.html');
	exit();
}

 
// Maak de SQL query die onze bestellingen gaat opleveren.

$sql = "SELECT DISTINCT `verkoop`.`Product_ID`, `product`.`NaamPdf`, `bestelling`.`DateTime`
FROM `verkoop`,`bestelling`, `product`
WHERE `verkoop`.`Bestelling_ID` = `bestelling`.`Bestelling_ID`
AND `verkoop`.`Product_ID` = `product`.`Product_ID`
AND `bestelling`.`Klant_ID` = $klantnr
ORDER BY `bestelling`.`DateTime` DESC";

// Voer de query uit en vang fouten op 
if( !($result = mysqli_query($conn, $sql)) ) {
	echo "<p>Geen bestellingen gevonden.</p>\n";
} else {
	echo "<div class=\"account\"><table><tr><td>Naam E-book:</td></tr>";
	while($row = mysqli_fetch_array($result)) {	
		setlocale( LC_ALL, 'Dutch_Netherlands', 'Dutch');
		$now = strftime("%d-%m-%Y %H:%M:%S", time());
		$now = date("Y-m-d H:i:s");

		//ExpireDate is de tijd van de database + 3 maanden
		//Uur - Minuut - Seconde - Maand - Dag - Jaar
		$databaseTimeDate = $row['DateTime'];
		$expireDate = date("Y-m-d H:i:s",strtotime("+10 minutes", strtotime($databaseTimeDate)));
	
		if ($now <= $expireDate) {
			echo "<tr><td><a href=books/".$row["NaamPdf"].".pdf target=_blank>".$row["NaamPdf"]."</a></td></tr>";
		}else if ($now > $expireDate){
			echo "<tr><td>De licentie voor uw boek: ".$row['NaamPdf']." is verlopen</td></tr>";
		}
		
	}
	echo "</table></div>";
}

mysqli_free_result($result);

/* sluit de connection */
mysqli_close($conn);

include ('includes/footer.html');
?>