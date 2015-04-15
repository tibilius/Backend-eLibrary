<?php

namespace LibraryCatalogBundle\Entity;

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
     * @var integer[]
     */
    private $cases;

    /**
     * @var \Library\CatalogBundle\Entity\Writers
     */
    private $writter;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $readlist;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->category = new \Doctrine\Common\Collections\ArrayCollection();
        $this->readlist = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param integer[] $cases
     * @return Books
     */
    public function setCases($cases)
    {
        $this->cases = $cases;

        return $this;
    }

    /**
     * Get cases
     *
     * @return integer[] 
     */
    public function getCases()
    {
        return $this->cases;
    }

    /**
     * Set writter
     *
     * @param \Library\CatalogBundle\Entity\Writers $writter
     * @return Books
     */
    public function setWritter(\Library\CatalogBundle\Entity\Writers $writter = null)
    {
        $this->writter = $writter;

        return $this;
    }

    /**
     * Get writter
     *
     * @return \Library\CatalogBundle\Entity\Writers
     */
    public function getWritter()
    {
        return $this->writter;
    }

    /**
     * Add category
     *
     * @param \Library\CatalogBundle\Entity\Categories $category
     * @return Books
     */
    public function addCategory(\Library\CatalogBundle\Entity\Categories $category)
    {
        $this->category[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Library\CatalogBundle\Entity\Categories $category
     */
    public function removeCategory(\Library\CatalogBundle\Entity\Categories $category)
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
     * Add readlist
     *
     * @param \Library\CatalogBundle\Entity\Readlists $readlist
     * @return Books
     */
    public function addReadlist(\Library\CatalogBundle\Entity\Readlists $readlist)
    {
        $this->readlist[] = $readlist;

        return $this;
    }

    /**
     * Remove readlist
     *
     * @param \Library\CatalogBundle\Entity\Readlists $readlist
     */
    public function removeReadlist(\Library\CatalogBundle\Entity\Readlists $readlist)
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
     * @var \Library\CommentBundle\Entity\Thread
     */
    private $thread;

    /**
     * @var \libary\VotesBundle\Entity\Rating
     */
    private $rating;


    /**
     * Set thread
     *
     * @param \Library\CommentBundle\Entity\Thread $thread
     * @return Books
     */
    public function setThread(\Library\CommentBundle\Entity\Thread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \Library\CommentBundle\Entity\Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set rating
     *
     * @param \libary\VotesBundle\Entity\Rating $rating
     * @return Books
     */
    public function setRating(\libary\VotesBundle\Entity\Rating $rating = null)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return \libary\VotesBundle\Entity\Rating 
     */
    public function getRating()
    {
        return $this->rating;
    }
}
