<?php

namespace Evaneos\Elastic\Entity;

use Evaneos\Elastic\Entity\VO\Country;
use Evaneos\Elastic\Entity\VO\PlaceHierarchy;
use Evaneos\Elastic\Entity\VO\PlaceId;
use Evaneos\Elastic\Entity\VO\Type;

class Place implements \JsonSerializable
{
    /** @var PlaceId */
    private $id;

    /** @var string[] */
    private $name;

    /** @var Country[] */
    private $countries;

    /** @var Type[] */
    private $types;

    /** @var PlaceHierarchy */
    private $hierarchy;

    /**
     * Place constructor.
     *
     * @param PlaceId        $id
     * @param string[]       $name
     * @param Country[]      $countries
     * @param Type[]         $types
     * @param PlaceHierarchy $hierarchy
     */
    public function __construct(
        PlaceId $id,
        array $name,
        array $countries,
        array $types,
        PlaceHierarchy $hierarchy
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->countries = $countries;
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
     * @return Country[]
     */
    public function getCountries()
    {
        return $this->countries;
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
            'country' => $this->countries,
            'type' => $this->types,
            'hierarchy' => $this->hierarchy
        ];
    }
}
