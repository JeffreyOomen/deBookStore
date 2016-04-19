<?php
//error_reporting(E_ERROR | E_PARSE);
error_reporting(0);

$page_title = 'Winkelwagen';
include ('includes/header.html');
include ('includes/mysqli_connect.php');

// Page header:
echo '<h1>Winkelwagen</h1>';
session_start();
// cart.php
// winkelwagen met bijbehorende functionaliteit

// Kijk of er iets in de winkelwagen zit
if (empty($_SESSION['cart'])) {
    echo "<p>Winkelwagen is leeg, <a href=\"ebooks.php\">klik hier</a> om verder te winkelen.</p>\n";
    echo "<div class=\"hidden\"></div>";
    include ("includes/footer.html");
    die();
}else {
    // Exploden
    $cart = explode("|",$_SESSION['cart']);

    // Tellen inhoud winkelwagen
    $count = count($cart);
    if ($count == 1) {
        echo "<p>Er staat 1 product in je winkelwagen.</p>\n";
    } else {
        echo "<p>Er staan ".$count." producten in je winkelwagen</p>\n";
    }

    // Wat javascriptjes voor het weghalen van producten
    // En daarna het begin van een tabel met de inhoud
    ?>
    <script type="text/javascript">
    <!--
    function removeItem(item) {
        var answer = confirm ('Weet je zeker dat je dit product wilt verwijderen?')
        if (answer)
            window.location="delete_cart_item.php?item=" + item;
    }

    function removeCart() {
        var answer = confirm ('Weet je zeker dat je de winkelwagen wilt leeghalen?')
        if (answer)
            window.location="delete_cart.php";
    }
    //-->
    </script>

    <div class="cart">
    <form method="post" name="form" action="update_cart.php">
    <table>
    <thead>
    <tr>
       
        <th>Productnaam</th>
        <th>Prijs</th>
        <th>Totaal</th>
        <th>Verwijder</th>

    </tr>
    </thead>
    </div>
    <?php

    // Totaal (komt later terug)
    $total = 0;
    
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

    // Toon de producten in de winkelwagen
    $i = 0;
    foreach($cart as $products) {
      // Splits het product in stukjes: $product[x] --> x == 0 -> product id, x == 1 -> hoeveelheid
      $product = explode(",", $products);

      if (strlen(trim($product[1])) <> 0) {
          // Get product info
          $sql = "SELECT Product_ID, NaamPdf, PrijsPdf 
                  FROM product
                  WHERE Product_ID = ".$product[0];  // Weet je nog, uit die sessie
                  
          $result = mysqli_query($conn, $sql) or die (mysqli_error($conn)."<br>in file ".__FILE__." on line ".__LINE__);
          $pro_cart = mysqli_fetch_object($result);
          $i++;
 
          echo "<tbody>\n<tr>\n";
          echo "<td>".$pro_cart->NaamPdf."</td>\n";     // naam
          echo "<td>€ ".number_format($pro_cart->PrijsPdf, 2, ',', '.')."</td>\n";
          $lineprice = $product[1];    
          echo "  <td>€ ".number_format($lineprice, 2, ',', '')."</td>\n";
          echo "  <td><a href=\"javascript:removeItem(".$pro_cart->Product_ID.")\"><img src=\"images\delete.png\"/></a></td>\n"; // Verwijder, mooi plaatje van prullebak ofzo
          echo "</tr></tbody>";
          // Total
          $total = $total + $lineprice;
          $totalIncBtw = (($total * 0.21) + $total);       // Totaal updaten
          $_SESSION['total'] = $totalIncBtw;
      }
    }
  }
?>
    <tfoot>
    <tr>
        <td colspan="2"><strong>Totaal Exclusief BTW</strong></td>
        <td colspan="2"><strong><?php echo "€ ".number_format($total, 2, ',', '.'); ?></strong></td>
    </tr>
    <tr>
        <td colspan="2"><strong>Totaal inclusief BTW</strong></td>
        <td colspan="2"><strong><?php echo "€ ".number_format($totalIncBtw, 2, ',', '.'); ?></strong></td>
    </tr>
    </tfoot>
    </table>
    </form>
    <div class="cartlinks">
 
        <ul>
            <li><a href="javascript:removeCart()">Winkelwagen leegmaken</a><br /></li>
            <li><a href="ebooks.php">Verder winkelen</a></li>
       </ul>    
    

    
      <a href="psp.php"><button class="afrekenen">Afrekenen</button></a>

  </div>
  
    </div><!--Einde div content -->
</div> <!--Einde div wrapper-->
<?php

include ('includes/footer.html');

?> 
