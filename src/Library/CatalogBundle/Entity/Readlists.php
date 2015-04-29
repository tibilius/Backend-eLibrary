<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Readlists
 */
class Readlists
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
    private $color;

    /**
     * @var boolean
     */
    private $inRead;

    /**
     * @var \Library\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $book;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->book = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Readlists
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
     * Set color
     *
     * @param string $color
     * @return Readlists
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set inRead
     *
     * @param boolean $inRead
     * @return Readlists
     */
    public function setInRead($inRead)
    {
        $this->inRead = $inRead;

        return $this;
    }

    /**
     * Get inRead
     *
     * @return boolean 
     */
    public function getInRead()
    {
        return $this->inRead;
    }

    /**
     * Set user
     *
     * @param \Library\UserBundle\Entity\User $user
     * @return Readlists
     */
    public function setUser(\Library\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Library\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add book
     *
     * @param \Library\CatalogBundle\Entity\Books $book
     * @return Readlists
     */
    public function addBook(\Library\CatalogBundle\Entity\Books $book)
    {
        $this->book[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \Library\CatalogBundle\Entity\Books $book
     */
    public function removeBook(\Library\CatalogBundle\Entity\Books $book)
    {
        $this->book->removeElement($book);
    }

    /**
     * Get book
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBook()
    {
        return $this->book;
    }
}
