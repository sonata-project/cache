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

use PHPUnit\Framework\TestCase;
use Predis\Client;
use Sonata\Cache\Adapter\Counter\PRedisCounter;
use Sonata\Cache\Counter;

class PRedisCounterTest extends TestCase
{
    protected $parameters = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'database' => 42,
    ];

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

        $client = new Client($this->parameters);

        $client->flushdb();
    }

    public function testCounterBackend(): void
    {
        $backend = new PRedisCounter($this->parameters);

        $counter = $backend->set(Counter::create('mycounter', 10));

        static::assertInstanceOf('Sonata\Cache\Counter', $counter);
        static::assertSame(10, $counter->getValue());
        static::assertSame('mycounter', $counter->getName());

        $counter = $backend->get('mycounter');
        static::assertInstanceOf('Sonata\Cache\Counter', $counter);
        static::assertSame(10, $counter->getValue());
        static::assertSame('mycounter', $counter->getName());

        $counter = $backend->increment($counter);
        static::assertSame(11, $counter->getValue());

        $counter = $backend->increment($counter, 10);
        static::assertSame(21, $counter->getValue());

        $counter = $backend->decrement($counter);
        static::assertSame(20, $counter->getValue());

        $counter = $backend->decrement($counter, 30);

        static::assertSame(-10, $counter->getValue());
    }

    public function testNonExistantKey(): void
    {
        $backend = new PRedisCounter($this->parameters);

        $counter = $backend->increment(Counter::create('mynewcounter.inc', 10));

        static::assertSame(11, $counter->getValue());

        $counter = $backend->decrement(Counter::create('mynewcounter.dec', 10));

        static::assertSame(9, $counter->getValue());
    }
}
