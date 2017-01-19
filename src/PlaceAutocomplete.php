<?php

namespace Evaneos\Elastic;

class PlaceAutocomplete
{
    /** @var string */
    private $partial;

    /** @var string */
    private $id;

    /** @var string */
    private $complete;

    /** @var string */
    private $type;

    /** @var string */
    private $country;

    /** @var string */
    private $hierarchy;

    /**
     * PlaceAutocomplete constructor.
     *
     * @param string $partial
     * @param string $id
     * @param string $complete
     * @param string $type
     * @param string $country
     * @param string $hierarchy
     */
    public function __construct($partial, $id, $complete, $type, $country, $hierarchy)
    {
        $this->partial = $partial;
        $this->id = $id;
        $this->complete = $complete;
        $this->type = $type;
        $this->country = $country;
        $this->hierarchy = $hierarchy;
    }

    /**
     * @return string
     */
    public function getPartial()
    {
        return $this->partial;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getHierarchy()
    {
        return $this->hierarchy;
    }
}
