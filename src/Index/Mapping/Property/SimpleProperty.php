<?php

namespace Evaneos\Elastic\Index\Mapping\Property;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Evaneos\Elastic\Index\Mapping\Property;
use Evaneos\Elastic\Index\Mapping\Property\Collection\PropertiesCollection;

class SimpleProperty implements Property
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string */
    private $analyzerName;

    /** @var PropertiesCollection */
    private $fields;

    /**
     * SimpleProperty constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $analyzerName
     *
     * @throws AssertionFailedException
     */
    public function __construct($name, $type, $analyzerName = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->analyzerName = $analyzerName;
        $this->fields = new PropertiesCollection();

        $this->guardType();
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
        return $this->type;
    }

    /**
     * @param Property $property
     *
     * @return $this
     */
    public function addField(Property $property)
    {
        $this->fields->addProperty($property);

        return $this;
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
        $jsonArray = [
            'type' => $this->type
        ];

        if ($this->analyzerName !== null) {
            $jsonArray['analyzer'] = $this->analyzerName;
        }

        if (! $this->fields->isEmpty()) {
            $jsonArray['fields'] = $this->fields;
        }

        return $jsonArray;
    }

    /**
     * @throws AssertionFailedException
     */
    private function guardType()
    {
        Assertion::choice($this->type, [ Property::TYPE_KEYWORD, Property::TYPE_STRING ]);
    }
}
