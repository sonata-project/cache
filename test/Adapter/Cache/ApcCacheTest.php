<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache\Tests\Adapter\Cache;

use Sonata\Cache\Adapter\Cache\ApcCache;
use Symfony\Component\Routing\RouterInterface;

class ApcCacheTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        if (!function_exists('apc_store')) {
            $this->markTestSkipped('APC is not installed');
        }

        if (ini_get('apc.enable_cli') == 0) {
            $this->markTestSkipped('APC is not enabled in cli, please add apc.enable_cli=On into the apc.ini file');
        }
    }

    public function testInitCache()
    {
        $router = $this->getMock('Symfony\Component\Routing\RouterInterface');

        $cache = new ApcCache($router, 'token', 'prefix_', array());

        $this->assertTrue($cache->flush(array()));
        $this->assertTrue($cache->flushAll());

        $cacheElement = $cache->set(array('id' => 7), 'data');

        $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);

        $this->assertTrue($cache->has(array('id' => 7)));

        $this->assertFalse($cache->has(array('id' => 8)));

        $cacheElement = $cache->get(array('id' => 7));

        $this->assertInstanceOf('Sonata\Cache\CacheElement', $cacheElement);
    }
}
