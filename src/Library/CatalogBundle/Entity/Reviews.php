<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reviews
 */
class Reviews
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $text;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Library\CommentBundle\Entity\Thread
     */
    private $thread;

    /**
     * @var \Library\UserBundle\Entity\User
     */
    private $author;

    /**
     * @var \Library\CatalogBundle\Entity\Books
     */
    private $book;

    /**
     * @var integer
     * @Groups({"books", "guest"})
     */
    private $created;
    /**
     * @var \Library\VotesBundle\Entity\Rating
     * @Groups({"books", "guest"})
     */
    private $rating;


    /**
     * Set text
     *
     * @param string $text
     * @return Reviews
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
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
     * Set thread
     *
     * @param \Library\CommentBundle\Entity\Thread $thread
     * @return Reviews
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
     * Set author
     *
     * @param \Library\UserBundle\Entity\User $author
     * @return Reviews
     */
    public function setAuthor(\Library\UserBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Library\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return \Library\CatalogBundle\Entity\Books
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * @param Books $book
     * @return Reviews
     */
    public function setBook(\Library\CatalogBundle\Entity\Books $book)
    {
        $this->book = $book;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Reviews
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }


    public function updateTimestamps()
    {
        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime("now"));
        }

    }

    /**
     * Get created
     *
     * @return integer
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return Books
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get rating
     *
     * @return \Library\VotesBundle\Entity\Rating
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set rating
     *
     * @param \Library\VotesBundle\Entity\Rating $rating
     * @return Books
     */
    public function setRating(\Library\VotesBundle\Entity\Rating $rating = null)
    {
        $this->rating = $rating;

        return $this;
    }




}
