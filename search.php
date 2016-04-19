<?php 
$page_title = 'De Bookstore - Zoeken';
$active = 3;
include ('includes/header.html');
?>

<?php 
	  if(isset($_POST['submit'])){ 
	  if(isset($_GET['go'])){ 
	  if(preg_match("/^[  a-zA-Z]+/", $_POST['name'])){ 
	  $name=$_POST['name']; 
	  //connect  to the database 
	  $db=mysql_connect  ("localhost", "root",  "") or die ('I cannot connect to the database  because: ' . mysql_error()); 
	  //-select  the database to use 
	  $mydb=mysql_select_db("debookstore"); 
	  //-query  the database table 
	  $sql="SELECT  Product_ID, NaamPdf, PrijsPdf, DatumUitgave, Beschrijving, Auteur, image FROM product WHERE NaamPdf LIKE '%".$name."%'"; 
	  //-run  the query against the mysql query function 
	  $result=mysql_query($sql); 
	  //-create  while loop and loop through result set
	  echo "<h1>Zoekresultaten</h1>";

	  if (mysql_num_rows($result) == false) {
	  	echo "<p>Er zijn geen resultaten gevonden</p>";
	  }else if (mysql_num_rows($result) == 1){
	  	echo "<p>Er is 1 resultaat gevonden</p>";
	  }else{
	  	$num = mysql_num_rows($result);
	  	echo "<p>Er zijn ".$num." zoekresultaten gevonden</p>";
	  }

	  while($row=mysql_fetch_array($result)){ 
	          $Search = $row['NaamPdf'];  
	  //-display the result of the array 
 
    echo "<div class=\"product\">\n<form action=\"add.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"Product_ID\" value=\"".$row["Product_ID"]."\" />\n";
	echo "<input type=\"hidden\" name=\"PrijsPdf\" value=\"".$row["PrijsPdf"]."\" />\n";
	echo '<img id=\'plaatje\' src="data:image/png;base64,'.base64_encode($row['image']).'">';
	echo "<div id=\"prijs\">€ ".number_format($row["PrijsPdf"], 2, ',', '.')."</div>\n";
	echo "<div id=\"prodnaam\">".$row["NaamPdf"]."</div>\n";
	echo "<div id=\"beschrijving\">".$row["Beschrijving"]."</div>\n";
	echo "<div id=\"leverbaar\">Auteur: ".$row["Auteur"]."</div>\n";
	echo "<div id=\"datum\">Datum van uitgave ".$row["DatumUitgave"]."\n</div>";
	echo "<div id=\"selecteer\">Aantal: <input type=\"text\" name=\"hoeveelheid\" size=\"2\" maxlength=\"1\" value=\"1\" disabled/></div>";
	echo "<button class=\"button2\"><a href=ebooks.php?productid=".$row["Product_ID"].">Bekijk product ></a></button>";
	echo "<button type=\"submit\" class=\"button\">Bestel!</button>\n";
	echo "</form>\n</div>\n";
          

	 } 
	} 
	 else{ 
	  echo  "<p>Vult u alstublieft een zoekopdracht in</p>"; 
    } 
  } 
} 
	?>
	

<?php include ('includes/footer.html'); ?>