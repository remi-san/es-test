<?php

use Elasticsearch\ClientBuilder;

require __DIR__ . '/../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(['default:9200'])->build();

// Delete the index if it existed
try {
    $response = $client->indices()->delete(['index' => 'fr']);
} catch (\Exception $e) {
}

// Create the index
$indexParams = [
    'index' => 'fr',
    'body' => [
        'mappings' => [
            'place' => [
                'properties' => [
                    'suggest' => [
                        'type' => 'completion'
                    ],
                    'name' => [
                        'type' => 'string',
                        'analyzer' => 'french'
                    ],
                    'type' => [
                        'type' => 'keyword'
                    ]
                ]
            ]
        ]
    ]
];
$client->indices()->create($indexParams);

// Create the place
$placeIndexed = $client->index([
    'index' => 'fr',
    'type' => 'place',
    'id' => 'abcd',
    'body' => [
        'name' => 'Paris',
        'type' => 'city',
        'suggest' => [
            [ 'input' =>'Lutèce', 'weight' => 3 ],
            [ 'input' =>'Paname', 'weight' => 5 ],
            [ 'input' =>'Paris', 'weight' => 10 ]
        ]
    ]
]);

// Get the place
$place = $client->get([
    'index' => 'fr',
    'type' => 'place',
    'id' => 'abcd'
]);
var_dump($place);

// Search by autocomplete
$searchReq = [
    'index' => 'fr',
    'type' => 'place',
    'body' => [
        '_source' => 'suggest',
        'suggest' => [
            'place-suggest' => [
                'prefix' => 'pa',
                'completion' => [
                    'field' => 'suggest',
                    'fuzzy' => [
                        'fuzziness' => 'auto'
                    ]
                ]
            ]
        ]
    ]
];
$searchResult = $client->search($searchReq);
var_dump($searchResult);

// Delete
$client->delete([
    'index' => 'fr',
    'type' => 'place',
    'id' => 'abcd'
]);
