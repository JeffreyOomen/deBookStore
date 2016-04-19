<?php
// This is the contact page for the site.
$page_title = 'De Bookstore - Contact';
$active = 6;
include ('includes/header.html');

if(isset($_POST['email'])) {
 
    // EDIT THE 2 LINES BELOW AS REQUIRED
 
    $email_to = "info@debookstore.nl";
 
    $email_subject = "Contact formulier";
 
    function died($error) {
        
        echo "<script type=\"text/javascript\">window.alert('$error');window.location.href = 'contact.php';</script>"; 
 
        die();
 
    }
 
    // validation expected data exists
 
    if(!isset($_POST['first_name']) ||
 
        !isset($_POST['email']) ||
 
        !isset($_POST['telephone']) ||
 
        !isset($_POST['comments'])) {
 
        died('Sorry maar er is een fout in uw formulier gevonden.');       
 
    }
 
     
 
    $first_name = $_POST['first_name']; // required
 
    $email_from = $_POST['email']; // required
 
    $telephone = $_POST['telephone']; // not required
 
    $comments = $_POST['comments']; // required
 
     
 
    $error_message = "";
 
    $string_exp = "/^[A-Za-z .'-]+$/";
 
  if(!preg_match($string_exp,$first_name)) {
 
    $error_message .= 'Vul uw voornaam correct in.\n';
 
  }
  
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
 
  if(!preg_match($email_exp,$email_from)) {
 
    $error_message .= 'Vul uw e-mail correct in.\n';
 
  }  
 
     $string_exp = "/^[0-9]+$/";
 
  if(!preg_match($string_exp,$telephone)) {
 
    $error_message .= 'Vul uw telefoonnummer correct in.\n';
 
  }
 
  if(strlen($comments) < 2) {
 
    $error_message .= 'Vul uw opmerking correct in.\n';
 
  }
 
  if(strlen($error_message) > 0) {
 
    died($error_message);
 
  }
 
    $email_message = "Opmerkingsformulier:\n\n";
 
     
 
    function clean_string($string) {
 
      $bad = array("content-type","bcc:","to:","cc:","href");
 
      return str_replace($bad,"",$string);
 
    }
 
     
 
    $email_message .= "Naam: ".clean_string($first_name)."\n";
 
    $email_message .= "Email: ".clean_string($email_from)."\n";
 
    $email_message .= "Telefoon: ".clean_string($telephone)."\n";
 
    $email_message .= "Opmerking: ".clean_string($comments)."\n";
 
     
 
     
 
// create email headers
 
$headers = 'From: '.$email_from."\r\n".
 
'Reply-To: '.$email_from."\r\n" .
 
'X-Mailer: PHP/' . phpversion();
 
@mail($email_to, $email_subject, $email_message, $headers);

	mysqli_close($dbc);
	
	header('Location: bedankt.php');

} // End of the main Submit conditional.

?>
  <h1>Contact: </h1>
      <p>Als U op- of aanmerkingen heeft over de website of vragen over producten kunt U contact met ons nemen door het onderstaande formulier in te vullen. Wij zullen U zo spoedig mogelijk antwoorden.</p>
	
    <div class="contact_form">
    <form action="index.html" method="post">
        <div class="contactrow">
            <label for="">Naam</label>
            <input  type="text" placeholder="Naam *" name="first_name" maxlength="50" size="30">
        </div>
        <div class="contactrow">
            <label for="E-">E-mailadres</label>
            <input  type="text" placeholder="E-mailadres *" name="email" maxlength="80" size="30">
        </div>
        <div class="contactrow">
            <label for="">Telefoonnummer</label>
            <input  type="text" placeholder="Telefoonnummer *" name="telephone" maxlength="30" size="30">
        </div>
        <div class="contactrow">
            <label for="">Opmerking</label>
            <textarea  name="comments" placeholder="Opmerking *" maxlength="1000" cols="25" rows="6"></textarea>
        </div>
        <div class="contactrow">
            <button type="submit">Verzend mail</button>
        </div>
    </form>
    </div>

<?php include ('includes/footer.html'); ?>