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

use PHPUnit\Framework\TestCase;
use Sonata\Cache\Invalidation\DoctrineORMListener;
use Sonata\Cache\Invalidation\ModelCollectionIdentifiers;

class DoctrineORMListenerTest_Model
{
    public function getCacheIdentifier()
    {
        return '1';
    }
}

class DoctrineORMListenerTest extends TestCase
{
    public function test(): void
    {
        $collection = new ModelCollectionIdentifiers();

        $listener = new DoctrineORMListener($collection, []);

        $event = $this->createMock('Doctrine\ORM\Event\LifecycleEventArgs');
        $event->expects($this->exactly(4))
            ->method('getEntity')
            ->will($this->returnValue(new DoctrineORMListenerTest_Model()));

        $cache = $this->createMock('Sonata\Cache\CacheAdapterInterface');
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
