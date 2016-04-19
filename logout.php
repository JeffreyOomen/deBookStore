<?php # Script 18.9 - logout.php
// This is the logout page for the site.
$page_title = 'Logout';
$active = 8;
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['Voornaam'])) {

	$url = 'index.php'; // Define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
	
} else { // Log out the user.
	$url = 'login.php';
	$_SESSION = array(); // Destroy the variables.
	session_destroy(); // Destroy the session itself.
	setcookie (session_name(), '', time()-3600); // Destroy the cookie.
	header("Location: $url");

}

// Print a customized message:
echo '<h3>U bent nu uitgelogd.</h3>';

include ('includes/footer.html');
?>