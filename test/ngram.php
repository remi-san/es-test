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

// Delete the index if it existed
if ($index->exists()) {
    $index->delete();
}
$index->create();

$indexer = new PlaceIndexer($index);
$search = new PlaceSearch($index);

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

$indexer->index($place);

// Get the place
$place = $indexer->get($placeId);

// Search
$criteria = (new PlaceCriteria())
    ->filterByCountry($fr)
    ->filterByParent($parentId)
    ->filterByType($placeType);
$searchResult = $search->autocomplete('Paris', $criteria);
echo json_encode($searchResult) . PHP_EOL;

// Delete
$indexer->delete($placeId);
