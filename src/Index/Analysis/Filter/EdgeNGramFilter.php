<?php

namespace Evaneos\Elastic\Index\Analysis\Filter;

use Evaneos\Elastic\Index\Analysis\Filter;

class EdgeNGramFilter implements Filter
{
    const NAME = 'edge_nGram_filter';

    /** @var string */
    private $name;

    /** @var int */
    private $min;

    /** @var int */
    private $max;

    /**
     * nGramFilter constructor.
     *
     * @param int    $min
     * @param int    $max
     */
    public function __construct($min, $max)
    {
        $this->name = self::NAME;
        $this->min = $min;
        $this->max = $max;
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
            'type'     => 'edgeNGram',
            'max_gram' => $this->max,
            'min_gram' => $this->min
        ];
    }
}
