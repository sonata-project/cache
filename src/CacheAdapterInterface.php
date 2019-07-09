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

interface CacheAdapterInterface
{
    /**
     * Gets data from cache.
     */
    public function get(array $keys): CacheElementInterface;

    /**
     * Returns TRUE whether cache contains data identified by keys.
     */
    public function has(array $keys): bool;

    /**
     * Sets value in cache.
     *
     * @param array $keys           An array of keys
     * @param mixed $value          Value to store
     * @param int   $ttl            A time to live, default 86400 seconds (CacheElement::DAY)
     * @param array $contextualKeys An array of contextual keys
     */
    public function set(array $keys, $value, int $ttl = CacheElement::DAY, array $contextualKeys = []): CacheElementInterface;

    /**
     * Flushes data from cache identified by keys.
     */
    public function flush(array $keys = []): bool;

    /**
     * Flushes all data from cache.
     */
    public function flushAll(): bool;

    /**
     * Returns TRUE whether cache is contextual.
     */
    public function isContextual(): bool;
}
