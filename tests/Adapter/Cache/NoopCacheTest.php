<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache\Tests\Adapter\Cache;

use Sonata\Cache\Adapter\Cache\NoopCache;

class NoopCacheTest extends \PHPUnit_Framework_TestCase
{
    public function testNoopCache()
    {
        $cache = new NoopCache();

        $this->assertTrue($cache->flush([]));
        $this->assertTrue($cache->flushAll());
        $this->assertFalse($cache->has([]));
        $this->assertFalse($cache->has([]));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function getGet()
    {
        $cache = new NoopCache();
        $cache->get([]);
    }
}
