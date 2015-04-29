<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

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
     * @var string
     */
    private $filepath;

    /**
     * @var integer
     */
    private $pageNumber;

    /**
     * @var integer
     */
    private $created;

    /**
     * @var integer
     */
    private $updated;

    /**
     * @var \Library\CatalogBundle\Entity\Writers
     */
    private $writer;

    /**
     * @var \Library\CommentBundle\Entity\Thread
     */
    private $thread;

    /**
     * @var \Library\VotesBundle\Entity\Rating
     */
    private $rating;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $readlists;


    /**
     * @var File $file
     */
    protected $file;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->readlists = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set file_name
     *
     * @param string $fileName
     * @return Books
     */
    public function setFilepath($fileName)
    {
        $this->filepath = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
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
     * Get created
     *
     * @return integer
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param integer $updated
     * @return Books
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return integer
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set writter
     *
     * @param \Library\CatalogBundle\Entity\Writers $writer
     * @return Books
     */
    public function setWriter(\Library\CatalogBundle\Entity\Writers $writer = null)
    {
        $this->writer = $writer;

        return $this;
    }

    /**
     * Get writter
     *
     * @return \Library\CatalogBundle\Entity\Writers
     */
    public function getWriter()
    {
        return $this->writer;
    }

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
     * @param \Library\VotesBundle\Entity\Rating $rating
     * @return Books
     */
    public function setRating(\Library\VotesBundle\Entity\Rating $rating = null)
    {
        $this->rating = $rating;

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
     * Add category
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $categories
     * @return Books
     */
    public function setCategories(\Doctrine\Common\Collections\ArrayCollection $categories)
    {
        $add = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($categories as $category) {
            if (!$this->categories->exists(function($key, $element)use($category){
                return $element->getId() == $category->getId();
            })) {
                $add->add($category);
            }
        }
        foreach($this->categories as $key => $category) {
            if (!$categories->exists(function($key, $element)use($category) {
                return $element->getId() == $category->getId();
            })) {
                $this->categories->remove($key);
            }
        }

        foreach($add as $category) {
            $this->categories->add($category);
        }
        return $this;
    }

    /**
     * Add category
     *
     * @param \Library\CatalogBundle\Entity\Categories $category
     * @return Books
     */
    public function addCategory(\Library\CatalogBundle\Entity\Categories $category)
    {
        $this->categories[] = $category;

        return $this;
    }


    /**
     * Remove category
     *
     * @param \Library\CatalogBundle\Entity\Categories $category
     */
    public function removeCategory(\Library\CatalogBundle\Entity\Categories $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get category
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add readlist
     *
     * @param \Library\CatalogBundle\Entity\Readlists $readlist
     * @return Books
     */
    public function addReadlist(\Library\CatalogBundle\Entity\Readlists $readlist)
    {
        $this->readlists[] = $readlist;

        return $this;
    }

    /**
     * Remove readlist
     *
     * @param \Library\CatalogBundle\Entity\Readlists $readlist
     */
    public function removeReadlist(\Library\CatalogBundle\Entity\Readlists $readlist)
    {
        $this->readlists->removeElement($readlist);
    }

    /**
     * Get readlist
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReadlists()
    {
        return $this->readlists;
    }

    /**
     * Set readlist
     *
     * @return Books
     */
    public function setReadlists($readlists)
    {
        $this->readlists = $readlists;
        return $this;
    }

    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setFile(File $image = null)
    {
        $this->file = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updated = time();
        }
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

}
