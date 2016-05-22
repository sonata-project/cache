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

use Sonata\Cache\Invalidation\InvalidationInterface;
use Sonata\Cache\Invalidation\Recorder;

class CacheManager implements CacheManagerInterface
{
    /**
     * @var InvalidationInterface
     */
    protected $cacheInvalidation;

    /**
     * @var array
     */
    protected $cacheServices = [];

    /**
     * @var Recorder
     */
    protected $recorder;

    /**
     * Constructor.
     *
     * @param InvalidationInterface $cacheInvalidation A cache invalidation instance
     * @param array                 $cacheServices     An array of cache services
     */
    public function __construct(InvalidationInterface $cacheInvalidation, array $cacheServices)
    {
        $this->cacheInvalidation = $cacheInvalidation;
        $this->cacheServices = $cacheServices;
    }

    /**
     * {@inheritdoc}
     */
    public function addCacheService($name, CacheAdapterInterface $cacheManager)
    {
        $this->cacheServices[$name] = $cacheManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheService($name)
    {
        if (!$this->hasCacheService($name)) {
            throw new \RuntimeException(sprintf('The cache service %s does not exist.', $name));
        }

        return $this->cacheServices[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheServices()
    {
        return $this->cacheServices;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheService($id)
    {
        return isset($this->cacheServices[$id]) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate(array $keys)
    {
        $this->cacheInvalidation->invalidate($this->getCacheServices(), $keys);
    }

    /**
     * {@inheritdoc}
     */
    public function setRecorder(Recorder $recorder)
    {
        $this->recorder = $recorder;
    }

    /**
     * {@inheritdoc}
     */
    public function getRecorder()
    {
        return $this->recorder;
    }
}
