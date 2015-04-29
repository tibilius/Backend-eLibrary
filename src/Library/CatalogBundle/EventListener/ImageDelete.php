<?php

namespace Library\CatalogBundle\EventListener;

use Psr\Log\LoggerInterface;
use Vich\UploaderBundle\Event\Event;

class ImageDelete
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onImageRemoved(Event $event)
    {
        if ($event->getObject() instanceof Image) {
            $this->logger->info('Image removed. You might want to delete generated thumbnails or do stuff here.');
        }
    }
}