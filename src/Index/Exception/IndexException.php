<?php

namespace Evaneos\Elastic\Index\Exception;

class IndexException extends \RuntimeException
{
    /**
     * @param \Exception $previous
     *
     * @return IndexException
     */
    public static function indexCreationFailed(\Exception $previous = null)
    {
        return new self('Could not create index', 0, $previous);
    }

    /**
     * @param \Exception $previous
     *
     * @return IndexException
     */
    public static function indexDeletionFailed(\Exception $previous = null)
    {
        return new self('Could not delete index', 0, $previous);
    }
}
