<?php

namespace Evaneos\Elastic\VO;

class Type implements \JsonSerializable
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
            'key' => $this->key,
            'name' => $this->name
        ];
    }
}
