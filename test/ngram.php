<?php

use Elasticsearch\ClientBuilder;
use Evaneos\Elastic\Index\Analysis;
use Evaneos\Elastic\Index\Analysis\Analyzer\MiddleAutocompleteAnalyzer;
use Evaneos\Elastic\Index\Analysis\Analyzer\PrefixAutocompleteAnalyzer;
use Evaneos\Elastic\Index\Analysis\Filter\EdgeNGramFilter;
use Evaneos\Elastic\Index\Analysis\Filter\NGramFilter;
use Evaneos\Elastic\Index\Definition;
use Evaneos\Elastic\Index\Mapping;
use Evaneos\Elastic\Index\Mapping\Collection\MappingsCollection;
use Evaneos\Elastic\Index\Mapping\Property;
use Evaneos\Elastic\Index\Mapping\Property\Collection\PropertiesCollection;
use Evaneos\Elastic\Index\Mapping\Property\NestedProperty;
use Evaneos\Elastic\Index\Mapping\Property\ObjectProperty;
use Evaneos\Elastic\Index\Mapping\Property\SimpleProperty;
use Evaneos\Elastic\Entity\PlaceIndex;
use Evaneos\Elastic\Entity\VO\Country;
use Evaneos\Elastic\Entity\VO\PlaceHierarchy;
use Evaneos\Elastic\Entity\VO\PlaceId;
use Evaneos\Elastic\Entity\VO\Type;
use Evaneos\Elastic\Indexing\PlaceIndexer;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/../vendor/autoload.php';

$analysis = (new Analysis())
    ->addFilter(new EdgeNGramFilter(2, 10))
    ->addFilter(new NGramFilter(3, 10))
    ->addAnalyzer(new MiddleAutocompleteAnalyzer())
    ->addAnalyzer(new PrefixAutocompleteAnalyzer());

$mappings = (new MappingsCollection())
    ->addMapping(
        (new Mapping('place'))
            ->addProperty(
                (new SimpleProperty('name', Property::TYPE_STRING, 'french'))
                    ->addField(
                        new SimpleProperty(
                            'prefix_autocomplete',
                            Property::TYPE_STRING,
                            PrefixAutocompleteAnalyzer::NAME
                        )
                    )
                    ->addField(
                        new SimpleProperty(
                            'middle_autocomplete',
                            Property::TYPE_STRING,
                            MiddleAutocompleteAnalyzer::NAME
                        )
                    )
            )
            ->addProperty(new SimpleProperty('country', Property::TYPE_KEYWORD))
            ->addProperty(
                new NestedProperty(
                    'type',
                    (new PropertiesCollection())
                        ->addProperty(new SimpleProperty('key', Property::TYPE_KEYWORD))
                        ->addProperty(new SimpleProperty('name', Property::TYPE_STRING))
                )
            )
            ->addProperty(
                new ObjectProperty(
                    'hierarchy',
                    (new PropertiesCollection())
                        ->addProperty(new SimpleProperty('parent', Property::TYPE_KEYWORD))
                        ->addProperty(new SimpleProperty('grandParent', Property::TYPE_KEYWORD))
                )
            )
    );

$definition = new Definition('fr', $analysis, $mappings);
$client = ClientBuilder::create()->setHosts(['default:9200'])->build();
$indexer = new PlaceIndexer($client, 'fr');

// Delete the index if it existed
try {
    $definition->delete($client);
} catch (\Exception $e) {
}

$definition->create($client);

// Create the place
$uuid = (string) Uuid::uuid4();
$parentUuid = (string) Uuid::uuid4();
$place = new PlaceIndex(
    new PlaceId($uuid),
    [ 'Paris', 'Lutèce', 'Paname', 'Parigi' ],
    [ new Country('fr') ],
    [ new Type('city', 'A populated place'), new Type('capital', 'A capital of a country') ],
    new PlaceHierarchy(
        [ new PlaceId($parentUuid) ],
        [ new PlaceId((string) Uuid::uuid4()) ]
    )
);
$placeIndexed = $indexer->index($place);

// Get the place
$place = $indexer->get(new PlaceId($uuid));
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
                        'multi_match' => [
                            'query' =>  $term,
                            'type' =>   'most_fields',
                            'fields' => [ 'name^10', 'name.prefix_autocomplete^3', 'name.middle_autocomplete' ]
                        ]
                    ],
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
                        'term' => [ 'hierarchy.parent' => $parentUuid ]
                    ],
                    [
                        'term' => [ 'country' => (string) new Country('fr') ]
                    ]
                ]
            ]
        ]
    ]
];
$searchResult = $client->search($searchReq);
var_dump($searchResult);

// Delete
$indexer->delete(new PlaceId($uuid));
