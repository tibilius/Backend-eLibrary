<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation\Groups;

/**
 * Books
 */
class Books
{
    /**
     * @var File $file
     */
    protected $file;
    /**
     * @var integer
     * @Groups({"id", "books", "guest"})
     */
    private $id;
    /**
     * @var string
     * @Groups({"books", "guest"})
     */
    private $name;
    /**
     * @var string
     * @Groups({"books"})
     */
    private $description;
    /**
     * @var string
     * @Groups({"books", "guest"})
     */
    private $teaser;
    /**
     * @var string
     * @Groups({"books", "guest"})
     */
    private $isbn;
    /**
     * @var string
     * @Groups({"books", "guest"})
     */
    private $filepath;
    /**
     * @var integer
     * @Groups({"books", "guest"})
     */
    private $pageNumber;
    /**
     * @var integer
     * @Groups({"books", "guest"})
     */
    private $created;
    /**
     * @var integer
     * @Groups({"books", "guest"})
     */
    private $updated;

    /**
     * @var boolean
     * @Groups({"books", "guest"})
     */
    private $published;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"books", "guest"})
     */
    private $writers;
    /**
     * @var \Library\CommentBundle\Entity\Thread
     * @Groups({"books", "guest"})
     */
    private $thread;

    /**
     * @var \Library\UserBundle\Entity\User
     * @Groups({"books", "guest"})
     */
    private $owner;
    /**
     * @var \Library\VotesBundle\Entity\Rating
     * @Groups({"books", "guest"})
     */
    private $rating;
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"books", "guest"})
     */
    private $categories;
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"books", "guest"})
     */
    private $readlists;
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"books", "guest"})
     */
    private $reviews;

    /**
     * @var
     * @Groups({"books", "guest"})
     */
    protected $commentCount;
    /**
     * @var
     * @Groups({"books", "guest"})
     */
    protected $reviewsCount;
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @Groups({"books", "guest"})
     */
    protected $userReadlistsIds;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->readlists = new \Doctrine\Common\Collections\ArrayCollection();
        $this->writers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userReadlistsIds = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }

    /**
     * @param string $teaser
     * @return Books
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;
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
     * Get file_name
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
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
     * Get pageNumber
     *
     * @return integer
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
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
     * Get updated
     *
     * @return integer
     */
    public function getUpdated()
    {
        return $this->updated;
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
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * @param boolean $published
     * @return Books
     */
    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }



    /**
     * Add writer
     *
     * @param \Library\CatalogBundle\Entity\Writers $writer
     * @return Books
     */
    public function addWriter(\Library\CatalogBundle\Entity\Writers $writer)
    {
        $writer->addBook($this);
        $this->writers[] = $writer;
        return $this;
    }

    /**
     * Remove writer
     *
     * @param \Library\CatalogBundle\Entity\Writers $writer
     * @return Books
     */
    public function removeWriter(\Library\CatalogBundle\Entity\Writers $writer)
    {
        $this->writers->removeElement($writer);
        return $this;
    }

    /**
     * Get writer
     *
     * @return \Library\CatalogBundle\Entity\Writers
     */
    public function getWriters()
    {
        return $this->writers;
    }

    /**
     * Set writer
     *
     * @param \Doctrine\Common\Collections\Collection $writers
     * @return Books
     */
    public function setWriters(\Doctrine\Common\Collections\Collection $writers = null)
    {
        $add = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($writers as $writer) {
            if (!$this->writers->exists(function ($key, $element) use ($writer) {
                return $element->getId() == $writer->getId();
            })
            ) {
                $add->add($writer);
            }
        }
        foreach ($this->categories as $key => $writer) {
            if (!$writers->exists(function ($key, $element) use ($writer) {
                return $element->getId() == $writer->getId();
            })
            ) {
                $this->writers->remove($key);
            }
        }
        foreach ($add as $writer) {
            $this->writers->add($writer);
        }
        return $this;
    }

    /**
     * Add writer
     *
     * @param \Library\CatalogBundle\Entity\Reviews $review
     * @return Books
     */
    public function addReview(\Library\CatalogBundle\Entity\Reviews $review)
    {
        $review->setBook($this);
        $this->reviews[] = $review;
        return $this;
    }

    /**
     * Remove writer
     *
     * @param \Library\CatalogBundle\Entity\Reviews $review
     * @return Books
     */
    public function removeReview(\Library\CatalogBundle\Entity\Reviews $review)
    {
        $this->reviews->removeElement($review);
        return $this;
    }

    /**
     * Get writer
     *
     * @return \Library\CatalogBundle\Entity\Reviews
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * Set writer
     *
     * @param \Doctrine\Common\Collections\Collection $reviews
     * @return Books
     */
    public function setReviews(\Doctrine\Common\Collections\Collection $reviews = null)
    {
        $add = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($reviews as $review) {
            if (!$this->reviews->exists(function ($key, $element) use ($review) {
                return $element->getId() == $review->getId();
            })
            ) {
                $add->add($review);
            }
        }
        foreach ($this->categories as $key => $review) {
            if (!$reviews->exists(function ($key, $element) use ($review) {
                return $element->getId() == $review->getId();
            })
            ) {
                $this->reviews->remove($key);
            }
        }
        foreach ($add as $review) {
            $this->reviews->add($review);
        }
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
     * @return \Library\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param \Library\UserBundle\Entity\User $owner
     * @return Books
     */
    public function setOwner(\Library\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;
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

    /**
     * Add category
     *
     * @param \Library\CatalogBundle\Entity\Categories $category
     * @return Books
     */
    public function addCategory(\Library\CatalogBundle\Entity\Categories $category)
    {
        $category->addBook($this);
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
     * Add category
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $categories
     * @return Books
     */
    public function setCategories(\Doctrine\Common\Collections\ArrayCollection $categories)
    {
        $add = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($categories as $category) {
            if (!$this->categories->exists(function ($key, $element) use ($category) {
                return $element->getId() == $category->getId();
            })
            ) {
                $add->add($category);
            }
        }
        foreach ($this->categories as $key => $category) {
            if (!$categories->exists(function ($key, $element) use ($category) {
                return $element->getId() == $category->getId();
            })
            ) {
                $this->categories->remove($key);
            }
        }

        foreach ($add as $category) {
            $this->categories->add($category);
        }
        return $this;
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
     * @return File
     */
    public function getFile()
    {
        return $this->file;
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
            $this->setUpdated(new \DateTime("now"));
        }
    }

    public function updateTimestamps()
    {
        $this->setUpdated(new \DateTime("now"));
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
     * @return mixed
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * @param mixed $commentCount
     * @return Books
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReviewsCount()
    {
        return $this->reviewsCount;
    }

    /**
     * @param mixed $reviewsCount
     * @return Books
     */
    public function setReviewsCount($reviewsCount)
    {
        $this->reviewsCount = $reviewsCount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserReadlistsIds()
    {
        return $this->userReadlistsIds;
    }

    /**
     * @param mixed $userReadlistsIds
     * @return Books
     */
    public function setUserReadlistsIds($userReadlistsIds)
    {
        $this->userReadlistsIds = $userReadlistsIds;
        return $this;
    }

    /**
     * @param integer $readlist_id
     * @param integer $readlist_book_id
     * @return Books
     */
    public function addUserReadlistsId($readlist_id, $readlist_book_id)
    {
        $this->userReadlistsIds[] = compact('readlist_id', 'readlist_book_id');
        return $this;
    }

    /**
     * @param mixed $id
     * @return Books
     */
    public function removeUserReadlistsId($id)
    {
        $this->userReadlistsIds->removeElement($id);
        return $this;
    }


}
