<?php

namespace Evaneos\Elastic\Index;

use Elasticsearch\Client;
use Evaneos\Elastic\Index\Mapping\Collection\MappingsCollection;

class Definition implements \JsonSerializable
{
    /** @var string */
    private $name;

    /** @var Analysis */
    private $analysis;

    /** @var MappingsCollection */
    private $mappings;

    /**
     * Definition constructor.
     *
     * @param string             $name
     * @param Analysis           $analysis
     * @param MappingsCollection $mappings
     */
    public function __construct($name, Analysis $analysis, MappingsCollection $mappings)
    {
        $this->name = $name;
        $this->analysis = $analysis;
        $this->mappings = $mappings;
    }

    /**
     * @param Client $client
     *
     * @return array
     */
    public function create(Client $client)
    {
        return $client
            ->indices()
            ->create([
                'index' => $this->name,
                'body' => json_encode($this)
            ]);
    }

    /**
     * @param Client $client
     *
     * @return array
     */
    public function delete(Client $client)
    {
        return $client
            ->indices()
            ->delete([
                'index' => $this->name
            ]);
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *        which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $jsonArray = [];

        if ($this->analysis->isDefined()) {
            $jsonArray['settings'] = [
                'analysis' => $this->analysis
            ];
        }

        if (! $this->mappings->isEmpty()) {
            $jsonArray['mappings'] = $this->mappings;
        }

        return $jsonArray;
    }
}
