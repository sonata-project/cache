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

use Sonata\Cache\Invalidation\DoctrinePHPCRODMListener;
use Sonata\Cache\Invalidation\ModelCollectionIdentifiers;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Sonata\Cache\CacheAdapterInterface;

class DoctrinePHPCRODMListenerTest_Model
{
    public function getCacheIdentifier()
    {
        return '1';
    }
}

class DoctrinePHPCRODMListenerTest extends \PHPUnit_Framework_TestCase
{
    public function test(): void
    {
        $collection = new ModelCollectionIdentifiers();

        $listener = new DoctrinePHPCRODMListener($collection, []);

        $event = $this->createMock(LifecycleEventArgs::class, [], [], '', false);
        $event->expects($this->exactly(4))
            ->method('getObject')
            ->will($this->returnValue(new DoctrinePHPCRODMListenerTest_Model()));

        $cache = $this->createMock(CacheAdapterInterface::class);
        $cache->expects($this->exactly(2))
            ->method('flush')
            ->will($this->returnValue(true));

        $cache->expects($this->exactly(1))
            ->method('isContextual')
            ->will($this->returnValue(true));

        $listener->addCache($cache);

        $listener->preUpdate($event);
        $listener->preRemove($event);
    }
}
