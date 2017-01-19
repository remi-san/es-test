<?php

namespace Evaneos\Elastic;

use Evaneos\Elastic\VO\Country;
use Evaneos\Elastic\VO\PlaceId;
use Evaneos\Elastic\VO\Type;

class PlaceIndex
{
    /** @var PlaceId */
    private $id;

    /** @var string */
    private $name;

    /** @var Country */
    private $country;

    /** @var Type[] */
    private $types;

    /** @var PlaceId[] */
    private $hierarchy;

    /**
     * PlaceIndex constructor.
     *
     * @param PlaceId   $id
     * @param string    $name
     * @param Country   $country
     * @param Type[]    $types
     * @param PlaceId[] $hierarchy
     */
    public function __construct(
        PlaceId $id,
        $name,
        Country $country,
        array $types,
        array $hierarchy
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
     * @return PlaceId[]
     */
    public function getHierarchy()
    {
        return $this->hierarchy;
    }
}
