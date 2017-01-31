<?php

namespace Evaneos\Elastic\Index;

use Evaneos\Elastic\Index\Mapping\Collection\MappingsCollection;

class Definition implements \JsonSerializable
{
    /** @var Analysis */
    private $analysis;

    /** @var MappingsCollection */
    private $mappings;

    /**
     * Definition constructor.
     *
     * @param Analysis           $analysis
     * @param MappingsCollection $mappings
     */
    public function __construct(Analysis $analysis, MappingsCollection $mappings)
    {
        $this->analysis = $analysis;
        $this->mappings = $mappings;
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
