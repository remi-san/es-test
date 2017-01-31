<?php

namespace Evaneos\Elastic\Index;

use Evaneos\Elastic\Index\Analysis\Analyzer;
use Evaneos\Elastic\Index\Analysis\Analyzer\Collection\AnalyzersCollection;
use Evaneos\Elastic\Index\Analysis\Filter;
use Evaneos\Elastic\Index\Analysis\Filter\Collection\FiltersCollection;
use Evaneos\Elastic\Index\Analysis\Tokenizer;
use Evaneos\Elastic\Index\Analysis\Tokenizer\Collection\TokenizersCollection;

class Analysis implements \JsonSerializable
{
    private $filters;

    private $tokenizers;

    private $analyzers;

    /**
     * Index constructor.
     */
    public function __construct()
    {
        $this->filters = new FiltersCollection();
        $this->tokenizers = new TokenizersCollection();
        $this->analyzers = new AnalyzersCollection();
    }

    /**
     * @param Filter $filter
     *
     * @return $this
     */
    public function addFilter(Filter $filter)
    {
        $this->filters->addFilter($filter);

        return $this;
    }

    /**
     * @param Tokenizer $tokenizer
     *
     * @return $this
     */
    public function addTokenizer(Tokenizer $tokenizer)
    {
        $this->tokenizers->addTokenizer($tokenizer);

        return $this;
    }

    /**
     * @param Analyzer $analyzer
     *
     * @return $this
     */
    public function addAnalyzer(Analyzer $analyzer)
    {
        $this->analyzers->addAnalyzer($analyzer);

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefined()
    {
        return ! $this->filters->isEmpty()
            || ! $this->tokenizers->isEmpty()
            || ! $this->analyzers->isEmpty();
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
        if (! $this->isDefined()) {
            return null;
        }

        $jsonArray = [];

        if (!$this->filters->isEmpty()) {
            $jsonArray['filter'] = $this->filters;
        }

        if (!$this->tokenizers->isEmpty()) {
            $jsonArray['tokenizer'] = $this->tokenizers;
        }

        if (!$this->analyzers->isEmpty()) {
            $jsonArray['analyzer'] = $this->analyzers;
        }

        return $jsonArray;
    }
}
