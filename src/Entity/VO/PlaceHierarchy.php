<?php

namespace Evaneos\Elastic\Entity\VO;

use Evaneos\Elastic\Entity\VO\PlaceId;

class PlaceHierarchy implements \JsonSerializable
{
    /** @var PlaceId[] */
    private $parents;

    /** @var PlaceId[] */
    private $grandParents;

    /**
     * PlaceHierarchy constructor.
     *
     * @param PlaceId[] $parents
     * @param PlaceId[] $grandParents
     */
    public function __construct(array $parents, array $grandParents)
    {
        $this->parents = $parents;
        $this->grandParents = $grandParents;
    }

    /**
     * @return PlaceId[]
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @return PlaceId[]
     */
    public function getGrandParents()
    {
        return $this->grandParents;
    }

    /**
     * @param PlaceId[] $parents
     */
    public function setParents($parents)
    {
        $this->parents = $parents;
    }

    /**
     * @param PlaceId[] $grandParents
     */
    public function setGrandParents($grandParents)
    {
        $this->grandParents = $grandParents;
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
            'parent' => $this->parents,
            'grandParent' => $this->grandParents
        ];
    }
}
