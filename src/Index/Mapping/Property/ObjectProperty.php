<?php

namespace Evaneos\Elastic\Index\Mapping\Property;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Evaneos\Elastic\Index\Mapping\Property;
use Evaneos\Elastic\Index\mapping\Property\Collection\PropertiesCollection;

class ObjectProperty implements Property
{
    /** @var string */
    private $name;

    /** @var PropertiesCollection */
    private $properties;

    /**
     * NestedProperty constructor.
     *
     * @param string               $name
     * @param PropertiesCollection $properties
     *
     * @throws AssertionFailedException
     */
    public function __construct($name, PropertiesCollection $properties)
    {
        $this->name = $name;
        $this->properties = $properties;

        $this->guardProperties();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_OBJECT;
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
        return [
            'type' => $this->getType(),
            'properties' => $this->properties
        ];
    }

    /**
     * @throws AssertionFailedException
     */
    private function guardProperties()
    {
        Assertion::false($this->properties->isEmpty(), 'You must provide properties');
    }
}
