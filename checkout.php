<?php
$page_title = 'de WebWinkel';
include ('includes/header.html');


if (isset($_GET['psp'])) {	
// mysqli_connect.php bevat de inloggegevens voor de database.
// Per server is er een apart inlogbestand - localhost vs. remote server
include ('includes/mysqli_connect.php');

// Page header:
echo '<h1>Bestelling afronden</h1>';

if (empty($_SESSION['Klant_ID'])) {
    echo "<p>U ben nog niet ingelogd. <a href=\"login.php\">Log eerst in</a> om uw bestelling af te ronden.</p>\n";
    echo "Nog geen account? <a href=\"register.php\">Registreer hier!</a>";
} else {

	// Afsluiten van bestelling en bestelregel opslaan in database

	//connectie maken met database webwinkel
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	 
	// check connection
	if (mysqli_connect_errno()) {
		printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
		include ('includes/footer.html');
		exit();
	}

	$sql = "INSERT INTO bestelling (`Klant_ID`, `Betalingstype`, `DateTime`, `Status`, `TotaalPrijs`) VALUES ('".$_SESSION['Klant_ID']."','".$_GET['psp']."', NOW(), 'onbetaald','".$_SESSION['total']."');"; 
	$result = mysqli_query($conn, $sql) or die (mysqli_error($conn)."<br>in file ".__FILE__." on line ".__LINE__);

	$bestelnr = mysqli_insert_id($conn); // insert_id geeft de id terug van het autoincrement veld - het bestelnr dus.

	// Stap 2, winkelwagen splitsen en de producten in bestelregels in de database zetten
	$cart = explode("|",$_SESSION['cart']);

	foreach($cart as $products) {
		// Splits het product in stukjes: $product[x] --> x == 0 -> product id, x == 1 -> hoeveelheid
		$product = explode(",",$products);

		// Hier willen we productprijs toevoegen aan de productregel. De productprijs is de prijs van het 
		// product. Deze zit nog niet in de sessie, en moet daar dus bij het bestellen (bijvoorbeeld 
		// in index.php) in worden gezet.
		// We tellen hier ook het bedrag per product op (prijs x aantal) en tellen dit op bij de totaalprijs.
		// Je kunt in cart.php kijken hoe je dat kunt doen.
		$sql = "INSERT INTO verkoop (Bestelling_ID, Product_ID, Prijs ) VALUES
		(".$bestelnr.", ".$product[0].", ".$product[1].")";
		$result = mysqli_query($conn, $sql) or die (mysqli_error($conn)."<br>in file ".__FILE__." on line ".__LINE__);
	}

	// 
	// Op dit moment hebben we de totaalprijs berekend. Deze moeten we nu nog in een aparte
	// query in de bestelling zetten. Je hebt $bestelnr, dus voeg daar de totaalprijs aan toe.
	// 

		// Bericht naar de gebruiker.
	echo "<p>Uw bestelling is afgerond. U ontvangt een bevestigingsmail, dit kan enkele minuten duren.</p>";
	} 

	// Leeg de winkelwagen door deze uit de sessie te verwijderen.
	// De overige gegevens in de sessie blijven behouden.
	if(isset($_SESSION['cart']))
		unset($_SESSION['cart']);

	// Sluit de connection
	@mysqli_close($conn);

	include ('includes/footer.html');
};
?> 