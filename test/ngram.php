<?php

use Elasticsearch\ClientBuilder;
use Evaneos\Elastic\Index\Analysis;
use Evaneos\Elastic\Index\Analysis\Analyzer\MiddleAutocompleteAnalyzer;
use Evaneos\Elastic\Index\Analysis\Analyzer\PrefixAutocompleteAnalyzer;
use Evaneos\Elastic\Index\Analysis\Filter\EdgeNGramFilter;
use Evaneos\Elastic\Index\Analysis\Filter\NGramFilter;
use Evaneos\Elastic\PlaceIndex;
use Evaneos\Elastic\VO\Country;
use Evaneos\Elastic\VO\PlaceHierarchy;
use Evaneos\Elastic\VO\PlaceId;
use Evaneos\Elastic\VO\Type;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(['default:9200'])->build();

// Delete the index if it existed
try {
    $response = $client->indices()->delete(['index' => 'fr']);
} catch (\Exception $e) {

}

$analysis = new Analysis();
$analysis->addFilter(new EdgeNGramFilter(2, 10));
$analysis->addFilter(new NGramFilter(3, 10));
$analysis->addAnalyzer(new MiddleAutocompleteAnalyzer());
$analysis->addAnalyzer(new PrefixAutocompleteAnalyzer());

// Create the index
$indexParams = [
    'index' => 'fr',
    'body' => [
        'settings' => [
            'analysis' => json_decode(json_encode($analysis), true)
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
                                'analyzer' => 'prefix_autocomplete_analyzer'
                            ],
                            'middle_autocomplete' => [
                                'type' => 'string',
                                'analyzer' => 'middle_autocomplete_analyzer'
                            ]
                        ]
                    ],
                    'country' => [
                        'type' => 'keyword'
                    ],
                    'type' => [
                        'type' => 'nested',
                        'properties' => [
                            'key' => [ 'type' => 'keyword' ],
                            'name' => [ 'type' => 'string' ]
                        ]
                    ],
                    'hierarchy' => [
                        'type' => 'object',
                        'properties' => [
                            'parents' => [ 'type' => 'keyword' ],
                            'grandParents' => [ 'type' => 'keyword' ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
$client->indices()->create($indexParams);

// Create the place
$uuid = (string) Uuid::uuid4();
$place = new PlaceIndex(
    new PlaceId($uuid),
    [ 'Paris', 'Lutèce', 'Paname', 'Parigi' ],
    [ new Country('fr') ],
    [ new Type('city', 'A populated place'), new Type('capital', 'A capital of a country') ],
    new PlaceHierarchy(
        [ new PlaceId((string) Uuid::uuid4()) ],
        [ new PlaceId((string) Uuid::uuid4()) ]
    )
);
$placeIndexed = $client->index([
    'index' => 'fr',
    'type' => 'place',
    'id' => $uuid,
    'body' => json_encode($place)
]);

// Get the place
$place = $client->get([
    'index' => 'fr',
    'type' => 'place',
    'id' => $uuid
]);
var_dump($place);

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
//var_dump($searchResult);

// Delete
$client->delete([
    'index' => 'fr',
    'type' => 'place',
    'id' => $uuid
]);
