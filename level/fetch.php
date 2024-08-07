<?php
// get a sequence of (random) words from a DB table
// example usage: https://typing.etownmca.com/level/fetch.php?table=TABLENAME&words=20&lower=true

require 'levels.php';

if (!isset($_GET['level'])) {
    http_response_code(400);
    die("Missing level name in GET request");
}

Level::load_cached_levels();
$level = Level::get_level($_GET['level']);

if (!$level) {
    print_r($level);
    http_response_code(400);
    die("Invalid level name");
}

echo $level->get_test();
?>