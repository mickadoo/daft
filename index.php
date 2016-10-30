<?php

require_once __DIR__ . "/vendor/autoload.php";

const MAX_WORK_DISTANCE_METERS = 5000;
const MIN_PRICE = 450;
const MAX_PRICE = 900;

const HOST = "http://www.daft.ie/";
const WORK_LATITUDE = 51.900865;
const WORK_LONGITUDE = -8.463574;

require_once 'getLinks.php';
require_once 'checkNew.php';
require_once 'sendMail.php';

$links = [];
$fiveMinutes = 300;
$client = new \Goutte\Client();
$path = sprintf(
    "/cork/residential-property-for-rent/" .
    "cork-city-centre,cork-city-suburbs,cork-commuter-towns/" .
    "?s[mnp]=%d&s[mxp]=%d" .
    "&s[advanced]=1&s[pt_id][0]=1&s[pt_id][1]=2&searchSource=rental",
    MIN_PRICE,
    MAX_PRICE
);
$searchUrl = HOST . $path;

while (true) {
    $new = [];
    printf("%sfetching links.", PHP_EOL);
    getNewLinks($client, $links, $new, $searchUrl);
    printf("%sfound %d new links%s", PHP_EOL, count($new), PHP_EOL);
    printf("filtering for distance.");
    filterNew($client, $new);
    printf("%sfound %d new within %d m of work", PHP_EOL, count($new), MAX_WORK_DISTANCE_METERS);
    if (!empty($new)) {
        sendMail($new);
    }
    printf("%sgoing to sleep!%s", PHP_EOL, PHP_EOL);
    sleep($fiveMinutes);
}