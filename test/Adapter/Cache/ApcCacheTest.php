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

class ApcCacheTestextends extends BaseTest
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

    /**
     * @return ApcCache
     */
    public function getCache()
    {
        return new ApcCache('http://localhost', 'prefix_', array());
    }
}
