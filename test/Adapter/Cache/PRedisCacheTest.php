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

use Sonata\Cache\Adapter\Cache\PRedisCache;
use Predis\Client;

class PRedisCacheTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!class_exists('\Predis\Client', true)) {
            $this->markTestSkipped('PRedis is not installed');
        }

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        // setup the default timeout (avoid max execution time)
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));

        $result = @socket_connect($socket, '127.0.0.1', 6379);

        if (!$result) {
            $this->markTestSkipped('Redis is not running');
        }

        socket_close($socket);

        $client = new Client(array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 42
        ));

        $client->flushdb();
    }

    public function testInitCache()
    {
        $cache = new PRedisCache(array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 42
        ));

        $cache->set(array('id' => 7), 'data');
        $cacheElement = $cache->set(array('id' => 42), 'data');

        $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);

        $this->assertTrue($cache->has(array('id' => 7)));

        $cache->flush(array('id' => 42));

        $this->assertFalse($cache->has(array('id' => 42)));

        $cacheElement = $cache->get(array('id' => 7));

        $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);

        $cache->flushAll();

        $this->assertFalse($cache->has(array('id' => 7)));
    }
}