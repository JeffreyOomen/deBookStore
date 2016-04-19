<?php
//error_reporting(E_ERROR | E_PARSE);
error_reporting(0);

$page_title = 'De Bookstore - Boeken bestellen ';
$active = 3;
include ('includes/header.html');
include ('includes/mysqli_connect.php');

echo '<div class="catalogus">
	<ul>
		<li><a href="?cat=Informatica">Informatica</a></li>
		<li><a href="?cat=Werktuigbouwkunde">Werktuigbouwkunde</a></li>
		<li><a href="?cat=BIM">Business IT &amp; Management</a></li>
	</ul>
</div>';
// 
// Stap 1: maak verbinding met MySQL.
// Zorg ervoor dat MySQL (via XAMPP) gestart is.
//
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 
// check connection
if (mysqli_connect_errno()) {
	printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
	include ('includes/footer.html');
	exit();
}

// Opdracht: Maak de juiste SQL query die hier de informatie over onze producten gaat opleveren.
 if (isset($_GET['cat'])) {
 	$sql = "SELECT
`product`.`Product_ID`,  
`product`.`NaamPdf`, 
`product`.`PrijsPdf`, 
`product`.`DatumUitgave`,
`product`.`Beschrijving`, 
`product`.`Auteur`, 
`product`.`image`
FROM `product`
WHERE categorienaam ='" .$_GET['cat'] .'\'';

}
else if (isset($_GET['productid'])) {
 	$sql = "SELECT
`product`.`Product_ID`,  
`product`.`NaamPdf`, 
`product`.`PrijsPdf`, 
`product`.`DatumUitgave`,
`product`.`Beschrijving`,
`product`.`BeschrijvingLang`,
`product`.`Auteur`, 
`product`.`image`
FROM `product`
WHERE Product_ID ='" .$_GET['productid'] .'\'';

}

else{$sql = "SELECT
`product`.`Product_ID`,  
`product`.`NaamPdf`, 
`product`.`PrijsPdf`, 
`product`.`DatumUitgave`,
`product`.`Beschrijving`, 
`product`.`Auteur`, 
`product`.`image`
FROM `product`"; };

// Voer de query uit en sla het resultaat op 
$result = mysqli_query($conn, $sql);
	
if($result === false) {
	echo "<p>Er zijn geen boeken in de winkel gevonden.</p>\n";
} else {
	$num = mysqli_num_rows($result);
    echo "<p>Er zijn ".$num." boeken gevonden.</p>\n";
}

// Laat de producten zien in een form, zodat de gebruiker ze kan selecteren.
// Haal een nieuwe regel op uit het resultaat, zolang er nog regels beschikbaar zijn.
// We gebruiken in dit geval een associatief array.


while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
{

	echo "<div class=\"product\">\n<form action=\"add.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"Product_ID\" value=\"".$row["Product_ID"]."\" />\n";
	echo "<input type=\"hidden\" name=\"PrijsPdf\" value=\"".$row["PrijsPdf"]."\" />\n";
	echo '<img id=\'plaatje\' src="data:image/png;base64,'.base64_encode($row['image']).'">';
	echo "<div id=\"prijs\">€ ".number_format($row["PrijsPdf"], 2, ',', '.')."</div>\n";
	echo "<div id=\"prodnaam\">".$row["NaamPdf"]."</div>\n";
	echo "<div id=\"beschrijving\">".$row["Beschrijving"]."</div>\n";
	echo "<div id=\"leverbaar\">Auteur: ".$row["Auteur"]."</div>\n";
	echo "<div id=\"datum\">Datum van uitgave ".$row["DatumUitgave"]."\n</div>";
	echo "<div id=\"selecteer\">Licentieduur: <b>3 maanden</b></div>";
	echo "<button class=\"button2\"><a href=?productid=".$row["Product_ID"].">Bekijk product ></a></button>";
	echo "<button style=\"background-color: #6e9131;\" type=\"submit\" class=\"button\">Bestel!</button>\n";
	echo "</form>\n</div>\n";
	
	echo "<div id=\"beschrijvinglang\">".$row["BeschrijvingLang"]."</div>\n";

}

/* maak de resultset leeg */
mysqli_free_result($result);

/* sluit de connection */
mysqli_close($conn);

include ('includes/footer.html');
?>