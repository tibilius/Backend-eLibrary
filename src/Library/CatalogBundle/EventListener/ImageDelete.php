<?php

namespace Library\CatalogBundle\EventListener;

use Library\CatalogBundle\Entity\Books;
use Library\UserBundle\Entity\User;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Vich\UploaderBundle\Event\Event;

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
     * @param LoggerInterface $logger
     * @param CacheManager $cacheManager
     */
    public function __construct(LoggerInterface $logger, CacheManager $cacheManager)
    {
        $this->logger = $logger;
        $this->cacheManager = $cacheManager;
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