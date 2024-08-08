<?php
$query = $connection->query("SELECT * FROM users WHERE username='{$username}' and password=MD5('{$password}')");
if (!$query) {
    die("Failed to connect to MySQL database");
}

if (sizeof($query) == 0) {
    // invalid credentials, user doesn't exist
    header('location:index.html');
    exit();
}

$LD=$row["LD"];

?>