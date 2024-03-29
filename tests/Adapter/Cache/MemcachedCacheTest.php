<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache\Tests\Adapter\Cache;

use Sonata\Cache\Adapter\Cache\MemcachedCache;

class MemcachedCacheTest extends BaseTest
{
    protected function setUp(): void
    {
        if (!class_exists('\Memcached', true)) {
            static::markTestSkipped('Memcached is not installed');
        }

        $socket = socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);

        // setup the default timeout (avoid max execution time)
        socket_set_option($socket, \SOL_SOCKET, \SO_SNDTIMEO, ['sec' => 1, 'usec' => 0]);

        $result = @socket_connect($socket, '127.0.0.1', 11211);

        if (!$result) {
            static::markTestSkipped('Memcached is not running');
        }

        socket_close($socket);

        $memcached = new \Memcached();
        $memcached->addServer('127.0.0.1', 11211);

        $memcached->fetchAll();
    }

    /**
     * @return MemcachedCache
     */
    public function getCache()
    {
        return new MemcachedCache('sonata_cache_test', [
            ['host' => '127.0.0.1', 'port' => 11211, 'weight' => 100],
        ]);
    }
}
