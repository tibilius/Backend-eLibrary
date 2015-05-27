<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy,
    JMS\Serializer\Annotation\Exclude,
    JMS\Serializer\Annotation\VirtualProperty,
    JMS\Serializer\Annotation\SerializedName,
    JMS\Serializer\Annotation\Type,
    JMS\Serializer\Annotation\Groups,
    JMS\Serializer\Annotation\MaxDepth;
/**
 * Readlists
 * @ExclusionPolicy("none")
 */
class Readlists
{
    /**
     * @var integer
     * @Groups({"id", "readlist"})
     */
    private $id;

    /**
     * @var string
     * @Groups({"readlist"})
     */
    private $name;

    /**
     * @var string
     * @Groups({"readlist"})
     */
    private $color;

    /**
     * @var string
     * @Groups({"readlist"})
     */
    private $type;

    /**
     * @var \Library\UserBundle\Entity\User
     * @Groups({"readlist"})
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"readlist"})
     */
    private $books;

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
     * Set type
     *
     * @param string $type
     * @return Readlists
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     * Add books
     *
     * @param \Library\CatalogBundle\Entity\ReadlistsBooks $book
     * @return Readlists
     */
    public function addBook(\Library\CatalogBundle\Entity\ReadlistsBooks $book)
    {
        $book->setReadlist($this);
        $this->books->add($book);
        return $this;
    }

    /**
     * Remove books
     *
     * @param \Library\CatalogBundle\Entity\ReadlistsBooks $book
     * @return Readlists
     */
    public function removeBook(\Library\CatalogBundle\Entity\ReadlistsBooks $book)
    {
        $this->books->removeElement($book);
        return $this;
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
}
