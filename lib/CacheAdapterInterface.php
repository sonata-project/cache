<?php

/*
 * This file is part of the Sonata package.
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
     * Gets data from cache
     *
     * @param array $keys
     *
     * @return CacheElement
     */
    function get(array $keys);

    /**
     * Returns TRUE whether cache contains data identified by keys
     *
     * @param array $keys
     *
     * @return boolean
     */
    function has(array $keys);

    /**
     * Sets value in cache
     *
     * @param array   $keys           An array of keys
     * @param mixed   $value          Value to store
     * @param integer $ttl            A time to live, default 84600 seconds
     * @param array   $contextualKeys An array of contextual keys
     *
     * @return CacheElement
     */
    function set(array $keys, $value, $ttl = 86400, array $contextualKeys = array());

    /**
     * Flushes data from cache identified by keys
     *
     * @param array $keys
     *
     * @return boolean
     */
    function flush(array $keys = array());

    /**
     * Flushes all data from cache
     *
     * @return boolean
     */
    function flushAll();

    /**
     * Returns TRUE whether cache is contextual
     *
     * @return boolean
     */
    function isContextual();
}
