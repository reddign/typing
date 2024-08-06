<?php
// publish a user's scores to the database and return their rankings
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

$uidreq = $connection->query("SELECT id FROM users WHERE username = " . $_SESSION['UserID']);
if (!$uidreq || !is_array($uidreq)) {
    // session UserID doesn't correspond to a real user
    http_response_code(409);
    exit();
}
$uid = intval($uidreq[0]['id']);
// add score to the database
$connection->query("INSERT INTO scores (userid, wpm, accuracy) VALUES ($uid, $wpm, $accuracy)");

// return user's rankings
$personal_scores = $connection->query("SELECT DISTINCT wpm FROM scores WHERE userid = $uid ORDER BY wpm DESC");
$global_scores = $connection->query("SELECT DISTINCT wpm FROM scores ORDER BY wpm DESC");
$p_rank = -1;
$g_rank = -1;
for ($i = 0; $i < sizeof($personal_scores); $i++) {
    if ($personal_scores[$i]['wpm'] == strval($wpm)) {
        $p_rank = $i + 1;
        break;
    }
}
for ($i = 0; $i < sizeof($global_scores); $i++) {
    if ($personal_scores[$i]['wpm'] == strval($wpm)) {
        $g_rank = $i + 1;
        break;
    }
}

echo json_encode(['personal' => $p_rank, 'global' => $g_rank]);
?>