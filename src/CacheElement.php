<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Cache;

final class CacheElement
{
    const MINUTE = 60;
    const HOUR = 3600;
    const DAY = 86400;
    const WEEK = 604800;
    const MONTH = 2.63e+6;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var array
     */
    private $keys = array();

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var array
     */
    private $contextualKeys = array();

    /**
     * Constructor.
     *
     * @param array $keys           An array of keys
     * @param mixed $data           Data
     * @param int   $ttl            A time to live, default 86400 seconds (CacheElement::DAY)
     * @param array $contextualKeys An array of contextual keys
     */
    public function __construct(array $keys, $data, int $ttl = self::DAY, array $contextualKeys = array())
    {
        $this->createdAt = new \DateTime();
        $this->keys = $keys;
        $this->ttl = $ttl;
        $this->data = $data;
        $this->contextualKeys = $contextualKeys;
    }

    /**
     * Returns the keys.
     *
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * Returns the time to live.
     *
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * Returns the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns TRUE whether the cache is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return strtotime('now') > ($this->createdAt->format('U') + $this->ttl);
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate(): \DateTime
    {
        if ($this->isExpired()) {
            return new \DateTime();
        }

        $date = clone $this->createdAt;
        $date = $date->add(new \DateInterval(sprintf('PT%sS', $this->ttl)));

        return $date;
    }

    /**
     * Returns the contextual keys.
     *
     * @return array
     */
    public function getContextualKeys(): array
    {
        return $this->contextualKeys;
    }
}
