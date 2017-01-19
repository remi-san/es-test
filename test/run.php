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
                        'type' => 'completion',
                        'contexts' => [
                            [
                                'name' => 'place_type',
                                'type' => 'category'
                            ],
                            [
                                'name' => 'place_hierarchy',
                                'type' => 'category'
                            ],
                            [
                                'name' => 'place_country',
                                'type' => 'category'
                            ]
                        ],
                        'analyzer' => 'french'
                    ],
                    'name' => [
                        'type' => 'string',
                        'analyzer' => 'french'
                    ],
                    'type' => [
                        'type' => 'nested'
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
        'type' => [ 'name' => 'city' ],
        'suggest' => [
            [
                'input' =>'Lutèce',
                'weight' => 3,
                'contexts' => [
                    'place_type' => [ 'city', 'populated_place', 'admin4' ],
                    'place_hierarchy' => [ 'fr', 'idf', '75' ],
                    'place_country' => [ 'fr' ]
                ]
            ],
            [
                'input' =>'Paname',
                'weight' => 5,
                'contexts' => [
                    'place_type' => [ 'city', 'populated_place', 'admin4' ],
                    'place_hierarchy' => [ 'fr', 'idf', '75' ],
                    'place_country' => [ 'fr' ]
                ]
            ],
            [
                'input' =>'Paris',
                'weight' => 10,
                'contexts' => [
                    'place_type' => [ 'city', 'populated_place', 'admin4' ],
                    'place_hierarchy' => [ 'fr', 'idf', '75' ],
                    'place_country' => [ 'fr' ]
                ]
            ]
        ]
    ]
]);

// Get the place
$place = $client->get([
    'index' => 'fr',
    'type' => 'place',
    'id' => 'abcd'
]);
//var_dump($place);

// Search by autocomplete
$searchReq = [
    'index' => 'fr',
    'type' => 'place',
    'body' => [
        '_source' => 'suggest',
        'suggest' => [
            'place-suggest' => [
                'prefix' => 'lutoc',
                'completion' => [
                    'field' => 'suggest',
                    'fuzzy' => [
                        'fuzziness' => 'auto'
                    ],
                    'contexts' => [
                        'place_hierarchy' => [ 'idf' ],
                        'place_type' => [ 'city' ]
                    ]
                ]
            ]
        ]
    ]
];
$searchResult = $client->search($searchReq);
var_dump($searchResult);

$multiget = $client->mget([
    'index' => 'fr',
    'type' => 'place',
    'body' => [
        'ids' => ['abcd', 'toto']
    ]
]);
//var_dump($multiget);

// Delete
$client->delete([
    'index' => 'fr',
    'type' => 'place',
    'id' => 'abcd'
]);
