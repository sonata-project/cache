<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache\Tests\Adapter\Cache;

use Sonata\Cache\Adapter\Cache\OpCodeCache;

class OpCodeCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OpCodeCache
     */
    protected $cache;

    public function setUp()
    {
        $this->cache = new OpCodeCache('http://localhost', 'prefix_', array(), array());
        $this->cache->setCurrentOnly(true);

        if ($this->checkApc()) {
            apc_clear_cache();
            apc_clear_cache('user');
        }
    }

    public function testBasicApcOperations()
    {
        if (!$this->checkApc()) {
            $this->setExpectedException('Sonata\Cache\Exception\UnsupportedException', 'Sonata\Cache\Adapter\Cache\OpCodeCache does not support data caching. you should install APC or APCu to use it');
            $this->cache->set(array('id' => 7), 'data');
        } else {
            $cacheElement = $this->cache->set(array('id' => 7), 'data');
            $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);
        }

        if (!$this->checkApc()) {
            $this->setExpectedException('Sonata\Cache\Exception\UnsupportedException', 'Sonata\Cache\Adapter\Cache\OpCodeCache does not support data caching. you should install APC or APCu to use it');
            $this->cache->get(array('id' => 7));
        } else {
            $cacheElement = $this->cache->get(array('id' => 7));
            $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);
        }

        if ($this->checkApc()) {
            $this->cache->set(array('id'          => 7), 'data');
            $res = $this->cache->flush(array('id' => 7));
            $this->assertTrue(true === $res);
            $this->assertFalse($this->cache->has(array('id' => 7)));
            $res = $this->cache->flushAll();
            $this->assertTrue(true === $res); // make sure it's really boolean TRUE
            $this->assertFalse($this->cache->has(array('id' => 7)));
        }
    }

    public function testNonExistantCache()
    {
        if ($this->checkApc()) {
            $cacheElement = $this->cache->get(array('invalid'));
            $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);
            $this->assertTrue($cacheElement->isExpired());
        }
    }

    public function testExpired()
    {
        if ($this->checkApc()) {
            $this->cache->set(array('expired'), 'hello', 1);
            sleep(2);
            $cacheElement = $this->cache->get(array('mykey'));
            $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);
            $this->assertTrue($cacheElement->isExpired());
            $this->assertNull($cacheElement->getData());
        }
    }

    /**
     * Call checkApc method.
     *
     * @return mixed
     */
    private function checkApc()
    {
        return extension_loaded('apc') && ini_get('apc.enabled');
    }
}
