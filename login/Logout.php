<?php
session_start();
$_SESSION["LoggedIn"]="NO";
session_destroy();
header("location:index.htm");
?>