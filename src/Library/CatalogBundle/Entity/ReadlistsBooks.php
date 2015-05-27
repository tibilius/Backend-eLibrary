<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReadlistsBooks
 */
class ReadlistsBooks
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $fact;

    /**
     * @var integer
     */
    private $plan;

    /**
     * @var \Library\CatalogBundle\Entity\Books
     */
    private $book;

    /**
     * @var \Library\CatalogBundle\Entity\Readlists
     */
    private $readlist;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fact
     *
     * @param integer $fact
     * @return ReadlistsBooks
     */
    public function setFact($fact)
    {
        $this->fact = $fact;

        return $this;
    }

    /**
     * Get fact
     *
     * @return integer 
     */
    public function getFact()
    {
        return $this->fact;
    }

    /**
     * Set plan
     *
     * @param integer $plan
     * @return ReadlistsBooks
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return integer 
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set book
     *
     * @param \Library\CatalogBundle\Entity\Books $book
     * @return ReadlistsBooks
     */
    public function setBook(\Library\CatalogBundle\Entity\Books $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \Library\CatalogBundle\Entity\Books 
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set readlist
     *
     * @param \Library\CatalogBundle\Entity\Readlists $readlist
     * @return ReadlistsBooks
     */
    public function setReadlist(\Library\CatalogBundle\Entity\Readlists $readlist = null)
    {
        $this->readlist = $readlist;

        return $this;
    }

    /**
     * Get readlist
     *
     * @return \Library\CatalogBundle\Entity\Readlists 
     */
    public function getReadlist()
    {
        return $this->readlist;
    }
}
