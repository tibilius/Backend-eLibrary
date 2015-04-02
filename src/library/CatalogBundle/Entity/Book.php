<?php

namespace library\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 */
class Book
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
     * @var \library\CatalogBundle\Entity\Category
     */
    private $category_id;


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
     * @return Book
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
     * @return Book
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
     * Set category_id
     *
     * @param \library\CatalogBundle\Entity\Category $categoryId
     * @return Book
     */
    public function setCategoryId(\library\CatalogBundle\Entity\Category $categoryId = null)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get category_id
     *
     * @return \library\CatalogBundle\Entity\Category 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }
    /**
     * @var \library\CatalogBundle\Entity\Category
     */
    private $category;


    /**
     * Set category
     *
     * @param \library\CatalogBundle\Entity\Category $category
     * @return Book
     */
    public function setCategory(\library\CatalogBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \library\CatalogBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}
