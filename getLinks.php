<?php

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

function getNewLinks(Client $client, array &$links, array &$new, $pageUrl)
{
    $crawler = $client->request("GET", $pageUrl);

    $crawler->filter('#sr_content .box h2 a')->each(function (Crawler $linkNode) use (&$links, &$new) {
        $link = $linkNode->attr('href');

        if (in_array($link, $links)) {
            return;
        }

        $new[] = $link;
        $links[] = $link;
    });

    $nextLinkNode = $crawler->filter('.next_page a');

    if ($nextLinkNode->count() === 1) {
        $nextLink = $nextLinkNode->attr('href');
        printf('.');
        getNewLinks($client, $links, $new, $nextLink);
    }
}