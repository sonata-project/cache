<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache\Tests\Cache\Invalidation;

use Sonata\Cache\Invalidation\SimpleCacheInvalidation;

class SimpleCacheInvalidationTest_Cache
{
}

class SimpleCacheInvalidationTest extends \PHPUnit_Framework_TestCase
{
    public function testInvalidate()
    {
        $cacheInvalidation = new SimpleCacheInvalidation();

        $cache = $this->getMock('Sonata\Cache\CacheAdapterInterface');
        $cache->expects($this->exactly(1))->method('flush');

        $caches = [$cache];

        $this->assertTrue($cacheInvalidation->invalidate($caches, ['test' => 1]));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testWithoutLogger()
    {
        $cacheInvalidation = new SimpleCacheInvalidation();

        $cache = $this->getMock('Sonata\Cache\CacheAdapterInterface');
        $cache->expects($this->exactly(1))->method('flush')->will($this->throwException(new \Exception()));

        $caches = [$cache];

        $cacheInvalidation->invalidate($caches, ['page_id' => 1]);
    }

    public function testWithLogger()
    {
        $logger = $this->getMock('Psr\Log\LoggerInterface', [], [], '', false);
        $logger->expects($this->exactly(1))->method('info');
        $logger->expects($this->exactly(1))->method('alert');

        $cacheInvalidation = new SimpleCacheInvalidation($logger);

        $cache = $this->getMock('Sonata\Cache\CacheAdapterInterface');
        $cache->expects($this->exactly(1))->method('flush')->will($this->throwException(new \Exception()));

        $caches = [$cache];

        $cacheInvalidation->invalidate($caches, ['page_id' => 1]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidCacheHandle()
    {
        $cacheInvalidation = new SimpleCacheInvalidation();

        $caches = [new SimpleCacheInvalidationTest_Cache()];

        $cacheInvalidation->invalidate($caches, ['page_id' => 1]);
    }
}
