<?php

namespace Evaneos\Elastic\Index\Mapping\Collection;

use Evaneos\Elastic\Index\Mapping;

class MappingsCollection implements \JsonSerializable
{
    /**
     * @var Mapping[]
     */
    private $mappings;

    /**
     * FiltersCollection constructor.
     */
    public function __construct()
    {
        $this->mappings = [];
    }

    /**
     * @param Mapping $mapping
     *
     * @return $this
     */
    public function addMapping(Mapping $mapping)
    {
        $this->mappings[$mapping->getName()] = $mapping;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->mappings) === 0;
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
        return $this->mappings;
    }
}
