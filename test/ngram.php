<?php

require __DIR__ . '/../vendor/autoload.php';

use Elasticsearch\ClientBuilder;
use Evaneos\Elastic\Entity\Place;
use Evaneos\Elastic\Entity\VO\Country;
use Evaneos\Elastic\Entity\VO\PlaceHierarchy;
use Evaneos\Elastic\Entity\VO\PlaceId;
use Evaneos\Elastic\Entity\VO\Type;
use Evaneos\Elastic\Indexing\LanguageIndex;
use Evaneos\Elastic\Indexing\PlaceIndexer;
use Evaneos\Elastic\Search\PlaceCriteria;
use Evaneos\Elastic\Search\PlaceSearch;
use Ramsey\Uuid\Uuid;

$index = new LanguageIndex(
    ClientBuilder::create()
        ->setHosts(['default:9200'])
        ->build(),
    'fr',
    'french'
);
$indexer = new PlaceIndexer($index);
$search = new PlaceSearch($index);

// Delete the index if it existed
try {
    $index->delete();
} catch (\Exception $e) {
}

$index->create();

// Create the place
$placeId = new PlaceId((string) Uuid::uuid4());
$parentId = new PlaceId((string) Uuid::uuid4());
$placeType = new Type('city', 'A populated place');
$fr = new Country('fr');

$place = new Place(
    $placeId,
    [ 'Paris', 'Lutèce', 'Paname', 'Parigi' ],
    [ $fr ],
    [ $placeType, new Type('capital', 'A capital of a country') ],
    new PlaceHierarchy(
        [ $parentId ],
        [ new PlaceId((string) Uuid::uuid4()) ]
    )
);

$placeIndexed = $indexer->index($place);

// Get the place
$place = $indexer->get($placeId);

// Search
$criteria = (new PlaceCriteria())
    ->filterByCountry($fr)
    ->filterByParent($parentId)
    ->filterByType($placeType);
$searchResult = $search->autocomplete('Paris', $criteria);
var_dump($searchResult);

// Delete
$indexer->delete($placeId);
