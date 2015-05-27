<?php

namespace Acme\TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reviews
 */
class Reviews
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \Acme\TestBundle\Entity\Books
     */
    private $book;

    /**
     * @var \Acme\TestBundle\Entity\Users
     */
    private $author;

    /**
     * @var \Acme\TestBundle\Entity\Thread
     */
    private $thread;


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
     * Set title
     *
     * @param string $title
     * @return Reviews
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

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
}
