<?php

namespace Evaneos\Elastic\Index;

use Assert\AssertionFailedException;
use Elasticsearch\Client;

abstract class AbstractIndex implements Index
{
    /** @var Client */
    private $client;

    /** @var Definition */
    private $definition;

    /**
     * LanguageIndex constructor.
     *
     * @param Client $client
     * @param string $name
     * @param string $language
     *
     * @throws AssertionFailedException
     */
    public function __construct(Client $client, $name, $language)
    {
        $this->client = $client;
        $this->definition = $this->getDefinition($name, $language);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->definition->getName();
    }

    /**
     * @return array
     */
    public function create()
    {
        return $this->client
            ->indices()
            ->create([
                 'index' => $this->getName(),
                 'body' => json_encode($this->definition)
             ]);
    }

    /**
     * @return array
     */
    public function delete()
    {
        return $this->client
            ->indices()
            ->delete([
                 'index' => $this->getName()
             ]);
    }

    /**
     * @param string            $type
     * @param string            $id
     * @param \JsonSerializable $indexable
     *
     * @return mixed
     */
    public function index($type, $id, \JsonSerializable $indexable)
    {
        return $this->client->index([
            'index' => $this->getName(),
            'type' => $type,
            'id' => (string) $id,
            'body' => json_encode($indexable)
        ]);
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return mixed
     */
    public function remove($type, $id)
    {
        return $this->client->delete([
            'index' => $this->getName(),
            'type' => $type,
            'id' => (string) $id
        ]);
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return mixed
     */
    public function get($type, $id)
    {
        return $this->client->get([
            'index' => $this->getName(),
            'type' => $type,
            'id' => (string) $id
        ]);
    }

    /**
     * @param string $type
     * @param array  $criteria
     *
     * @return mixed
     */
    public function search($type, array $criteria)
    {
        $searchReq = [
            'index' => $this->getName(),
            'type' => $type,
            'body' => json_encode($criteria)
        ];

        return $this->client->search($searchReq);
    }

    /**
     * @param string $name
     * @param string $language
     *
     * @return Definition
     *
     * @throws AssertionFailedException
     */
    abstract protected function getDefinition($name, $language);
}
