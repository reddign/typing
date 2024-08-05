<?php
// get a sequence of (random) words from a DB table
// example usage: https://typing.etownmca.com/level/fetch.php?table=TABLENAME&words=20&lower=true

require '../libs/sql.php';

if (!(isset($_GET['table']) && isset($_GET['words']) && isset($_GET['lower']))) {
    http_response_code(400); // malformed/missing GET paramaters
    exit();
}

// escape strings to mitigate SQL injection attacks
$table = $connection->escape_string($_GET['table']);
$word_count = intval($_GET['words'], 10);
$lower = (bool) filter_var($_GET['lower'], FILTER_VALIDATE_BOOL);

if ($word_count < 1) {
    http_response_code(400);
    exit();
}

$words = $connection->query("SELECT word FROM $table ORDER BY RAND() LIMIT $word_count");

$response = "";
for ($i = 0; $i < sizeof($words); $i++) {
    $word = $words[$i]['word'];
    $response = $response . ($lower? strtolower($word) : $word) . " ";
}
echo rtrim($response, " ");
?>