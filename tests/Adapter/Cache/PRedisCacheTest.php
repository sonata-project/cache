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

use Predis\Client;
use Predis\Connection\Aggregate\PredisCluster;
use Sonata\Cache\Adapter\Cache\PRedisCache;

class PRedisCacheTest extends BaseTest
{
    protected function setUp(): void
    {
        if (!class_exists('\Predis\Client', true)) {
            static::markTestSkipped('PRedis is not installed');
        }

        $socket = socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);

        // setup the default timeout (avoid max execution time)
        socket_set_option($socket, \SOL_SOCKET, \SO_SNDTIMEO, ['sec' => 1, 'usec' => 0]);

        $result = @socket_connect($socket, '127.0.0.1', 6379);

        if (!$result) {
            static::markTestSkipped('Redis is not running');
        }

        socket_close($socket);

        $client = new Client([
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 42,
        ]);

        $client->flushdb();
    }

    /**
     * @return PRedisCache
     */
    public function getCache()
    {
        return new PRedisCache([
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 42,
        ]);
    }

    /**
     * Tests the flushAll method when connection is a single one.
     */
    public function testFlushAllForSingleConnection(): void
    {
        $cache = $this->getMockBuilder('Sonata\Cache\Adapter\Cache\PRedisCache')
            ->setMethods(['getClient'])
            ->getMock();

        $command = $this->createMock('Predis\Command\CommandInterface');

        $client = $this->createMock('Predis\ClientInterface');
        $client->expects(static::exactly(2))->method('createCommand')->with(static::equalTo('flushdb'))->willReturn($command);
        $client->expects(static::exactly(2))->method('getConnection');
        $client->expects(static::exactly(2))->method('executeCommand')->with(static::equalTo($command))->will(static::onConsecutiveCalls(false, true));

        $cache->expects(static::exactly(6))->method('getClient')->willReturn($client);

        static::assertFalse($cache->flushAll());
        static::assertTrue($cache->flushAll());
    }

    /**
     * Tests the flushAll method when connection is a cluster one.
     */
    public function testFlushAllForClusterConnection(): void
    {
        $cache = $this->getMockBuilder('Sonata\Cache\Adapter\Cache\PRedisCache')
            ->setMethods(['getClient'])
            ->getMock();

        $command = $this->createMock('Predis\Command\CommandInterface');

        $connection = $this->createMock(PredisCluster::class);
        $connection->expects(static::exactly(5))->method('executeCommandOnNodes')->with(static::equalTo($command))->will(static::onConsecutiveCalls([false], [true], [false, true], [true, false], [true, true]));

        $client = $this->createMock('Predis\ClientInterface');
        $client->expects(static::exactly(5))->method('createCommand')->with(static::equalTo('flushdb'))->willReturn($command);
        $client->expects(static::exactly(5))->method('getConnection')->willReturn($connection);
        $client->expects(static::never())->method('executeCommand');

        $cache->expects(static::exactly(10))->method('getClient')->willReturn($client);

        static::assertFalse($cache->flushAll());
        static::assertTrue($cache->flushAll());
        static::assertFalse($cache->flushAll());
        static::assertFalse($cache->flushAll());
        static::assertTrue($cache->flushAll());
    }
}
