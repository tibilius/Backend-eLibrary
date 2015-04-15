<?php

namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Books
 */
class Books
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $isbn;

    /**
     * @var integer
     */
    private $pageNumber;

    /**
     * @var integerarray
     */
    private $cases;

    /**
     * @var \Acme\TestBundle\Entity\Writers
     */
    private $writter;

    /**
     * @var \Acme\TestBundle\Entity\Thread
     */
    private $thread;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $readlist;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->readlist = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Books
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Books
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isbn
     *
     * @param string $isbn
     * @return Books
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get isbn
     *
     * @return string 
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set pageNumber
     *
     * @param integer $pageNumber
     * @return Books
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    /**
     * Get pageNumber
     *
     * @return integer 
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Set cases
     *
     * @param \integerarray $cases
     * @return Books
     */
    public function setCases(\integerarray $cases)
    {
        $this->cases = $cases;

        return $this;
    }

    /**
     * Get cases
     *
     * @return \integerarray 
     */
    public function getCases()
    {
        return $this->cases;
    }

    /**
     * Set writter
     *
     * @param \Acme\TestBundle\Entity\Writers $writter
     * @return Books
     */
    public function setWritter(\Acme\TestBundle\Entity\Writers $writter = null)
    {
        $this->writter = $writter;

        return $this;
    }

    /**
     * Get writter
     *
     * @return \Acme\TestBundle\Entity\Writers 
     */
    public function getWritter()
    {
        return $this->writter;
    }

    /**
     * Set thread
     *
     * @param \Acme\TestBundle\Entity\Thread $thread
     * @return Books
     */
    public function setThread(\Acme\TestBundle\Entity\Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \Acme\TestBundle\Entity\Thread 
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Add readlist
     *
     * @param \Acme\TestBundle\Entity\Readlists $readlist
     * @return Books
     */
    public function addReadlist(\Acme\TestBundle\Entity\Readlists $readlist)
    {
        $this->readlist[] = $readlist;

        return $this;
    }

    /**
     * Remove readlist
     *
     * @param \Acme\TestBundle\Entity\Readlists $readlist
     */
    public function removeReadlist(\Acme\TestBundle\Entity\Readlists $readlist)
    {
        $this->readlist->removeElement($readlist);
    }

    /**
     * Get readlist
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReadlist()
    {
        return $this->readlist;
    }

    /**
     * Add category
     *
     * @param \Acme\TestBundle\Entity\Categories $category
     * @return Books
     */
    public function addCategory(\Acme\TestBundle\Entity\Categories $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Acme\TestBundle\Entity\Categories $category
     */
    public function removeCategory(\Acme\TestBundle\Entity\Categories $category)
    {
        $this->category->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @var \Acme\TestBundle\Entity\Rating
     */
    private $rating;


    /**
     * Set rating
     *
     * @param \Acme\TestBundle\Entity\Rating $rating
     * @return Books
     */
    public function setRating(\Acme\TestBundle\Entity\Rating $rating = null)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return \Acme\TestBundle\Entity\Rating 
     */
    public function getRating()
    {
        return $this->rating;
    }
}