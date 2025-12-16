<?php

$commonBirdsData = file_get_contents("./common-birds-kerala.json");

$commonBirdsArray = json_decode($commonBirdsData, true);

$cbHref = [];
foreach($commonBirdsArray as $cb) {
    $cbHref[] = $cb["href"];
}


$allBirdsData = file_get_contents("./all-birds.json");
$allBirdsDataArray = json_decode($allBirdsData, true);

$sahyadriBirds = [];
foreach($allBirdsDataArray as $bird) {
    if (!in_array($bird["href"], $cbHref)) {
        $sahyadriBirds[] = $bird;
    }
}

$sahyadriBirdsData = json_encode($sahyadriBirds, JSON_UNESCAPED_SLASHES);

print_r($sahyadriBirdsData);

file_put_contents("sahyadri-birds.json", $sahyadriBirdsData);