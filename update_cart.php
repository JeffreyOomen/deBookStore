<?php
// update_cart.php
session_start();

// Eenzijdig, maar kijken of die bestaat
if (empty($_SESSION['cart'])) {
    // Nee dus, terugsturen!
    header("Location: index.php");
} else {
    // Exploden
    $cart = explode("|",$_SESSION['cart']);

    // Tellen
    $count = count($cart);

    // Alle producten langslopen
    foreach($cart as $products) {
        // Split
        /*
        $product[x] -->
        x == 0 -> product id
        x == 1 -> hoeveelheid
        */
        $product = explode(",",$products);
        $i++;

        $postedProduct = "Product_ID".$i; // Deze twee om later de geposte waarde te 'spoofen'
  

        if ($product[0] == $_POST[$postedProduct]) // hier dus
        {
            // Update pro
            $inNewCart = $product[0].;
            $newCart = $newCart."|".$inNewCart;
        }
    }

    // En weer die luiheid, dus die eerste | eraf...
    $newCart = substr($newCart,1);

    // Oude winkelwagen weg, nieuwe terug
    session_unset($_SESSION['cart']);
    $_SESSION['cart'] = $newCart;

    // En weer terugsturen
    header("Location: cart.php");
}
?> 