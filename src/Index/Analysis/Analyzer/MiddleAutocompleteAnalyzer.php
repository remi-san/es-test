<?php

namespace Evaneos\Elastic\Index\Analysis\Analyzer;

use Evaneos\Elastic\Index\Analysis\Filter\NGramFilter;
use Evaneos\Elastic\Index\Analysis\Filter;
use Evaneos\Elastic\Index\Analysis\Tokenizer;

class MiddleAutocompleteAnalyzer extends AbstractAnalyzer
{
    const NAME = 'middle_autocomplete_analyzer';

    /**
     * MiddleAutocompleteAnalyzer constructor.
     */
    public function __construct()
    {
        parent::__construct(
            self::NAME,
            self::TYPE_CUSTOM,
            [ Filter::TYPE_LOWER_CASE, Filter::TYPE_ASCII_FOLDING, NGramFilter::NAME ],
            Tokenizer::TYPE_STANDARD
        );
    }
}
