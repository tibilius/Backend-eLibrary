<?php

namespace Library\CatalogBundle\EventListener;

use Library\CatalogBundle\Entity\Books;
use Library\UserBundle\Entity\User;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Storage\StorageInterface;

class ImageDelete
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var StorageInterface
     */
    protected $vichStorage;

    /**
     * @param LoggerInterface $logger
     * @param CacheManager $cacheManager
     */

    public function __construct(LoggerInterface $logger, CacheManager $cacheManager, StorageInterface $vichStorage)
    {
        $this->logger = $logger;
        $this->cacheManager = $cacheManager;
        $this->vichStorage = $vichStorage;
    }

    public function onImageRemoved(Event $event)
    {
        if ($event->getObject() instanceof User) {
            $path = $this->vichStorage->resolveUri($event->getObject(), 'avatarImage');
            if ($this->cacheManager->isStored($path, 'small')) {
                $this->cacheManager->remove([$path], ['small']);
            }
            return;
        }
        if ($event->getObject() instanceof Books) {
            $path = $this->vichStorage->resolveUri($event->getObject(), 'file');
            if ($this->cacheManager->isStored($path, 'my_thumb')) {
                $this->cacheManager->remove([$path], ['my_thumb']);
            }
            return;

        }
    }
}