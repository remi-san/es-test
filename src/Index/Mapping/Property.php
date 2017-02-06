<?php

namespace Evaneos\Elastic\Index\Mapping;

interface Property extends \JsonSerializable
{
    const TYPE_STRING = 'string';
    const TYPE_KEYWORD = 'keyword';
    const TYPE_OBJECT = 'object';
    const TYPE_NESTED = 'nested';
    
    /** @return string */
    public function getName();

    /** @return string */
    public function getType();
}
