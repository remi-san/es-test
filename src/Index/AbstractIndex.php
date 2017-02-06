<?php

namespace Evaneos\Elastic\Index;

use Assert\AssertionFailedException;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Evaneos\Elastic\Index\Exception\IndexException;
use Evaneos\Elastic\Index\Exception\TypeException;

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
     * @return bool
     */
    public function exists()
    {
        return $this->client->indices()->exists([ 'index' => $this->getName() ]);
    }

    /**
     * @throws IndexException
     */
    public function create()
    {
        try {
            $this->client
                ->indices()
                ->create([
                    'index' => $this->getName(),
                    'body' => json_encode($this->definition)
                ]);
        } catch (BadRequest400Exception $e) {
            throw IndexException::indexCreationFailed($e);
        }
    }

    /**
     * @throws IndexException
     */
    public function delete()
    {
        try {
            $this->client
                ->indices()
                ->delete([ 'index' => $this->getName() ]);
        } catch (BadRequest400Exception $e) {
            throw IndexException::indexDeletionFailed($e);
        }
    }

    /**
     * @param string            $type
     * @param string            $id
     * @param \JsonSerializable $indexable
     *
     * @return string The id
     *
     * @throws TypeException
     */
    public function index($type, $id, \JsonSerializable $indexable)
    {
        try {
            return $this->client->index([
                'index' => $this->getName(),
                'type' => $type,
                'id' => (string) $id,
                'body' => json_encode($indexable)
            ])['_id'];
        } catch (BadRequest400Exception $e) {
            throw TypeException::indexingFailed($e);
        }
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @throws TypeException
     */
    public function remove($type, $id)
    {
        try {
            $this->client->delete([
                'index' => $this->getName(),
                'type' => $type,
                'id' => (string) $id
            ]);
        } catch (BadRequest400Exception $e) {
            throw TypeException::removingFromIndexFailed($e);
        }
    }

    /**
     * @param string $type
     * @param string $id
     *
     * @return mixed
     *
     * @throws TypeException
     */
    public function get($type, $id)
    {
        try {
            return $this->client->get([
                'index' => $this->getName(),
                'type' => $type,
                'id' => (string) $id
            ]);
        } catch (Missing404Exception $e) {
            return null;
        } catch (BadRequest400Exception $e) {
            throw TypeException::retrievingFailed($e);
        }
    }

    /**
     * @param string $type
     * @param array  $criteria
     *
     * @return array
     *
     * @throws TypeException
     */
    public function search($type, array $criteria)
    {
        $searchReq = [
            'index' => $this->getName(),
            'type' => $type,
            'body' => json_encode($criteria)
        ];

        try {
            return $this->client->search($searchReq);
        } catch (BadRequest400Exception $e) {
            throw TypeException::retrievingFailed($e);
        }
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
