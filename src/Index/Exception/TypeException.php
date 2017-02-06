<?php

namespace Evaneos\Elastic\Index\Exception;

class TypeException extends IndexException
{
    /**
     * @param \Exception $previous
     *
     * @return TypeException
     */
    public static function indexingFailed(\Exception $previous = null)
    {
        return new self('Could not index', 0, $previous);
    }

    /**
     * @param \Exception $previous
     *
     * @return TypeException
     */
    public static function removingFromIndexFailed(\Exception $previous = null)
    {
        return new self('Could not remove from index', 0, $previous);
    }

    /**
     * @param \Exception $previous
     *
     * @return TypeException
     */
    public static function retrievingFailed(\Exception $previous = null)
    {
        return new self('Could not retrieve from index', 0, $previous);
    }
}
