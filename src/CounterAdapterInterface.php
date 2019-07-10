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

namespace Sonata\Cache;

interface CounterAdapterInterface
{
    /**
     * @param Counter|string $counter
     */
    public function increment(Counter $counter, int $number = 1): Counter;

    /**
     * @param Counter|string $counter
     */
    public function decrement(Counter $counter, int $number = 1): Counter;

    public function set(Counter $counter): Counter;

    public function get(string $name): Counter;
}
