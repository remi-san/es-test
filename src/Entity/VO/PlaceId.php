<?php

namespace Evaneos\Elastic\Entity\VO;

use Assert\Assertion;
use Assert\AssertionFailedException;

class PlaceId implements \JsonSerializable
{
    /** @var string */
    private $id;

    /**
     * PlaceId constructor.
     *
     * @param string $id
     *
     * @throws AssertionFailedException
     */
    public function __construct($id)
    {
        Assertion::uuid($id);

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id;
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
        return $this->id;
    }
}
