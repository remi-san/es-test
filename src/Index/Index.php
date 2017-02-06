<?php

namespace Evaneos\Elastic\Index;

use Evaneos\Elastic\Index\Exception\IndexException;
use Evaneos\Elastic\Index\Exception\TypeException;

interface Index
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function exists();

    /**
     * @throws IndexException
     */
    public function create();

    /**
     * @throws IndexException
     */
    public function delete();

    /**
     * @param string            $type
     * @param string            $id
     * @param \JsonSerializable $indexable
     *
     * @return string The id
     *
     * @throws TypeException
     */
    public function index($type, $id, \JsonSerializable $indexable);

    /**
     * @param string $type
     * @param string $id
     *
     * @throws TypeException
     */
    public function remove($type, $id);

    /**
     * @param string $type
     * @param string $id
     *
     * @return mixed
     *
     * @throws TypeException
     */
    public function get($type, $id);

    /**
     * @param string $type
     * @param array  $criteria
     *
     * @return array
     *
     * @throws TypeException
     */
    public function search($type, array $criteria);
}
