<?php

require '../libs/sql.php';
$email = $connection->escape_string($_POST["email"]);
$username = $connection->escape_string($_POST["username"]);
$password = $connection->escape_string($_POST["password"]);
$LD = $connection->escape_string($_POST["LD"]);

$connection->query("INSERT INTO users (email,username,password,LD) VALUES ('{$email}','{$username}','{$password}','{$LD}')");
if ($connection) {
    header("location:index.html");
} else {
    die("Couldn't add user. Please try again");
}

$to = $email;
require("email.php?email={$email}");

header("location:HomePage.html")
?>