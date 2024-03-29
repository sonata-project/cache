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

namespace Sonata\Cache\Tests\Invalidation;

use PHPUnit\Framework\TestCase;
use Sonata\Cache\Invalidation\ModelCollectionIdentifiers;
use Sonata\Cache\Invalidation\Recorder;

class Recorder_Model_1
{
    public function getCacheIdentifier()
    {
        return 1;
    }
}

class Recorder_Model_2
{
    public function getId()
    {
        return 2;
    }
}

class Recorder_Model_3
{
    public function getId()
    {
        return 3;
    }
}

class RecorderTest extends TestCase
{
    public function testRecorder(): void
    {
        $collection = new ModelCollectionIdentifiers([
            'Sonata\Cache\Tests\Invalidation\Recorder_Model_1' => 'getCacheIdentifier',
        ]);

        $m1 = new Recorder_Model_1();
        $m2 = new Recorder_Model_2();
        $m3 = new Recorder_Model_3();
        $recorder = new Recorder($collection);

        $recorder->push();

        $recorder->add($m1);

        $recorder->push();
        $recorder->add($m1);
        $recorder->add($m2);
        $recorder->add($m2);

        $innerKeys = $recorder->pop();
        $recorder->add($m3);

        $keys = $recorder->pop();

        static::assertArrayHasKey('Sonata\Cache\Tests\Invalidation\Recorder_Model_1', $innerKeys);
        static::assertArrayHasKey('Sonata\Cache\Tests\Invalidation\Recorder_Model_2', $innerKeys);
        static::assertArrayHasKey('Sonata\Cache\Tests\Invalidation\Recorder_Model_1', $keys);
        static::assertArrayHasKey('Sonata\Cache\Tests\Invalidation\Recorder_Model_3', $keys);
    }
}
