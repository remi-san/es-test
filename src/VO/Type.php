<?php

namespace Evaneos\Elastic\VO;

class Type
{
    /** @var string */
    private $key;

    /** @var string */
    private $name;

    /**
     * Type constructor.
     *
     * @param string $key
     * @param string $name
     */
    public function __construct($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
