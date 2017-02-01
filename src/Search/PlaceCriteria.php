<?php

namespace Evaneos\Elastic\Search;

use Evaneos\Elastic\Entity\VO\Country;
use Evaneos\Elastic\Entity\VO\PlaceId;
use Evaneos\Elastic\Entity\VO\Type;

class PlaceCriteria implements \JsonSerializable
{
    /** @var string */
    private $typeKey;

    /** @var string */
    private $countryIsoCode;

    /** @var string */
    private $parentId;

    /**
     * @param Type $type
     *
     * @return $this
     */
    public function filterByType(Type $type)
    {
        $this->typeKey = $type->getKey();

        return $this;
    }

    /**
     * @param Country $country
     *
     * @return $this
     */
    public function filterByCountry(Country $country)
    {
        $this->countryIsoCode = (string) $country;

        return $this;
    }

    /**
     * @param PlaceId $parentId
     *
     * @return $this
     */
    public function filterByParent(PlaceId $parentId)
    {
        $this->parentId = (string) $parentId;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCriteria()
    {
        return $this->typeKey !== null
            || $this->countryIsoCode !== null
            || $this->parentId !== null;
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
        if (! $this->hasCriteria()) {
            return null;
        }

        $jsonCriteria = [];

        if ($this->typeKey !== null) {
            $jsonCriteria[] = [
                'nested' => [
                    'path' => 'type',
                    'query' => [
                        'bool' => [
                            'should' => [
                                [ 'term' => [ 'type.key' => $this->typeKey ] ]
                            ]
                        ]
                    ]
                ]
            ];
        }

        if ($this->countryIsoCode !== null) {
            $jsonCriteria[] = [
                'term' => [ 'country' => $this->countryIsoCode ]
            ];
        }

        if ($this->parentId !== null) {
            $jsonCriteria[] = [
                'term' => [ 'hierarchy.parent' => $this->parentId ]
            ];
        }

        return [
            'bool' => [
                'must' => $jsonCriteria
            ]
        ];
    }
}
