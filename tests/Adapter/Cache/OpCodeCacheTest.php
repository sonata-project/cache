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
use Sonata\Cache\Adapter\Cache\OpCodeCache;

class OpCodeCacheTest extends TestCase
{
    /**
     * @var OpCodeCache
     */
    protected $cache;

    public function setUp(): void
    {
        $this->cache = new OpCodeCache('http://localhost', 'prefix_', [], []);
        $this->cache->setCurrentOnly(true);
    }

    public function testFlushAll(): void
    {
        $res = $this->cache->flushAll();
        $this->assertTrue($res);
    }
}
