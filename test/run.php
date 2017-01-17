<?php

use Elasticsearch\ClientBuilder;

require __DIR__ . '/../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(['default:9200'])->build();

$placeIndexed = $client->index([
    "index" => "fr",
    "type" => "place",
    "id" => "1",
    "body" => [
        "name" => "Paris"
    ]
]);

var_dump($placeIndexed);

$place = $client->get([
    "index" => "fr",
    "type" => "place",
    "id" => "1"
]);

var_dump($place);

$placeDeleted = $client->delete([
    "index" => "fr",
    "type" => "place",
    "id" => "1"
]);

var_dump($placeDeleted);
