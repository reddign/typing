<?php
// publish a user's scores to the database
// takes GET arguments: wpm, accuracy
// TODO: add heuristics to ensure submitted scores are valid

require '../libs/sql.php';
session_start();

if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] == 'NO') {
    http_response_code(401); // unauthorized
    exit();
}

if (!(isset($_GET['wpm']) && isset($_GET['accuracy']))) {
    http_response_code(400); // bad request
    exit();
}

$wpm = intval($_GET['wpm']);
$accuracy = intval($_GET['accuracy']);
if ($wpm < 0 || strlen($_GET['wpm']) > 4 || strlen($_GET['accuracy']) > 3 || $accuracy < 0) {
    http_response_code(400);
    exit();
}

$uidreq = $connection->query("SELECT id FROM users WHERE username = '" . $_SESSION['UserID'] . "'");
if (!$uidreq || !is_array($uidreq)) {
    // session UserID doesn't correspond to a real user
    http_response_code(409);
    exit();
}
$uid = intval($uidreq[0]['id']);
// add score to the database
$connection->query("INSERT INTO scores (userid, wpm, accuracy) VALUES ($uid, $wpm, $accuracy)");
?>