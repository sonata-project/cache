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

use Sonata\Cache\Adapter\Cache\ApcCache;

class ApcCacheTest extends BaseTest
{
    protected function setUp(): void
    {
        if (!\function_exists('apc_store')) {
            static::markTestSkipped('APC is not installed');
        }

        if (0 === ini_get('apc.enable_cli')) {
            static::markTestSkipped('APC is not enabled in cli, please add apc.enable_cli=On into the apc.ini file');
        }

        apc_clear_cache();
        apc_clear_cache('user');
    }

    /**
     * @return ApcCache
     */
    public function getCache()
    {
        $cache = new ApcCache('http://localhost', 'prefix_', [], []);

        // force to clear the current apc instance (ie, ignore the servers informations)
        $cache->setCurrentOnly(true);

        return $cache;
    }
}
