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

namespace Sonata\Cache\Adapter\Cache;

use Psr\Cache\CacheItemPoolInterface;
use Sonata\Cache\CacheElement;
use Sonata\Cache\CacheElementInterface;

final class Psr6Cache extends BaseCacheHandler
{
    /**
     * @var CacheItemPoolInterface
     */
    private $psr6Pool;

    public function __construct(CacheItemPoolInterface $psr6Pool)
    {
        $this->psr6Pool = $psr6Pool;
    }

    public function get(array $keys): CacheElementInterface
    {
        $psr6Item = $this->psr6Pool->getItem($this->computeCacheKey($keys));

        return $this->handleGet($keys, $psr6Item->get());
    }

    public function has(array $keys): bool
    {
        return $this->psr6Pool->hasItem($this->computeCacheKey($keys));
    }

    public function set(array $keys, $value, int $ttl = CacheElement::DAY, array $contextualKeys = []): CacheElementInterface
    {
        $cacheElement = new CacheElement($keys, $value, $ttl);

        $psr6Item = $this->psr6Pool->getItem($this->computeCacheKey($keys));
        $psr6Item->set($cacheElement);
        $psr6Item->expiresAfter($ttl);
        $this->psr6Pool->save($psr6Item);

        return $cacheElement;
    }

    public function flush(array $keys = []): bool
    {
        return $this->psr6Pool->deleteItem($this->computeCacheKey($keys));
    }

    public function flushAll(): bool
    {
        return $this->psr6Pool->clear();
    }

    public function isContextual(): bool
    {
        return false;
    }

    private function computeCacheKey(array $keys): string
    {
        ksort($keys);

        return md5(serialize($keys));
    }
}
