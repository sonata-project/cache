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

use Doctrine\Persistence\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;
use Sonata\Cache\CacheAdapterInterface;
use Sonata\Cache\Invalidation\DoctrinePHPCRODMListener;
use Sonata\Cache\Invalidation\ModelCollectionIdentifiers;

class DoctrinePHPCRODMListenerTest_Model
{
    public function getCacheIdentifier()
    {
        return '1';
    }
}

class DoctrinePHPCRODMListenerTest extends TestCase
{
    public function test(): void
    {
        $collection = new ModelCollectionIdentifiers();

        $listener = new DoctrinePHPCRODMListener($collection, []);

        $event = $this->createMock(LifecycleEventArgs::class, [], [], '', false);
        $event->expects($this->exactly(4))
            ->method('getObject')
            ->willReturn(new DoctrinePHPCRODMListenerTest_Model());

        $cache = $this->createMock(CacheAdapterInterface::class);
        $cache->expects($this->exactly(2))
            ->method('flush')
            ->willReturn(true);

        $cache->expects($this->exactly(1))
            ->method('isContextual')
            ->willReturn(true);

        $listener->addCache($cache);

        $listener->preUpdate($event);
        $listener->preRemove($event);
    }
}
