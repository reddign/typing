<?php

$email = $_POST["email"];
$username = $_POST["username"];
$password = $_POST["pass"];
$LD = $_POST["LD"];

$sql = "INSERT INTO users
(email,username,password,LD)
Values
('{$email}','{$username}','{$password}','{$LD}')";


$mysqli = new mysqli("195.35.59.14","u121755072_typing","EdV@7~4B>c","u121755072_typingdb");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$stmt = $mysqli->prepare($sql);
$stmt->execute();

header("location:index.html");
?>