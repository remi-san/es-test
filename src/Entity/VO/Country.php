<?php

namespace Evaneos\Elastic\Entity\VO;

use League\ISO3166\ISO3166;

class Country implements \JsonSerializable
{
    /** @var string */
    private $iso2;

    /** @var string */
    private $name;

    /**
     * Country constructor.
     *
     * @param string $iso
     *
     * @throws \DomainException
     * @throws \OutOfBoundsException
     */
    public function __construct($iso)
    {
        $countryInformation = (new ISO3166())->getByAlpha2($iso);

        $this->iso2 = $countryInformation['alpha2'];
        $this->name = $countryInformation['name'];
    }

    /**
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
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
    public function __toString()
    {
        return $this->iso2;
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
        return $this->iso2;
    }
}
