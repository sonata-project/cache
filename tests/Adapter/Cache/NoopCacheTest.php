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
use Sonata\Cache\Adapter\Cache\NoopCache;

class NoopCacheTest extends TestCase
{
    public function testNoopCache(): void
    {
        $cache = new NoopCache();

        $this->assertTrue($cache->flush([]));
        $this->assertTrue($cache->flushAll());
        $this->assertFalse($cache->has([]));
        $this->assertFalse($cache->has([]));
    }

    public function getGet(): void
    {
        $this->expectException(\RuntimeException::class);

        $cache = new NoopCache();
        $cache->get([]);
    }
}
