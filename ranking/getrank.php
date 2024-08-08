<?php
// takes 2 GET paramaters: type (wpm or accuracy) and score
// responds with json encoding of global and personal rankings for that score
// user must be logged in to check their scores

require '../libs/sql.php';
require 'rank.php';
session_start();

if (!(isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] == 'YES')) {
    http_response_code(401);
    exit();
}

if (!(isset($_GET['type']) && isset($_GET['score']))) {
    http_response_code(400);
    exit();
}

$type = RankType::tryFrom($_GET['type']);
$score = strval($_GET['score']);
if ($type == null) {
    http_response_code(400);
    exit();
}

$id = $connection->query("SELECT id FROM users WHERE username = '" . $_SESSION['UserID'] . "'");
if (sizeof($id) == 0) {
    http_response_code(409);
    exit();
}

echo json_encode(get_rankings($connection, $id[0]['id'], $type, $score));
?>