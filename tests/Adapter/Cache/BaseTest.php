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
use Sonata\Cache\CacheAdapterInterface;
use Sonata\Cache\CacheElement;

abstract class BaseTest extends TestCase
{
    /**
     * @return CacheAdapterInterface
     */
    abstract public function getCache();

    public function testBasicOperations(): void
    {
        // init cache
        $cache = $this->getCache();
        $cacheElement = $cache->set(['id' => 7], 'data');
        static::assertInstanceOf(CacheElement::class, $cacheElement);
        static::assertTrue($cache->has(['id' => 7]));

        // test flush
        $cache->set(['id' => 42], 'data');
        static::assertTrue($cache->has(['id' => 42]));

        $res = $cache->flush(['id' => 42]);
        static::assertTrue($res);
        static::assertFalse($cache->has(['id' => 42]));

        $cacheElement = $cache->get(['id' => 7]);
        static::assertInstanceOf(CacheElement::class, $cacheElement);

        // test flush all
        $res = $cache->flushAll();
        static::assertTrue($res);
        static::assertFalse($cache->has(['id' => 7]));
    }

    public function testNonExistantCache(): void
    {
        $cache = $this->getCache();

        $cacheElement = $cache->get(['invalid']);

        static::assertInstanceOf(CacheElement::class, $cacheElement);
        static::assertTrue($cacheElement->isExpired());
    }

    public function testExpired(): void
    {
        $cache = $this->getCache();

        $cache->set(['expired'], 'hello', 1);

        sleep(2);

        $cacheElement = $cache->get(['mykey']);

        static::assertInstanceOf(CacheElement::class, $cacheElement);
        static::assertTrue($cacheElement->isExpired());
        static::assertNull($cacheElement->getData());
    }
}
