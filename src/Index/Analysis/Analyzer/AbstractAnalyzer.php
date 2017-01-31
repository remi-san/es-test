<?php

namespace Evaneos\Elastic\Index\Analysis\Analyzer;

use Evaneos\Elastic\Index\Analysis\Analyzer;

abstract class AbstractAnalyzer implements Analyzer
{
    const TYPE_CUSTOM = 'custom';

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string */
    private $filtersNames;

    /** @var string */
    private $tokenizerName;

    /**
     * AbstractAnalyzer constructor.
     *
     * @param string $name
     * @param string $type
     * @param string $filtersNames
     * @param string $tokenizerName
     */
    protected function __construct($name, $type, $filtersNames, $tokenizerName)
    {
        $this->name = $name;
        $this->type = $type;
        $this->filtersNames = $filtersNames;
        $this->tokenizerName = $tokenizerName;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getFilters()
    {
        return $this->filtersNames;
    }

    /**
     * @return string
     */
    public function getTokenizer()
    {
        return $this->tokenizerName;
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
            'type' => $this->type,
            'filter' => $this->filtersNames,
            'tokenizer' => $this->tokenizerName
        ];
    }
}
