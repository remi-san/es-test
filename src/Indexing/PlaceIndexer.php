<?php

namespace Evaneos\Elastic\Indexing;

use Evaneos\Elastic\Entity\Place;
use Evaneos\Elastic\Entity\VO\PlaceId;
use Evaneos\Elastic\Index\Index;

class PlaceIndexer
{
    const TYPE = 'place';

    /** @var Index */
    private $index;

    /**
     * PlaceIndexer constructor.
     *
     * @param Index  $index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    /**
     * @param Place $place
     *
     * @return array
     */
    public function index(Place $place)
    {
        // TODO deal with return

        return $this->index->index(self::TYPE, (string) $place->getId(), $place);
    }

    /**
     * @param PlaceId $id
     *
     * @return array
     */
    public function get(PlaceId $id)
    {
        // TODO cast to Place

        return $this->index->get(self::TYPE, (string) $id);
    }

    /**
     * @param PlaceId $id
     *
     * @return array
     */
    public function delete(PlaceId $id)
    {
        // TODO deal with return

        return $this->index->remove(self::TYPE, (string) $id);
    }
}
