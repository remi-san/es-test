<?php

namespace Evaneos\Elastic\Index\Analysis\Tokenizer\Collection;

use Evaneos\Elastic\Index\Analysis\Tokenizer;

class TokenizersCollection implements \JsonSerializable
{
    /**
     * @var Tokenizer[]
     */
    private $tokenizers;

    /**
     * FiltersCollection constructor.
     */
    public function __construct()
    {
        $this->tokenizers = [];
    }

    /**
     * @param Tokenizer $tokenizer
     */
    public function addTokenizer(Tokenizer $tokenizer)
    {
        $this->tokenizers[$tokenizer->getName()] = $tokenizer;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->tokenizers) === 0;
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
        return $this->tokenizers;
    }
}
