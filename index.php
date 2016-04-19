<?php
// Zet het niveau van foutmeldingen zo dat warnings niet getoond worden.
error_reporting(E_ERROR | E_PARSE);

$page_title = 'De BookStore - Home ';
$active = 1;
include ('includes/header.html');
include ('includes/mysqli_connect.php');

// Welcome the user (by name if they are logged in):
echo '<h1>Welkom';
if (isset($_SESSION['Voornaam'])) {
	echo ", {$_SESSION['Voornaam']}";
	echo " {$_SESSION['Achternaam']}";
}
echo '!</h1>';

$useragent = $_SERVER['HTTP_USER_AGENT'];
$userip = $_SERVER['REMOTE_ADDR'];
$userhost = gethostbyaddr($_SERVER["REMOTE_ADDR"]);

//connectie maken met database webwinkel
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	 
// check connection
if (mysqli_connect_errno()) {
	printf("<p><b>Fout: verbinding met de database mislukt.</b><br/>\n%s</p>\n", mysqli_connect_error());
	include ('includes/footer.html');
	exit();
}

?>
    <div class="info">
    <p>
		De BookStore is een online webwinkel die een nieuw concept aanbiedt, bedacht door studenten. 
		Het aanschaffen van (school)boeken is een dure investering. De meeste boeken gebruik je maar voor een korte 
		periode of slechts enkele keren. Na deze periode heb je de keuze om de boeken zelf te houden, waarna je ze naar 
		alle waarschijnlijkheid ook bijna nooit meer zult gebruiken of je kunt de boeken te koop aanbieden. Bij de Bookstore 
		is dit verleden tijd! De BookStore biedt namelijk boeken aan in de vorm van een E-book die te huur zijn voor een gekozen periode. 
		Na deze periode zijn de boeken niet meer beschikbaar en kunt u weer nieuwe boeken bestellen! 
    </p>
  	</div>

	<div class="kopje"><p>Voordelen</p></div>
	<div class="kopje"><p>Acties</p></div>
	<div class="kopje2"><p>Locatie</p></div>
	<div class="kolom">
	    <ul class="checkmark">
	        <li>Je betaalt niet meer het gehele aankoopbedrag.</li>
	        <li>Boeken zijn altijd op voorraad.</li>
	        <li>Geen wachttijd en geen verzendkosten.</li>
	        <li>Altijd de nieuwste uitgave van een boek.</li>
	        <li>Geen grote en zware boeken meer.</li>
	        <li>De boeken zijn op meerdere platvormen beschikbaar.</li>
	        <li>Makkelijk en veilig betalen met creditcard of iDEAL.</li>
	    </ul>
	   	<img src="images/ideal.PNG" alt="Betalen met iDeal" width="301px" />
	   	<img src="images/creditcardindex.jpg" alt="Betalen met creditcard" width="301px" />
	</div>
	<div class="kolom"><img src="images/actie.png" width="301px" height="350px"></div>
	<div class="kolom"><iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2479.0511784153155!2d4.7938126!3d51.58562539999999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c6a1d62a3c4c9d%3A0xf1e23d69eeb94bd4!2sLovensdijkstraat+63%2C+4818+AJ+Breda!5e0!3m2!1snl!2snl!4v1420818574566" width="301" height="350" frameborder="0" style="border:0"></iframe></div>

<?php
	include ("includes/footer.html");
?>