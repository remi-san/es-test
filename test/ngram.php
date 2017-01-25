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
        'settings' => [
            'analysis' => [
                'filter' => [
                    'ngrams_for_all' => [
                        'type'     => 'nGram',
                        'max_gram' => '10',
                        'min_gram' => '3'
                    ],
                    'ngrams_for_prefix' => [
                        'type'     => 'edgeNGram',
                        'max_gram' => '10',
                        'min_gram' => '2',
                        'side'     => 'front'
                    ]
                ],
                'analyzer' => [
                    'middle_autocomplete' => [
                        'filter' => [
                            'lowercase',
                            'asciifolding',
                            'ngrams_for_all'
                        ],
                        'type' => 'custom',
                        'tokenizer' => 'standard'
                    ],
                    'prefix_autocomplete' => [
                        'filter' => [
                            'lowercase',
                            'asciifolding',
                            'ngrams_for_prefix'
                        ],
                        'type' => 'custom',
                        'tokenizer' => 'standard'
                    ]
                ]
            ]
        ],
        'mappings' => [
            'place' => [
                'properties' => [
                    'name' => [
                        'type' => 'string',
                        'analyzer' => 'french',
                        'fields' => [
                            'prefix_autocomplete' => [
                                'type' => 'string',
                                'analyzer' => 'prefix_autocomplete'
                            ],
                            'middle_autocomplete' => [
                                'type' => 'string',
                                'analyzer' => 'middle_autocomplete'
                            ]
                        ]
                    ],
                    'type' => [
                        'type' => 'nested',
                        'properties' => [
                            'key' => [ 'type' => 'keyword' ],
                            'name' => [ 'type' => 'string' ]
                        ]
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
        'name' => [ 'Paris', 'Lutèce', 'Paname', 'Parigi' ],
        'type' => [
            [ 'key' => 'city', 'name' => 'A populated place' ],
            [ 'key' => 'capital', 'name' => 'A capital of a country' ]
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
$term = 'Paris';
$type = 'city';
$searchReq = [
    'index' => 'fr',
    'type' => 'place',
    'body' => [
        'query' => [
            'bool' => [
                'must' => [
                    [
                        'nested' => [
                            'path' => 'type',
                            'query' => [
                                'bool' => [
                                    'should' => [
                                        [ 'term' => [ 'type.key' => $type ] ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        "multi_match" => [
                            "query" =>  $term,
                            "type" =>   "most_fields",
                            "fields" => [ "name^10", "name.prefix_autocomplete^3", "name.middle_autocomplete" ]
                        ]
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
