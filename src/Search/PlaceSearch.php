<?php

namespace Evaneos\Elastic\Search;

use Evaneos\Elastic\Index\Index;

class PlaceSearch
{
    const TYPE = 'place';

    /** @var Index */
    private $index;

    /**
     * PlaceSearch constructor.
     *
     * @param Index $index
     */
    public function __construct(Index $index)
    {
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
            'query' => [
                'bool' => [
                    'must' => $must
                ]
            ]
        ];

        // TODO parse return

        return $this->index->search(self::TYPE, $searchReq);
    }
}
