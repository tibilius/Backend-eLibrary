<?php

namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reviews
 */
class Reviews
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Acme\TestBundle\Entity\Thread
     */
    private $thread;

    /**
     * @var \Acme\TestBundle\Entity\Users
     */
    private $author;


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
     * @param \Acme\TestBundle\Entity\Thread $thread
     * @return Reviews
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
     * Set author
     *
     * @param \Acme\TestBundle\Entity\Users $author
     * @return Reviews
     */
    public function setAuthor(\Acme\TestBundle\Entity\Users $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Acme\TestBundle\Entity\Users 
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * @var \Acme\TestBundle\Entity\Books
     */
    private $book;


    /**
     * Set book
     *
     * @param \Acme\TestBundle\Entity\Books $book
     * @return Reviews
     */
    public function setBook(\Acme\TestBundle\Entity\Books $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \Acme\TestBundle\Entity\Books 
     */
    public function getBook()
    {
        return $this->book;
    }
}
