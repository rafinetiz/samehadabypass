<?php
require 'vendor/autoload.php';

$app = new rafinetiz\App;

$url = readline('Enter url: ');
if ($app->is_valid_url($url) === false) {
    throw new Exception('URL you entered its not samehada valid url');
}

echo 'Downloading webpage..' . PHP_EOL;
$webpage = $app->request_webpage($url);

echo 'Retreiving download table..' . PHP_EOL;
$eps_list = $app->get_download_table($webpage->getBody());

$format_list = array_keys($eps_list);
echo 'Available format is: ' . implode(' ', $format_list) . PHP_EOL;
$format = readline('Select format: ');
if(in_array($format, $format_list) == false) {
    echo "$format is not available\n";
    exit(1);
}

$tbl = new Console_Table();
$tbl->setHeaders(['ID','Quality']);
foreach($eps_list[$format] as $key => $eps) {
    $tbl->addRow([$key, $eps['quality']]);
}
echo $tbl->getTable();
$pilih = readline('Select quality: ');
if ( in_array($pilih, array_keys($eps_list[$format])) == false ) {
    echo 'not found' . PHP_EOL;
    exit();
}

$link = $eps_list[$format][$pilih]['link'];

echo PHP_EOL . 'Resolving download link' . PHP_EOL;

$step1 = $app->resolve_link($link);
$step2 = $app->resolve_link($step1);
echo $step2 . PHP_EOL;
$zippy = rafinetiz\Zippyshare::generate($step2);
system("wget --user-agent 'Mozilla/5.0 (Linux; Android 5.1.1; SM-T285) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.116 Safari/537.36' $zippy");
