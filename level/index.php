<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Level Selection</title>
</head>
<body>
    <!-- TODO: add navbar -->

<?php
// map level name to DB table name
$levels = [
    'Home Row Only' => 'homerow_keys',
    'Top Row Only' => 'toprow_keys',
    'Bottom Row Only' => 'bottomrow_keys',
    'All Letters' => 'letter_keys',
];

// create list of levels
// TODO: create level sections whre similar levels are grouped together
echo "<ul>";
foreach ($levels as $name => $table) {
    echo "<li><a href=../test/?level=$table>$name</a></li>";
}
echo "</ul>";
?>

</body>
</html>