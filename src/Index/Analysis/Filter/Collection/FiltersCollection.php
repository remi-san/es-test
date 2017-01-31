<?php

namespace Evaneos\Elastic\Index\Analysis\Filter\Collection;

use Evaneos\Elastic\Index\Analysis\Filter;

class FiltersCollection implements \JsonSerializable
{
    /**
     * @var Filter[]
     */
    private $filters;

    /**
     * FiltersCollection constructor.
     */
    public function __construct()
    {
        $this->filters = [];
    }

    /**
     * @param Filter $filter
     *
     * @return $this
     */
    public function addFilter(Filter $filter)
    {
        $this->filters[$filter->getName()] = $filter;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->filters) === 0;
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
        return $this->filters;
    }
}
