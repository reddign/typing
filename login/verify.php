<?php

//Get the users email
$email = $_GET["email"];
require '../libs/sql.php';

//Connect to the database
$query = $connection->query("SELECT * FROM users WHERE username='{$username}' and password=MD5('{$password}') and verified=1");
if (!$query) {
    die("Failed to connect to MySQL database");
}

if (sizeof($query) == 0) {
    // invalid credentials, user doesn't exist
    header('location:index.html');
    exit();
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