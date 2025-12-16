<?php

$htmlFiles = file_get_contents("./html-files.txt");

$htmlFilesArray = explode(" ", $htmlFiles);

$lastParts = [];

foreach($htmlFilesArray as $hf) {
    $hfParts = explode("/", $hf);
    $lastPart = end($hfParts);

    if(!str_contains($lastPart, ".")) {
        $lastParts[] = $lastPart;
    }
}

print_r($lastParts);

foreach($lastParts as $fName) {
    file_put_contents($fName . ".html", "");
}

