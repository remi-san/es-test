<?php

namespace Evaneos\Elastic\Index\Analysis;

interface Filter extends \JsonSerializable
{
    const TYPE_LOWER_CASE = 'lowercase';
    const TYPE_ASCII_FOLDING = 'asciifolding';

    /**
     * @return string
     */
    public function getName();
}
