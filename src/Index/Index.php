<?php

namespace Evaneos\Elastic\Index;

interface Index
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function create();

    /**
     * @return array
     */
    public function delete();

    /**
     * @param string            $type
     * @param string            $id
     * @param \JsonSerializable $indexable
     *
     * @return mixed
     */
    public function index($type, $id, \JsonSerializable $indexable);

    /**
     * @param string $type
     * @param string $id
     *
     * @return mixed
     */
    public function remove($type, $id);

    /**
     * @param string $type
     * @param string $id
     *
     * @return mixed
     */
    public function get($type, $id);

    /**
     * @param string $type
     * @param array  $criteria
     *
     * @return mixed
     */
    public function search($type, array $criteria);
}
