<?php

namespace Evaneos\Elastic\Indexing;

use Assert\AssertionFailedException;
use Evaneos\Elastic\Entity\Place;
use Evaneos\Elastic\Entity\VO\PlaceId;
use Evaneos\Elastic\Index\Exception\TypeException;
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
     * @throws TypeException
     */
    public function index(Place $place)
    {
        $this->index->index(self::TYPE, (string) $place->getId(), $place);
    }

    /**
     * @param PlaceId $id
     *
     * @return Place
     *
     * @throws TypeException
     * @throws \DomainException
     * @throws \OutOfBoundsException
     * @throws AssertionFailedException
     */
    public function get(PlaceId $id)
    {
        $jsonPlace = $this->index->get(self::TYPE, (string) $id);

        if ($jsonPlace === null) {
            return null;
        }

        return Place::fromJsonArray($jsonPlace['_source']);
    }

    /**
     * @param PlaceId $id
     *
     * @throws TypeException
     */
    public function delete(PlaceId $id)
    {
        $this->index->remove(self::TYPE, (string) $id);
    }
}
