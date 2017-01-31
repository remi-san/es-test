<?php

namespace Evaneos\Elastic\Index\Analysis;

interface Tokenizer extends \JsonSerializable
{
    const TYPE_STANDARD = 'standard';

    /**
     * @return string
     */
    public function getName();
}