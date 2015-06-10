<?php
namespace Library\CatalogBundle\Entity;

class ReadlistsBooksSort
{
    private $readlists;

    function __construct()
    {
        $this->readlists = [];
    }

    public function getReadlists()
    {
        return $this->readlists;
    }

    public function setReadlists($readlists)
    {
        $this->readlists = $readlists;
        return $this;
    }

    public function addReadlist($entity) {
        $this->readlists[] = $entity;
        return $this;
    }
    public function removeReadlist($entity) {
        return $this;
    }


}