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

use Sonata\Cache\Adapter\Cache\Psr6Cache;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class Psr6CacheTest extends BaseTest
{
    public function getCache()
    {
        return new Psr6Cache(new ArrayAdapter());
    }
}
