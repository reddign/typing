<?php
session_start();

$username = $_POST["username"];
$password = $_POST["password"];
$LD = $_POST["LD"];

$sql = "SELECT * FROM users 
WHERE username='{$username}' and password=MD5('{$password}') and verified=1;";

$mysqli = new mysqli("195.35.59.14","u121755072_typing","EdV@7~4B>c","u121755072_typingdb");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$result = $mysqli -> query($sql);
$rows = $result -> fetch_all(MYSQLI_ASSOC);

if(count($rows)>0) {

    $_SESSION["LoggedIn"]="YES";
    $_SESSION["UserID"]=$_POST["username"];
    header("location:HomePage.html");


}else{

    $_SESSION["LoggedIn"]="NO";
    $_SESSION["UserID"]="";
    header("location:index.html");
}
?>