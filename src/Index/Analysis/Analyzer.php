<?php

namespace Evaneos\Elastic\Index\Analysis;

interface Analyzer extends \JsonSerializable
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string[]
     */
    public function getFilters();

    /**
     * @return string
     */
    public function getTokenizer();
}
