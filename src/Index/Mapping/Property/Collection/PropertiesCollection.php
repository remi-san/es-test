<?php

namespace Evaneos\Elastic\Index\Mapping\Property\Collection;

use Evaneos\Elastic\Index\Mapping\Property;

class PropertiesCollection implements \JsonSerializable
{
    /** @var Property[] */
    private $properties;

    /**
     * FiltersCollection constructor.
     */
    public function __construct()
    {
        $this->properties = [];
    }

    /**
     * @param Property $property
     *
     * @return $this
     */
    public function addProperty(Property $property)
    {
        $this->properties[$property->getName()] = $property;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->properties) === 0;
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
        return $this->properties;
    }
}
