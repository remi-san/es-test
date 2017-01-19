<?php

namespace Evaneos\Elastic\VO;

use League\ISO3166\ISO3166;

class Country
{
    /** @var string */
    private $iso;

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

        $this->iso = $countryInformation['alpha2'];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->iso;
    }
}
