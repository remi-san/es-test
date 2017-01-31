<?php

namespace Evaneos\Elastic\Index\Analysis\Analyzer\Collection;

use Evaneos\Elastic\Index\Analysis\Analyzer;

class AnalyzersCollection implements \JsonSerializable
{
    /**
     * @var Analyzer[]
     */
    private $analyzers;

    /**
     * FiltersCollection constructor.
     */
    public function __construct()
    {
        $this->analyzers = [];
    }

    /**
     * @param Analyzer $analyzer
     */
    public function addAnalyzer(Analyzer $analyzer)
    {
        $this->analyzers[$analyzer->getName()] = $analyzer;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->analyzers) === 0;
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
        return $this->analyzers;
    }
}
