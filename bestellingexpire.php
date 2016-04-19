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
	
if (!isset($_SESSION['Klant_ID'])) {
    echo "U moet eerst inloggen voordat U uw bestellingen kunt zien.";
    echo "<div class=\"hidden\"></div>";
    include ("includes/footer.html");
    die ();	
}else {
	$klantnr = $_SESSION['Klant_ID'];
	echo "<p>Als u een actieve licentie heeft voor een boek,
	kunt u die openen door op de link te klikken. Als er geen links <br/>
	staan, heeft u geen actieve licenties.</p>";
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
	while($row = mysqli_fetch_array($result)) {	

		//Make the time it is now:
		setlocale( LC_ALL, 'Dutch_Netherlands', 'Dutch');
		$now = strftime("%d-%m-%Y %H:%M:%S", time());
		$now = date("Y-m-d H:i:s");

		$ExplodeNowTimeDate = explode(" ", $now); //split time and date
		$ExplodeNowTimeDate[0]; //Here is the date
		$ExplodeNowTimeDate[1]; //Here is the time

		$ExplodeDateNow = explode("-", $ExplodeNowTimeDate[0]); //Explode the date in pieces
		$ExplodeTimeNow = explode(":", $ExplodeNowTimeDate[1]); //Explode the time in pieces

		$ExplodeDateNow[0]; //year
		$ExplodeDateNow[1]; //month
		$ExplodeDateNow[2]; //day

		$ExplodeTimeNow[0]; //hour
		$ExplodeTimeNow[1]; //minute
		$ExplodeTimeNow[2]; //second

		//Put in in an mktime: 
		$MktimeNow = mktime($ExplodeTimeNow[0], $ExplodeTimeNow[1], $ExplodeTimeNow[2], 
			$ExplodeDateNow[1], $ExplodeDateNow[2], $ExplodeDateNow[0]);

		//Set in format: 
		$FinalNowDateTime = date("Y-m-d H:i:s", $MktimeNow);
		$FinalNowDateTimeFormat = strtotime($now); 

		//=================================================================================================

		//This is the virtual date and time in the database
		$databaseTimeDate = $row['DateTime'];
		$expireDate = date("Y-m-d H:i:s",strtotime("+2 minutes", strtotime($databaseTimeDate)));
		
		$ExplodeDatabaseTimeDate = explode(" ", $expireDate); //split time and date
		$ExplodeDatabaseTimeDate[0]; // Here is the date
		$ExplodeDatabaseTimeDate[1]; // Here is the time

		$ExplodeDateExpireDestruction = explode("-", $ExplodeDatabaseTimeDate[0]); //Explode the date in pieces
		$ExplodeTimeExpireDestruction = explode(":", $ExplodeDatabaseTimeDate[1]); //Explode the time in pieces

		$ExplodeDateExpireDestruction[0]; //year
		$ExplodeDateExpireDestruction[1]; //month
		$ExplodeDateExpireDestruction[2]; //day 

		$ExplodeTimeExpireDestruction[0]; //hour
		$ExplodeTimeExpireDestruction[1]; //minute
		$ExplodeTimeExpireDestruction[2]; //second

		//Put in in an mktime: 
		$MktimeExpireDestruction = mktime($ExplodeTimeExpireDestruction[0], $ExplodeTimeExpireDestruction[1], $ExplodeTimeExpireDestruction[2],
			$ExplodeDateExpireDestruction[1], $ExplodeDateExpireDestruction[2], $ExplodeDateExpireDestruction[0]);

		//Set in format: 
		$FinalExpireDateTime = date("Y-m-d H:i:s", $MktimeExpireDestruction);
		$FinalExpireDateTimeFormat = strtotime($FinalExpireDateTime);

		//Diffrence
		$difference = $FinalExpireDateTimeFormat - $FinalNowDateTimeFormat;

		$seconds = $difference;
		$days    = floor($seconds / 86400);
		$hours   = floor(($seconds - ($days * 86400)) / 3600);
		$minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
		$seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));

		if ($now <= $expireDate) {
			echo "<div class=\"account\"><table><tr><td>Naam E-book:</td></tr>";
			echo "<tr><td><a href=books/".$row["NaamPdf"].".pdf target=_blank>".$row["NaamPdf"]."<br /></a>";

				if ($FinalExpireDateTimeFormat >= $FinalNowDateTimeFormat) {
					if ($minutes == 1) {
						echo "<p>Uw licentie is nog ".$minutes." minuut geldig</p>";
					}else if ($minutes <= 1) {
						echo "<p>Uw licentie is nog ".$seconds." seconden geldig</p>";
					}else{
						echo "<p>Uw licentie is nog ".$minutes." minuten geldig</p>";
					}
				}
				echo "</td></tr>";
				echo "<br />";
		}else if ($now > $expireDate){
			$expireDateNotification = date("Y-m-d H:i:s",strtotime("+1 minutes", strtotime($expireDate))); 

			if ($now <= $expireDateNotification) {
				echo "<tr><td>De licentie voor uw boek: ".$row['NaamPdf']." is verlopen.</td></tr>";
			}else if($now > $expireDateNotification) {
				echo "";
			}
		}	
}
	echo "</table></div>";
}

mysqli_free_result($result);

/* sluit de connection */
mysqli_close($conn);

include ('includes/footer.html');
?>