<?php

//Get the users email
$email = $_GET["email"];


//Connect to the database
$mysqli = new mysqli("195.35.59.14","u121755072_typing","EdV@7~4B>c","u121755072_typingdb");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "SELECT * FROM users 
WHERE email='".$email."' ;";


$result = $mysqli -> query($sql);
$rows = $result -> fetch_all(MYSQLI_ASSOC);

if(count($rows)==0){
    echo "Sorry, you havn't registered yet.";
    echo "<a href='Segister.htm'>Register</a>";
    exit;
}


//Set verified=1 where the email="?"
$sql = "UPDATE users set verified=1
WHERE email='".$email."' ;";

$stmt = $mysqli->prepare($sql);
$stmt->execute();

?>