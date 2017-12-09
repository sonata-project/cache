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

namespace Sonata\Cache\Tests\Counter;

use PHPUnit\Framework\TestCase;
use Sonata\Cache\Counter;

class CounterTest extends TestCase
{
    public function testInvalidValue(): void
    {
        $this->expectException(\TypeError::class);

        Counter::create('value', 'data');
    }

    public function testClass(): void
    {
        $counter = Counter::create('mycounter', 42);

        $this->assertEquals('mycounter', $counter->getName());
        $this->assertEquals(42, $counter->getValue());
    }
}
