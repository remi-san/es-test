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

// Connection
$client = ClientBuilder::create()->setHosts(['default:9200'])->build();
$index = new LanguageIndex($client, 'fr', 'french');

// Delete the Index if it existed
if ($index->exists()) {
    $index->delete();
}
// Create Index
$index->create();

// Create the place
$placeId = new PlaceId((string) Uuid::uuid4());
$names = [ 'Paris', 'Lutèce', 'Paname', 'Parigi' ];
$fr = new Country('fr');
$placeType = new Type('city', 'A populated place');
$capitalType = new Type('capital', 'A capital of a country');
$parentId = new PlaceId((string) Uuid::uuid4());
$grandParentId = new PlaceId((string) Uuid::uuid4());
$placeHierarchy = new PlaceHierarchy([ $parentId ], [ $grandParentId ]);

$place = new Place($placeId, $names, [ $fr ], [ $placeType, $capitalType ], $placeHierarchy);

// Index
$indexer = new PlaceIndexer($index);
$indexer->index($place);

// Get the place
$placeRetrieved = $indexer->get($placeId);
echo json_encode($placeRetrieved) . PHP_EOL;

// Search
$search = new PlaceSearch($index);
$criteria = (new PlaceCriteria())->filterByCountry($fr)->filterByParent($parentId)->filterByType($placeType);
$searchResult = $search->autocomplete('Paris', $criteria);
echo json_encode($searchResult) . PHP_EOL;

// Delete
$indexer->delete($placeId);
