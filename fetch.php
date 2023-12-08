<?php
// fetch content from google sheets:
// https://docs.google.com/spreadsheets/d/1Pt6VS4sVkgVdFl23WRNHtcjNDU6Gpuybme2fTQ38WdU/edit#gid=1001060475

$gids = array(
        "Art" => "296735077",
        "Music" => "4719631",
        "Math" => "791155157",
        "Health" => "873386545",
        "PhysEd" => "1107520403",
        "Lang" => "972322472",
        "Science" => "271009271",
        "Social" => "1001060475",
);
$sheetId = '1Pt6VS4sVkgVdFl23WRNHtcjNDU6Gpuybme2fTQ38WdU';

foreach ($gids as $filename => $gid) {
    $sheetUrl = 'https://docs.google.com/spreadsheets/d/' . $sheetId . '/export?format=csv&id=' . $sheetId . '&gid=' . $gid;
    $csv = file_get_contents($sheetUrl);
    print ('Fetching ' . $filename . PHP_EOL);
    $file = "./csv/" . $filename . ".csv";
    file_put_contents($file, $csv);
}
