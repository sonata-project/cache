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

namespace Sonata\Cache\Tests\Cache\Invalidation;

use PHPUnit\Framework\TestCase;
use Sonata\Cache\Invalidation\SimpleCacheInvalidation;

class SimpleCacheInvalidationTest_Cache
{
}

class SimpleCacheInvalidationTest extends TestCase
{
    public function testInvalidate(): void
    {
        $cacheInvalidation = new SimpleCacheInvalidation();

        $cache = $this->createMock('Sonata\Cache\CacheAdapterInterface');
        $cache->expects($this->exactly(1))->method('flush');

        $caches = [$cache];

        $this->assertTrue($cacheInvalidation->invalidate($caches, ['test' => 1]));
    }

    public function testWithoutLogger(): void
    {
        $this->expectException(\RuntimeException::class);

        $cacheInvalidation = new SimpleCacheInvalidation();

        $cache = $this->createMock('Sonata\Cache\CacheAdapterInterface');
        $cache->expects($this->exactly(1))->method('flush')->will($this->throwException(new \Exception()));

        $caches = [$cache];

        $cacheInvalidation->invalidate($caches, ['page_id' => 1]);
    }

    public function testWithLogger(): void
    {
        $logger = $this->createMock('Psr\Log\LoggerInterface');
        $logger->expects($this->exactly(1))->method('info');
        $logger->expects($this->exactly(1))->method('alert');

        $cacheInvalidation = new SimpleCacheInvalidation($logger);

        $cache = $this->createMock('Sonata\Cache\CacheAdapterInterface');
        $cache->expects($this->exactly(1))->method('flush')->will($this->throwException(new \Exception()));

        $caches = [$cache];

        $cacheInvalidation->invalidate($caches, ['page_id' => 1]);
    }

    public function testInvalidCacheHandle(): void
    {
        $this->expectException(\RuntimeException::class);

        $cacheInvalidation = new SimpleCacheInvalidation();

        $caches = [new SimpleCacheInvalidationTest_Cache()];

        $cacheInvalidation->invalidate($caches, ['page_id' => 1]);
    }
}
