<?php
session_start();
require '../libs/sql.php';

$username = $_POST["username"];
$password = $_POST["password"];
$L = $_POST["L"];
$D = $_POST["D"];


$query = $connection->query("SELECT * FROM users WHERE username='{$username}' and password=MD5('{$password}') and verified=1");
if (!$query) {
    die("Failed to connect to MySQL database");
}

if (sizeof($query) == 0) {
    // invalid credentials, user doesn't exist
    header('location:index.html');
    exit();
}

// user exists, proceed to signin
$_SESSION["LoggedIn"]="YES";
$_SESSION["UserID"]=$_POST["username"];
header("location:HomePage.html");
?>