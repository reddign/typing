<?php

//$to = "OSFryberger@gmail.com";
$subject = "Register your account";
$message = "Click here to register your email with our site.
    <BR>
    <a href='https://127.0.0.1/typing/login/verify.php?email={$to}'>Register</a>";
$headers = 'From: webmaster@etownmca.com' . "\r\n" .
    'Reply-To: webmaster@etownmca.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($to,$subject,$message,$headers);

?>