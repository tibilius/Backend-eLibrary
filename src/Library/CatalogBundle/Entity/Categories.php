<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categories
 */
class Categories
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $books;


    /**
     * @var integer
     */
    private $last;

    /**
     * @var integer
     */
    private $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Categories
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
     * @return Categories
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
     * Add books
     *
     * @param \Library\CatalogBundle\Entity\Books $book
     * @return Categories
     */
    public function addBook(\Library\CatalogBundle\Entity\Books $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
     * Remove books
     *
     * @param \Library\CatalogBundle\Entity\Books $book
     */
    public function removeBook(\Library\CatalogBundle\Entity\Books $book)
    {
        $this->books->removeElement($book);
    }

    /**
     * Get books
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @return int
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @param int $last
     * @return Categories
     */
    public function setLast($last)
    {
        $this->last = $last;
        return $this;
    }

    /**
     * @return int
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param int $items
     * @return Categories
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }





}
