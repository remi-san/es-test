<?php

namespace Evaneos\Elastic\Indexing;

use Assert\AssertionFailedException;
use Evaneos\Elastic\Index\AbstractIndex;
use Evaneos\Elastic\Index\Analysis;
use Evaneos\Elastic\Index\Analysis\Analyzer\MiddleAutocompleteAnalyzer;
use Evaneos\Elastic\Index\Analysis\Analyzer\PrefixAutocompleteAnalyzer;
use Evaneos\Elastic\Index\Analysis\Filter\EdgeNGramFilter;
use Evaneos\Elastic\Index\Analysis\Filter\NGramFilter;
use Evaneos\Elastic\Index\Definition;
use Evaneos\Elastic\Index\Mapping;
use Evaneos\Elastic\Index\Mapping\Collection\MappingsCollection;
use Evaneos\Elastic\Index\Mapping\Property;
use Evaneos\Elastic\Index\Mapping\Property\Collection\PropertiesCollection;
use Evaneos\Elastic\Index\Mapping\Property\NestedProperty;
use Evaneos\Elastic\Index\Mapping\Property\ObjectProperty;
use Evaneos\Elastic\Index\Mapping\Property\SimpleProperty;

class LanguageIndex extends AbstractIndex
{
    /**
     * @param string $name
     * @param string $languageAnalyzer
     *
     * @return Definition
     *
     * @throws AssertionFailedException
     */
    protected function getDefinition($name, $languageAnalyzer)
    {
        $analysis = $this->getAnalyzer();
        $mappings = (new MappingsCollection())->addMapping($this->getPlaceMapping($languageAnalyzer));

        return new Definition($name, $analysis, $mappings);
    }

    /**
     * @return Analysis
     */
    private function getAnalyzer()
    {
        return (new Analysis())
            ->addFilter(new EdgeNGramFilter(2, 10))
            ->addFilter(new NGramFilter(3, 10))
            ->addAnalyzer(new MiddleAutocompleteAnalyzer())
            ->addAnalyzer(new PrefixAutocompleteAnalyzer());
    }

    /**
     * @param $languageAnalyzer
     *
     * @return Mapping
     *
     * @throws AssertionFailedException
     */
    private function getPlaceMapping($languageAnalyzer)
    {
        return (new Mapping('place'))
            ->addProperty(
                (new SimpleProperty('name', Property::TYPE_STRING, $languageAnalyzer))
                    ->addField(
                        new SimpleProperty(
                            'prefix_autocomplete',
                            Property::TYPE_STRING,
                            PrefixAutocompleteAnalyzer::NAME
                        )
                    )
                    ->addField(
                        new SimpleProperty(
                            'middle_autocomplete',
                            Property::TYPE_STRING,
                            MiddleAutocompleteAnalyzer::NAME
                        )
                    )
            )
            ->addProperty(new SimpleProperty('country', Property::TYPE_KEYWORD))
            ->addProperty(
                new NestedProperty(
                    'type',
                    (new PropertiesCollection())
                        ->addProperty(new SimpleProperty('key', Property::TYPE_KEYWORD))
                        ->addProperty(new SimpleProperty('name', Property::TYPE_STRING))
                )
            )
            ->addProperty(
                new ObjectProperty(
                    'hierarchy',
                    (new PropertiesCollection())
                        ->addProperty(new SimpleProperty('parent', Property::TYPE_KEYWORD))
                        ->addProperty(new SimpleProperty('grandParent', Property::TYPE_KEYWORD))
                )
            );
    }
}
