<?php

namespace Evaneos\Elastic\VO;

use Assert\Assertion;
use Assert\AssertionFailedException;

class PlaceId
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
}
