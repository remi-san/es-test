<?php

namespace Evaneos\Elastic\Indexing;

use Elasticsearch\Client;
use Evaneos\Elastic\Entity\PlaceIndex;
use Evaneos\Elastic\Entity\VO\PlaceId;

class PlaceIndexer
{
    const TYPE = 'place';

    /** @var Client */
    private $client;

    /** @var string */
    private $index;

    /**
     * PlaceIndexer constructor.
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
     * @param PlaceIndex $place
     *
     * @return array
     */
    public function index(PlaceIndex $place)
    {
        // TODO deal with return

        return $this->client->index([
            'index' => $this->index,
            'type' => self::TYPE,
            'id' => (string) $place->getId(),
            'body' => json_encode($place)
        ]);
    }

    /**
     * @param PlaceId $id
     *
     * @return array
     */
    public function get(PlaceId $id)
    {
        // TODO cast to PlaceIndex

        return $this->client->get([
            'index' => $this->index,
            'type' => self::TYPE,
            'id' => (string) $id
        ]);
    }

    /**
     * @param PlaceId $id
     *
     * @return array
     */
    public function delete(PlaceId $id)
    {
        // TODO deal with return

        return $this->client->delete([
            'index' => $this->index,
            'type' => self::TYPE,
            'id' => (string) $id
        ]);
    }
}
