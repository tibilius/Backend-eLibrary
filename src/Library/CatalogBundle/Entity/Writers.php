<?php

namespace Library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation\Groups;
/**
 *  Writers
 */
class Writers
{
    /**
     * @var integer
     * @Groups({"id", "writers"})
     */
    private $id;

    /**
     * @var string
     * @Groups({"writers"})
     */
    private $firstName;

    /**
     * @var string
     * @Groups({"writers"})
     */
    private $middleName;

    /**
     * @var string
     * @Groups({"writers"})
     */
    private $lastName;

    /**
     * @var string
     * @Groups({"writers"})
     */
    private $description;

    /**
     * @var string
     * @Groups({"writers"})
     */
    private $filepath;

    /**
     *
     * @var File $file
     * @Groups({"writers"})
     */
    protected $file;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Groups({"writers", "books"})
     */
    private $books;


    public function __toString() {
        return $this->getId() .':' .$this->getFirstName().$this->getLastName();
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
     * Set firstName
     *
     * @param string $firstName
     * @return Writers
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     * @return Writers
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string 
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Writers
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Writers
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
     * Set pictures
     *
     * @param string $filepath
     * @return Writers
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;

        return $this;
    }

    /**
     * Get pictures
     *
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }


    /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @return Writers
     */

    public function setFile(File $image = null)
    {
        $this->file = $image;
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
}
