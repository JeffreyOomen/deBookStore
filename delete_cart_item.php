<?php
// delete_cart_item.php
// item uit winkelwagen verwijderen
session_start();

// Variables
$item = $_GET['item'];

// Wederom, kijken of winkelwagen bestaat
if (empty($_SESSION['cart']))
{
    // Winkelwagen leeg, gan naar startscherm :)
    header("Location: index.php");
} else {
    // Winkelwagen uit elkaar plukken
    $cart2 = explode("|",$_SESSION['cart']);

    // Tellen aantal items in winkelwagen
    $count = count($cart2);

    $newCartString = "";

    foreach($cart2 as $products) {
        
        $product = explode(",",$products);

        if ($product[0] != $item) {
            $newCartString .= $products."|";
        }
    }

    // Er staat nog een | vooraan, even weghalen (had natuurlijk ook eerder
    // een controle kunnen doen en die daar niet plaatsen
    $newCartString = substr($newCartString, 0,-1);

}

// Verwijder de 'oude' winkelwagen en bouw een nieuwe
$_SESSION['cart'] = $newCartString;

// En terugsturen
header("Location: cart.php");
?> 