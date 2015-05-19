<?php
namespace Library\CatalogBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;

class ImageShow {

    /**
     * @var \Vich\UploaderBundle\Storage\FileSystemStorage
     */
    protected $vichStorage;

    public function __construct($storage) {
        $this->vichStorage = $storage;
    }

    public function postLoad(LifecycleEventArgs $args){
        $entity = &$args->getEntity();
        if (method_exists($entity, 'getFile') && $file = $entity->getFile()) {
            $entity->setFilepath($this->vichStorage->resolveUri($entity, 'file'));
        }
    }

    public function postPersist(LifecycleEventArgs $args) {
//        $entity = &$args->getEntity();
//        if (method_exists($entity, 'getFile') && $file = $entity->getFile()) {
//            $entity->setFilepath($this->vichStorage->resolveUri($entity, 'file'));
//        }
    }
}