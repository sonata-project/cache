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
use Sonata\Cache\Adapter\Cache\MongoCache;
use Sonata\Cache\Adapter\Counter\MongoCounter;
use Sonata\Cache\Counter;

class MongoCounterTest extends TestCase
{
    protected function setUp(): void
    {
        $class = MongoCache::getMongoClass();

        static::markTestSkipped('Tests are not working');

        if (!class_exists($class, true)) {
            static::markTestSkipped('Mongo is not installed');
        }

        $socket = socket_create(\AF_INET, \SOCK_STREAM, \SOL_TCP);

        // setup the default timeout (avoid max execution time)
        socket_set_option($socket, \SOL_SOCKET, \SO_SNDTIMEO, ['sec' => 1, 'usec' => 0]);

        $result = @socket_connect($socket, '127.0.0.1', 27017);

        socket_close($socket);

        if (!$result) {
            static::markTestSkipped('MongoDB is not running');
        }

        $mongo = new $class('mongodb://127.0.0.1:27017');

        $mongo
            ->selectDB('sonata_counter_test')
            ->selectCollection('counter')
            ->remove([]);
    }

    public function testCounterBackend(): void
    {
        $backend = new MongoCounter(['127.0.0.1:27017'], 'sonata_counter_test', 'counter');

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
        $backend = new MongoCounter(['127.0.0.1:27017'], 'sonata_counter_test', 'counter');

        $counter = $backend->increment(Counter::create('mynewcounter.inc', 10));

        static::assertSame(11, $counter->getValue());

        $counter = $backend->decrement(Counter::create('mynewcounter.dec', 10));

        static::assertSame(9, $counter->getValue());
    }
}
