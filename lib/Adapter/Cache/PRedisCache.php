<?php
/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache\Adapter\Cache;

use Predis\Client;
use Sonata\Cache\CacheAdapterInterface;
use Sonata\Cache\CacheElement;

class PRedisCache extends BaseCacheHandler
{
    protected $parameters;

    protected $options;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param array $parameters Connection parameters for one or more servers, passed to Predis\Client constructor
     * @param array $options    Options to configure some behaviours of the client.
     */
    public function __construct(array $parameters = array(), array $options = array())
    {
        // When an array of connections parameters is provided, Predis automatically
        // works in clustering mode using client-side sharding.
        // Don't trigger it unnecessarily if only one connection info is provided,
        // so e.g. FLUSHDB works correctly.
        $this->parameters = count($parameters) > 1 ? $parameters : array_shift($parameters);
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll()
    {
        // You cannot use 'FLUSHDB' over clusters of connections.
        if (is_a($this->getClient()->getConnection(), 'Predis\Connection\PredisCluster')) {
            return false;
        }

        /** @var $res \Predis\Response\Status **/
        $res = $this->getClient()->flushdb();
        return (bool)stristr($res->getPayload(), 'OK');
    }

    /**
     * {@inheritdoc}
     */
    public function flush(array $keys = array())
    {
        return $this->getClient()->del($this->computeCacheKeys($keys));
    }

    /**
     * {@inheritdoc}
     */
    public function has(array $keys)
    {
        return $this->getClient()->exists($this->computeCacheKeys($keys));
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        if (!$this->client) {
            $this->client = new Client($this->parameters, $this->options);
        }

        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function set(array $keys, $data, $ttl = 84600, array $contextualKeys = array())
    {
        $cacheElement = new CacheElement($keys, $data, $ttl);

        $key = $this->computeCacheKeys($keys);

        $this->getClient()->hset($key, "sonata__data", serialize($cacheElement));

        foreach ($contextualKeys as $name => $value) {
            if (!is_scalar($value)) {
                $value = serialize($value);
            }

            $this->getClient()->hset($key, $name, $value);
        }

        foreach ($keys as $name => $value) {
            if (!is_scalar($value)) {
                $value = serialize($value);
            }

            $this->getClient()->hset($key, $name, $value);
        }

        $this->getClient()->expire($key, $cacheElement->getTtl());

        return $cacheElement;
    }

    /**
     * {@inheritdoc}
     */
    private function computeCacheKeys(array $keys)
    {
        ksort($keys);

        return md5(serialize($keys));
    }

    /**
     * {@inheritdoc}
     */
    public function get(array $keys)
    {
        return $this->handleGet($keys, unserialize($this->getClient()->hget($this->computeCacheKeys($keys), "sonata__data")));
    }

    /**
     * {@inheritdoc}
     */
    public function isContextual()
    {
        return false;
    }
}