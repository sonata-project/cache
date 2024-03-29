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

namespace Sonata\Cache\Tests\Cache;

use PHPUnit\Framework\TestCase;
use Sonata\Cache\CacheElement;

class CacheElementTest extends TestCase
{
    public function testCache(): void
    {
        $cacheKeys = [
          'block_id' => '1',
        ];

        $cache = new CacheElement($cacheKeys, 'data', 20);

        static::assertSame(20, $cache->getTtl());
        static::assertSame($cacheKeys, $cache->getKeys());
        static::assertFalse($cache->isExpired());

        $cache = new CacheElement($cacheKeys, 'data', -1);
        static::assertTrue($cache->isExpired());

        static::assertSame('data', $cache->getData());

        $cache->getExpirationDate();
    }

    public function testContextual(): void
    {
        $cacheKeys = [
          'block_id' => '1',
        ];

        $cache = new CacheElement($cacheKeys, 'data', CacheElement::DAY, ['foo' => 'bar']);

        static::assertSame(['foo' => 'bar'], $cache->getContextualKeys());
    }
}
