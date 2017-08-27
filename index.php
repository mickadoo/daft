<?php

require_once __DIR__ . "/vendor/autoload.php";

const MAX_DISTANCE_METERS = 5000;
const MIN_PRICE = 250;
const MAX_PRICE = 700;

const HOST = "http://www.daft.ie/";
const TARGET_LATITUDE = 51.894760;
const TARGET_LONGITUDE = -8.476485;

require_once 'getLinks.php';
require_once 'checkNew.php';
require_once 'sendMail.php';

if (empty(getenv('GMAIL_PASSWORD'))) {
    throw new \Exception('ENV variable GMAIL_PASSWORD must be set');
}

$links = [];
$fiveMinutes = 300;
$client = new \Goutte\Client();

$path = sprintf(
    "cork-city/residential-property-for-rent/" .
    "cork-city-centre,cork-city-suburbs,cork-commuter-towns/" .
    "?s[mnp]=%d&s[mxp]=%d",
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
    printf("%sfound %d new within %d m of target", PHP_EOL, count($new), MAX_DISTANCE_METERS);
    if (!empty($new)) {
        sendMail($new);
    }
    printf("%sgoing to sleep!%s", PHP_EOL, PHP_EOL);
    sleep($fiveMinutes);
}
