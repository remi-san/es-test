<?php

namespace Evaneos\Elastic;

use Evaneos\Elastic\VO\Country;
use Evaneos\Elastic\VO\PlaceHierarchy;
use Evaneos\Elastic\VO\PlaceId;
use Evaneos\Elastic\VO\Type;

class PlaceIndex implements \JsonSerializable
{
    /** @var PlaceId */
    private $id;

    /** @var string[] */
    private $name;

    /** @var Country */
    private $country;

    /** @var Type[] */
    private $types;

    /** @var PlaceHierarchy */
    private $hierarchy;

    /**
     * PlaceIndex constructor.
     *
     * @param PlaceId        $id
     * @param string[]       $name
     * @param Country        $country
     * @param Type[]         $types
     * @param PlaceHierarchy $hierarchy
     */
    public function __construct(
        PlaceId $id,
        array $name,
        Country $country,
        array $types,
        PlaceHierarchy $hierarchy
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->types = $types;
        $this->hierarchy = $hierarchy;
    }

    /**
     * @return PlaceId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return Type[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @return PlaceHierarchy
     */
    public function getHierarchy()
    {
        return $this->hierarchy;
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
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'types' => $this->types,
            'hierarchy' => $this->hierarchy
        ];
    }
}
