<?php

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

function filterNew(Client $client, array &$new)
{
    foreach ($new as $key => $link) {
        $crawler = $client->request('GET', $link);
        $crawler->filter('script')->each(function (Crawler $script) use (&$new, $key) {
            if (strpos($script->text(), 'MultiMaps') !== false) {
                $regex = '/latitude\":\"([-\d\.]*)\",\"longitude\":\"([-\d\.]*)\"/';
                preg_match($regex, $script->text(), $matches);
                if (count($matches) !== 3) {
                    unset($new[$key]);
                } else {
                    list($original, $latitude, $longitude) = $matches;
                    $distance = getDistanceToWork($latitude, $longitude);
                    if ($distance > MAX_DISTANCE_METERS) {
                        unset($new[$key]);
                    }
                }
            }
        });
        print('.');
    }
}

function getDistanceToWork($latitudeFrom, $longitudeFrom)
{
    $earthRadius = 6371000;
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad(TARGET_LATITUDE);
    $lonTo = deg2rad(TARGET_LONGITUDE);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
    return $angle * $earthRadius;
}
