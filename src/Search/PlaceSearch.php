<?php

namespace Evaneos\Elastic\Search;

use Elasticsearch\Client;

class PlaceSearch
{
    const TYPE = 'place';

    /** @var Client */
    private $client;

    /** @var string */
    private $index;

    /**
     * PlaceSearch constructor.
     *
     * @param Client $client
     * @param string $index
     */
    public function __construct(Client $client, $index)
    {
        $this->client = $client;
        $this->index = $index;
    }

    /**
     * @param string        $partial
     * @param PlaceCriteria $criteria
     *
     * @return array
     */
    public function autocomplete($partial, PlaceCriteria $criteria = null)
    {
        $must = [
            [
                'multi_match' => [
                    'query' =>  $partial,
                    'type' =>   'most_fields',
                    'fields' => [ 'name^10', 'name.prefix_autocomplete^3', 'name.middle_autocomplete' ]
                ]
            ]
        ];

        if ($criteria !== null && $criteria->hasCriteria()) {
            $must[] = $criteria;
        }

        $searchReq = [
            'index' => $this->index,
            'type' => self::TYPE,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => $must
                    ]
                ]
            ]
        ];

        // TODO parse return

        return $this->client->search($searchReq);
    }
}
